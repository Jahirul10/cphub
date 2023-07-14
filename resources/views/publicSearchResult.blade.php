<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!--Navbar Design start -->
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('dashboard') }}">
                <h4>CSERU</h4>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Compare</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Log In</a>
                    </li>
            </div>
        </div>

    </nav>

    <!-- Content of search-page -->
    <div class="vh-100 d-flex justify-content-center mt-4">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12">

                    <div class="row">
                        <div class="card bg-white">
                            <div class="card-body p-4">

                                <div class="row">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="codeforces" class="form-label">Codeforces Handle</label>
                                            <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="codeforces" placeholder="Codeforces" aria-label="Codeforces" value={{$codeforcesHandle}}>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="vjudge" class="form-label">Vjudge Handle</label>
                                            <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="vjudge" placeholder="Vjudge" aria-label="Vjudge" value={{$vjudgeHandle}}>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="spoj" class="form-label">Spoj Handle</label>
                                            <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="spoj" placeholder="Spoj" aria-label="Spoj" value={{$spojHandle}}>
                                        </div>
                                    </div>
                                    <div class="col-md mt-4">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary btn-md w-100 mt-sm-3" id="submitBtn">Submit</button>

                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script>
                                            $(document).ready(function() {
                                                $('#submitBtn').click(function() {
                                                    var codeforcesHandle = $('#codeforces').val();
                                                    var vjudgeHandle = $('#vjudge').val();
                                                    var spojHandle = $('#spoj').val();
                                                    
                                                    
                                                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                                                    // Create the data object to be sent in the POST request
                                                    var data = {
                                                        _token: csrfToken,
                                                        codeforces: codeforcesHandle,
                                                        vjudge: vjudgeHandle,
                                                        spoj: spojHandle
                                                    };
                                                    
                                                    // Send the POST request to the desired endpoint
                                                    $.post('/searchdata', data, function(response) {
                                                        // Handle the response from the server
                                                        // console.log(response);
                                                        $('body').html(response);
                                                    });
                                                });
                                            });
                                            function sanitizeInput(input) {
                                                // Remove leading and trailing whitespace
                                                input = input.trim();
                                                
                                                // Remove any potentially harmful characters or HTML tags
                                                input = input.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                                                
                                                // Return the sanitized input
                                                return input;
                                            }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="card bg-white">
                            <div class="card-body p-4">

                                <div class="row justify-content-evenly mt-3 mb-3">
                                    <div class="" style="display:inline-block; width:30%;">
                                        <canvas id="pie-chart-1"></canvas>
                                    </div>
                                    <div class="" style="display:inline-block; width:30%;">
                                        <canvas id="pie-chart-2"></canvas>
                                    </div>

                                    <div id="verdictCountsData" data-verdictCounts=""></div>
                                    <div id="languageCountsData" data-languageCounts=""></div>
                                </div>
                            </div>
                            <hr>

                            <div class="mt-3">
                                <h2 class="p-2">Submission Records</h2>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Submission ID</th>
                                            <th scope="col">Problem name</th>
                                            <th scope="col">OJ</th>
                                            <th scope="col">Verdicts</th>
                                            <th scope="col">Language</th>
                                            <th scope="col">Submission time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($mergedData as $item)
                                        <tr class="submission-row">
                                            <td>{{ $item[0] }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td>{{ $item[2] }}</td>
                                            <td>{{ $item[3] }}</td>
                                            <td>{{ $item[4] }}</td>
                                            <td>{{ $item[5] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="row mt-5 ms-2">
                                <div class="col-12">
                                    <div class="chart-container" style="width: 100%;">
                                        <div id="calendar_basic" style="height: auto;"></div>
                                    </div>
                                </div>

                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                <div id="dailyCountData" data-dailycount=""></div>
                                <script type="text/javascript">
                                    google.charts.load("current", {
                                        packages: ["calendar"]
                                    });
                                    google.charts.setOnLoadCallback(drawChart);

                                    function drawChart() {
                                        var dataTable = new google.visualization.DataTable();
                                        dataTable.addColumn({
                                            type: 'date',
                                            id: 'Date'
                                        });
                                        dataTable.addColumn({
                                            type: 'number',
                                            id: 'Won/Loss'
                                        });
                                        var dailycount = JSON.parse(document.getElementById('dailyCountData').getAttribute('data-dailycount'));
                                        console.log(dailycount);
                                        for (var i = 0; i < dailycount.length; i++) {
                                            dataTable.addRow([new Date(dailycount[i][0], dailycount[i][1], dailycount[i][2]), dailycount[i][3]]);
                                        }
                                        dataTable.addRows([
                                            [new Date(2012, 3, 13), 5],
                                            [new Date(2012, 3, 14), 1],
                                            [new Date(2012, 3, 15), 9],
                                            [new Date(2012, 3, 16), 3],
                                            [new Date(2012, 3, 17), 4],
                                            [new Date(2012, 3, 20), 4],
                                            [new Date(2012, 3, 21), 4],
                                        ]);
                                        var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

                                        var options = {
                                            title: "Daily Submission Heatmap",
                                            noDataPattern: {
                                                backgroundColor: '#f1f1f1',
                                                color: '#fefefe'
                                            },
                                            colorAxis: {
                                                minValue: 0,
                                                maxValue: 5,
                                                colors: ['#e2fcd3', '#006b05']
                                            },
                                            width: 1000
                                        };

                                        chart.draw(dataTable, options);
                                    }
                                </script>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>