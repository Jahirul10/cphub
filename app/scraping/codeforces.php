<?php
// Make a GET request to the Codeforces API to retrieve the user's submissions
$handle = 'ostrich_';
$url = 'https://codeforces.com/api/user.status?handle=' . $handle . '&from=1&count=1000';
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
$csvheader = array('Submission ID', 'Username', 'Problem_Name', 'Language', 'Verdict', 'Date');
foreach ($myjson['result'] as $x) {
    $date = date('Y-m-d H:i:s', $x['creationTimeSeconds']);
    $listing = array($x['id'], $handle, $x['problem']['name'], $x['programmingLanguage'], $x['verdict'], $date);
    array_push($ourdata, $listing);
}

// Write the data to a CSV file
$filename = 'codeforces.csv';
$fp = fopen($filename, 'w');
fputcsv($fp, $csvheader);
foreach ($ourdata as $listing) {
    fputcsv($fp, $listing);
}
fclose($fp);
?>
