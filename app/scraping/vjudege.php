<?php
// Make a GET request to the VJudge API to retrieve the user's submissions
$vjudge_user_name = array('mursalin_18');
$url = 'https://vjudge.net/status/data?draw=1&start=0&length=20&un=' . $vjudge_user_name[0] . '&OJId=All&probNum=&res=0&language=&onlyFollowee=false&orderBy=run_id&20paging_simple_numbers=1&Mine=6&_=167539930135';
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
$ourdata = array();
$csvheader = array('UserName', 'OJ', 'Problem_Name', 'Result', 'Language', 'Submission_Time');
foreach ($myjson['data'] as $x) {
    $unixToDatetime = date('Y-m-d H:i:s', $x['time'] / 1000);
    $listing = array($x['userName'], $x['oj'], $x['probNum'], $x['status'], $x['language'], $unixToDatetime);
    array_push($ourdata, $listing);
}

// Write the data to a CSV file
$filename = 'vjudge.csv';
$fp = fopen($filename, 'w');
fputcsv($fp, $csvheader);
foreach ($ourdata as $listing) {
    fputcsv($fp, $listing);
}
fclose($fp);
?>
