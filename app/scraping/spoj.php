<?php
$url = "https://www.spoj.com/status/xilinx/all/start=0";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$html = new DOMDocument();
libxml_use_internal_errors(true); // enable error handling
$html->loadHTML($response);
libxml_use_internal_errors(false); // disable error handling

$table = $html->getElementsByTagName('tbody')->item(0);
$rows = array();
foreach ($table->getElementsByTagName('tr') as $row) {
    $tdata = array();
    foreach ($row->getElementsByTagName('td') as $cell) {
        $tdata[] = trim(preg_replace('/\s+/', ' ', $cell->textContent));
    }
    $data = array_slice($tdata, 0, -3);
    $data[] = end($tdata);
    $rows[] = $data;
}

$filename = "spoj.csv";
$file = fopen($filename, 'w');
fputcsv($file, array('Submission ID', 'Problem Code', 'User', 'Result', 'Memory', 'Time', 'Language', 'Submit Time'));
foreach ($rows as $row) {
    fputcsv($file, $row);
}
fclose($file);
?>
