<?php

if (count($argv) < 4) {
    echo "Usage: php codeforces.php <codeforces_handle> <student_id>\n";
    exit(1);
}
$handle = $argv[1];
$studentId = $argv[2];
$lastSubmission=$argv[3];
$uniqueIndexSet = array();
$temp=$lastSubmission;
// Make a GET request to the VJudge API to retrieve the user's submissions
$base_url = 'https://vjudge.net/status/data?draw=1&start=';
$batch_size = 20;
$total_entries = 200;
// $csvheader = array('problem_id', 'Language', 'Submission_Time', 'verdict', 'student_id','submission_id');
$ourdata = array();

for ($start = 0; $start < $total_entries; $start += $batch_size) {
    $url = $base_url . $start . '&length=' . $batch_size . '&un=' . $handle;

    $options = array(
        'http' => array(
            'header' => 'Content-type: application/json',
            'method' => 'GET'
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    // Parse the JSON response and extract the relevant data
    $myjson = json_decode($response, true);

    foreach ($myjson['data'] as $x) {
        $unixToDatetime = date('Y-m-d H:i:s', $x['time'] / 1000);
        // var_dump($x['oj']);
        if($lastSubmission<$x['runId']){

            $url='https://vjudge.net/problem/'.$x['oj'] . '-' .$x['probNum'];

            print_r($x['oj']);
            if($x['oj']!='CodeForces' || $x['oj']!='SPOJ'){
                $listing = array($x['oj'].$x['probNum'], $x['language'], $unixToDatetime,$x['status'],$x['runId']);
                $index = array($x['oj'] . $x['probNum'], $x['oj'] . $x['probNum'],'vjudge',$url);
            }
            else{
                $listing = array($x['probNum'], $x['language'], $unixToDatetime,$x['status'],$x['runId']);
                $index = array($x['probNum'], $x['oj'] . $x['probNum'], strtolower($x['oj']),$url);
            }
            // if($x['oj']=='CodeForces'){
            //     $listing = array($x['probNum'], $x['language'], $unixToDatetime,$x['status'],$x['runId']);
            // }
            // elseif($x['oj']=='SPOJ'){
            //     $listing = array($x['probNum'], $x['language'], $unixToDatetime,$x['status'],$x['runId']);
            // }
            // else {
            //     $listing = array($x['oj'].$x['probNum'], $x['language'], $unixToDatetime,$x['status'],$x['runId']);
            // }
            // $url='https://vjudge.net/problem/'.$x['oj'] . '-' .$x['probNum'];
            // $index = array($x['probNum'], $x['oj'] . $x['probNum'], $x['oj'],$url);
            if (!in_array($index, $uniqueIndexSet)) {
                // Insert the index into the set
                $uniqueIndexSet[] = $index;
            }
            array_push($ourdata, $listing);
        }
        $temp=max($x['runId'], $temp);
    }
}
$lastSubmission=$temp;
// Write the data to a CSV file
// $filename = 'vjudge.csv';
// $fp = fopen($filename, 'w');
// fputcsv($fp, $csvheader);
// foreach ($ourdata as $listing) {
//     fputcsv($fp, $listing);
// }
// fclose($fp);
$host = 'localhost';
$dbname = 'db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO submissions (problem_id, language, submissiontime, verdict,submission_id,student_id) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($ourdata as $listing) {
        $listing[] = $studentId; // Add the student ID to the listing
        $stmt->execute($listing);
    }
    $query = "UPDATE handles SET vj_last_submission = :value WHERE id = :studentId";
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

    foreach($uniqueIndexSet as $unique)
    {
        // print_r($unique[0]);
        if(!array_key_exists($unique[0], $problemMap))
        {
            $stmt = $pdo->prepare("INSERT INTO problems (id, title,oj,url) VALUES (?, ?, ?,?)");
            $stmt->execute($unique);
        }
    }

}catch (PDOException $e) {
    echo 'Database connection failed: ' . $e->getMessage();
}
