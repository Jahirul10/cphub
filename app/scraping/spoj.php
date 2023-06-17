<?php
function scrapeSubmissions($handle, $studentId, $lastSubmission)
{
    $start = 0;
    $maxStart = 100;
    $increment = 20;
    $temp = $lastSubmission;

    // Database connection details
    $host = 'localhost';
    $dbname = 'db';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    $stmt = $pdo->prepare("INSERT IGNORE INTO submissions (submission_id, submissiontime, problem_id, language, verdict, student_id) VALUES (?, ?, ?, ?, ?, ?)");

    $problemMap = array();

    // Prepare and execute the query
    $stmtToGetProblem = $pdo->prepare("SELECT id, title FROM problems");
    $stmtToGetProblem->execute();

    $existingSubmissions = array();
    $queryToInsertProblem = $pdo->prepare("INSERT IGNORE INTO problems (id, title, url, oj) Values(?, ?, ?, ?)");

    while ($start <= $maxStart) {
        $url = "https://www.spoj.com/status/$handle/all/start=$start";
        // print_r($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CAINFO, "C:/cacert.pem");
        $response = curl_exec($ch);

        if ($response === false) {
            die("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        $html = new DOMDocument();
        libxml_use_internal_errors(true);
        $loaded = $html->loadHTML($response);
        if (!$loaded) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                echo $error->message;
            }
            libxml_clear_errors();
            die("Failed to load HTML.");
        }
        libxml_use_internal_errors(false);

        $tables = $html->getElementsByTagName('tbody');
        if ($tables->length > 0) {
            $table = $tables->item(0);
            if ($table instanceof DOMElement) {
                $rows = array();
                $trElements = $table->getElementsByTagName('tr');
                foreach ($trElements as $row) {
                    $tdata = array();
                    $tdElements = $row->getElementsByTagName('td');
                    foreach ($tdElements as $index => $cell) {
                        $textContent = trim(preg_replace('/\s+/', ' ', $cell->textContent));
                        if ($index === 2) {
                            $linkElement = $cell->getElementsByTagName('a')->item(0);
                            if ($linkElement instanceof DOMElement) {
                                $title = $linkElement->getAttribute('title');
                                $tdata[] = $title;
                            }
                        }
                        $tdata[] = $textContent;
                    }
                    // print_r($tdata);
                    $data = array_slice($tdata, 0, 3);
                    // echo 'This ONE';
                    // print_r($data);
                    $data[] = $tdata[4];
                    $data[] = end($tdata);
                    $data[] = $studentId;

                    $submissionId = $data[0];

                    if (!in_array($submissionId, $existingSubmissions) && $submissionId > $lastSubmission) {
                        $stmt->execute($data);
                        $temp = max($temp, $submissionId);
                        $existingSubmissions[] = $submissionId; // Add the submission ID to the existing submissions array

                        $problemId = $data[2];
                        if (!array_key_exists($problemId, $problemMap)) {
                            $problemName = $tdata[3];
                            $uniqueProblems = array();
                            $uniqueProblems[] = $problemId;
                            $uniqueProblems[] = $problemName;
                            $uniqueProblems[] = 'https://www.spoj.com/problems/' . $problemId;
                            $uniqueProblems[] = 'spoj';
                            $queryToInsertProblem->execute($uniqueProblems);

                            print_r($uniqueProblems);
                        }
                    }
                }
            } else {
                echo "Invalid table element.";
            }
        } else {
            echo "No table found.";
        }

        $start += $increment;
    }

    print_r($temp);
    $lastSubmission = $temp;

    $query = "UPDATE handles SET spoj_last_submission = :value WHERE id = :studentId";

    $statement = $pdo->prepare($query);
    $statement->bindParam(':value', $lastSubmission, PDO::PARAM_INT);
    $statement->bindValue(':studentId', $studentId, PDO::PARAM_INT);
    $statement->execute();

    // Close the database connection
    $pdo = null;

    return $lastSubmission;
}

// Usage:
if (count($argv) < 4) {
    echo "Usage: php codeforces.php <codeforces_handle> <student_id>\n";
    exit(1);
}

$handle = $argv[1]; // Replace with the desired username
$studentId = $argv[2]; // Replace with the student ID
$lastSubmission = $argv[3]; // Replace with the last submission value

$lastSubmission = scrapeSubmissions($handle, $studentId, $lastSubmission);

// echo "Last Submission: $lastSubmission";
