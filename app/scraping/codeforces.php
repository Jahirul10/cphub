<?php
if (count($argv) < 4) {
    echo "Usage: php codeforces.php <codeforces_handle> <student_id>\n";
    exit(1);
}
$handle = $argv[1];
$studentId = $argv[2];
$lastSubmission=$argv[3];
$url = 'https://codeforces.com/api/user.status?handle=' . $handle . '&from=1&count=10000';
$options = array(
    'http' => array(
        'header' => 'Content-type: application/json',
        'method' => 'GET'
    )
);
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// $result = problem::select('id', 'title')->get();
// print_r($result);

$uniqueIndexSet = array();

$myjson = json_decode($response, true);
$ourdata = array();
$temp=$lastSubmission;

foreach ($myjson['result'] as $x) {

    if($lastSubmission<$x['id']){

        $date = date('Y-m-d H:i:s', $x['creationTimeSeconds']);
        $verdict = $x['verdict'];

        $language = $x['programmingLanguage'];

        if (strpos($language, "GNU GCC") !== false || strpos($language, "C11") !== false) {
            $language = "C";
        } elseif (strpos($language, "++") !== false) {
            $language = "C++";
        } elseif (strpos($language, "C#") !== false) {
            $language = "C#";
        } elseif (strpos($language, "DMD") !== false) {
            $language = "D";
        } elseif (strpos($language, "Go") !== false) {
            $language = "Go";
        } elseif (strpos($language, "Haskell") !== false) {
            $language = "Haskell";
        } elseif (strpos($language, "Java") !== false) {
            $language = "Java";
        } elseif (strpos($language, "Kotlin") !== false) {
            $language = "Kotlin";
        } elseif (strpos($language, "OCaml") !== false) {
            $language = "OCaml";
        } elseif (strpos($language, "Delphi") !== false) {
            $language = "Delphi";
        } elseif (strpos($language, "Pascal") !== false) {
            $language = "Pascal";
        } elseif (strpos($language, "Perl") !== false) {
            $language = "Perl";
        } elseif (strpos($language, "PHP") !== false) {
            $language = "PHP";
        } elseif (strpos($language, "Py") !== false) {
            $language = "Python";
        } elseif (strpos($language, "Ruby") !== false) {
            $language = "Ruby";
        } elseif (strpos($language, "Rust") !== false) {
            $language = "Rust";
        } elseif (strpos($language, "Scala") !== false) {
            $language = "Scala";
        } elseif (strpos($language, "JavaScript") !== false || strpos($language, "Node.js") !== false) {
            $language = "JavaScript";
        } else {
            $language = "Miscellaneous";
        }

        if ($verdict == 'OK') {
            $verdict = 'Accepted';
        } elseif ($verdict == 'REJECTED' || $verdict == 'WRONG_ANSWER') {
            $verdict = 'Wrong answer';
        } elseif ($verdict == 'TIME_LIMIT_EXCEEDED') {
            $verdict = 'Time limit exceeded';
        } elseif ($verdict == 'MEMORY_LIMIT_EXCEEDED') {
            $verdict = 'Memory limit exceeded';
        } elseif ($verdict == 'COMPILATION_ERROR') {
            $verdict = 'Compilation error';
        } elseif ($verdict == 'CHALLENGED') {
            $verdict = 'Hacked';
        } elseif ($verdict == 'PARTIAL') {
            $verdict = 'Partial';
        } else {
            $verdict = 'Runtime error';
        }

        $listing = array($x['id'], $x['contestId'].$x['problem']['index'], $language, $date, $verdict);


        $index = array($x['contestId'] . $x['problem']['index'], $x['problem']['name'], 'codeforces', $x['contestId'], $x['problem']['index']);
        if (!in_array($index, $uniqueIndexSet)) {
            // Insert the index into the set
            $uniqueIndexSet[] = $index;
        }
        // print_r($index);

        array_push($ourdata, $listing);

        $temp=max($x['id'], $temp);
    }
    else break;
    
}

if (empty($listing)) exit();

$lastSubmission=$temp;
// print_r($uniqueIndexSet);
$host = 'localhost';
$dbname = 'db';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO submissions (submission_id, problem_id, language, submissiontime, verdict, student_id) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($ourdata as $listing) {
        $listing[] = $studentId; // Add the student ID to the listing
        $stmt->execute($listing);
    }
    $query = "UPDATE handles SET cf_last_submission = :value WHERE id = :studentId";

    $statement = $pdo->prepare($query);
    $statement->bindParam(':value', $lastSubmission, PDO::PARAM_INT);
    $statement->bindValue(':studentId', $studentId, PDO::PARAM_INT);
    $statement->execute();

    $problemMap = array();

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT id, title FROM problems");
    $stmt->execute();

    // Fetch all rows as associative arrays
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        foreach ($result as $row) {
            $problem_id = $row["id"];
            $title = $row["title"];

            // Add the problem ID and title to the map
            $problemMap[$problem_id] = $title;
        }
    }

    // print_r($problemMap);

    foreach($uniqueIndexSet as $unique)
    {
        // print_r($unique[0]);
        if(!array_key_exists($unique[0], $problemMap))
        {
            $stmt = $pdo->prepare("INSERT INTO problems (id, title, oj, url) VALUES (?, ?, ?, ?)");
            $unique[] = 'https://codeforces.com/contest/'. $unique[3] . '/problem/' . $unique[4];
            $unique[3]=$unique[5];
            unset($unique[4]);
            unset($unique[5]);
            // print_r($unique);
            $stmt->execute($unique);
        }
    }

    echo 'Data inserted into the database successfully!';
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
?>
