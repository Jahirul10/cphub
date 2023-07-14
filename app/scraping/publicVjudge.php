<?php

if (count($argv) <2 ) {
    echo "Usage: php codeforces.php <codeforces_handle> <student_id>\n";
    exit(1);
}
$handle = $argv[1];
if(empty($handle))exit();

$uniqueIndexSet = array();
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

        $language = $x['language'];

        if (strpos($language, "++") !== false) {
            $language = "C++";
        } elseif (strpos($language, "C#") !== false) {
            $language = "C#";
        } elseif (strpos($language, "DMD") !== false) {
            $language = "D";
        } elseif (strpos($language, "Go") !== false) {
            $language = "Go";
        } elseif (strpos($language, "Haskell") !== false) {
            $language = "Haskell";
        } elseif (strpos($language, "Java") !== false || strpos($language, "java") !== false ||strpos($language, "JAVA") !== false) {
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
        } elseif (strpos($language, "JavaScript") !== false || strpos($language, "javascript") !== false) {
            $language = "JavaScript";
        }elseif (strpos($language, "C") !== false || strpos($language, "c") !== false ||strpos($language, "GNU") !== false) {
            $language = "C";
        }else {
            $language = "Miscellaneous".$language;
        }


        $verdict=$x['status'];
        if ($verdict == 'Happy New Year!' || $verdict == 'Accepted') {
            $verdict = 'Accepted';
        } elseif (strpos($verdict, "Wrong answer") !== false || $verdict == 'Wrong Answer') {
            $verdict = 'Wrong answer';
        } elseif ($verdict == 'Time Limit Exceeded' || $verdict == 'Time limit exceeded') {
            $verdict = 'Time limit exceeded';
        } elseif ($verdict == 'Memory Limit Exceeded' || $verdict =='Memory limit exceeded') {
            $verdict = 'Memory limit exceeded';
        } elseif ($verdict == 'Compile Error' || $verdict == 'Compile error' || $verdict == 'Compilation error' || $verdict == 'Compilation Error') {
            $verdict = 'Compilation error';
        } elseif ($verdict == 'CHALLENGED') {
            $verdict = 'Hacked';
        } elseif ($verdict == 'PARTIAL') {
            $verdict = 'Partial';
        } else {
            $verdict = 'Runtime error';
        }

        $url='https://vjudge.net/problem/'.$x['oj'] . '-' .$x['probNum'];


        $listing = array($x['runId'],$x['probNum'],"Vjudge", $verdict, $language, $unixToDatetime);
        array_push($ourdata, $listing);
    }
}
echo json_encode($ourdata);
