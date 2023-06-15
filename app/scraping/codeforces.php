<?php
echo count($argv);
if (count($argv) < 3) {
    echo "Usage: php codeforces.php <codeforces_handle> <student_id>\n";
    exit(1);
}
$handle = $argv[1];
$studentId = $argv[2];

$url = 'https://codeforces.com/api/user.status?handle=' . $handle . '&from=1&count=1000';
$options = array(
    'http' => array(
        'header' => 'Content-type: application/json',
        'method' => 'GET'
    )
);
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

$myjson = json_decode($response, true);
$ourdata = array();
foreach ($myjson['result'] as $x) {
    $date = date('Y-m-d H:i:s', $x['creationTimeSeconds']);
    $listing = array($x['id'], $x['problem']['name'], $x['programmingLanguage'], $date, $x['verdict']);
    array_push($ourdata, $listing);
}

$host = 'localhost';
$dbname = 'db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO submissions (id, problem_id, language, submissiontime, verdict, student_id) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($ourdata as $listing) {
        $listing[] = $studentId; // Add the student ID to the listing
        $stmt->execute($listing);
    }

    echo 'Data inserted into the database successfully!';
} catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
?>
