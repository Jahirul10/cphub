<?php
if (count($argv) < 2) {
    echo "Usage: php codeforces.php <codeforces_handle> <student_id>\n";
    exit(1);
}
$handle = $argv[1];
$url = 'https://codeforces.com/api/user.status?handle=' . $handle . '&from=1&count=10000';
$options = array(
    'http' => array(
        'header' => 'Content-type: application/json',
        'method' => 'GET'
    )
);
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

$uniqueIndexSet = array();

$myjson = json_decode($response, true);
$ourdata = array();

foreach ($myjson['result'] as $x) {


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
        array_push($ourdata, $listing);
}
echo json_encode($ourdata);