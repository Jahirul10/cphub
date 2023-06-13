<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
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
                        <a class="nav-link" href="#">Contest</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Help</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Log Out</a></li>
                        </ul>
                    </li>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4 mb-4">
        <div class="row mb-4">
            <div class="col-9">
                <div class="card">
                    <div class="row">
                        <div class="col-2">
                            <img class="rounded-circle shadow ms-4 mt-4 mb-4" alt="avatar2" src="asset/jahirul.png" />
                        </div>
                        <div class="col ps-5">
                            <ul class=" mt-5">
                                <ul class="">
                                    <h4>{{$student->name}}</h4>
                                    <h4>{{$student->id}}</h4>
                                    <h4>{{$student->phone}}</h4>
                                    <h4>{{$student->session}}</h4>
                                </ul>
                                <ul class="">
                                </ul>
                                <ul class="">
                                </ul>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="row mt-4 mb-2">
                            <div class="col-2">
                                <input class="form-check-input" id="all-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input">
                                <label for="all-checkbox" class="form-label">All</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input" id="codeforces-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input">
                                <label for="codeforces-checkbox" class="form-label">Codeforces</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input" id="vjudge-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input">
                                <label for="vjudge-checkbox" class="form-label">Vjudge</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input" id="spoj-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input">
                                <label for="spoj-checkbox" class="form-label">Spoj</label>
                            </div>
                        </div>
                    </div>

                    <script>
                        function handleCheckboxChange() {
                            const allCheckbox = document.querySelector('#all-checkbox');
                            const codeforcesCheckbox = document.querySelector('#codeforces-checkbox');
                            const vjudgeCheckbox = document.querySelector('#vjudge-checkbox');
                            const spojCheckbox = document.querySelector('#spoj-checkbox');

                            var selectedJudgesCount = allCheckbox.checked + codeforcesCheckbox.checked + vjudgeCheckbox.checked + spojCheckbox.checked;
                            console.log(selectedJudgesCount);

                            if (selectedJudgesCount > 0 && selectedJudgesCount < 4) {

                            } else if (!allCheckbox.checked) {
                                codeforcesCheckbox.checked = false;
                                vjudgeCheckbox.checked = false;
                                spojCheckbox.checked = false;
                                selectedJudgesCount = 0;
                            } else if (allCheckbox.checked) {
                                codeforcesCheckbox.checked = true;
                                vjudgeCheckbox.checked = true;
                                spojCheckbox.checked = true;
                            }
                        }

                        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach((checkbox) => {
                            checkbox.addEventListener('change', handleCheckboxChange);
                        });
                    </script>

                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="row justify-content-evenly mt-3 mb-3">
                                <div class="" style="display:inline-block; width:30%;">
                                    <canvas id="pie-chart-1"></canvas>
                                </div>
                                <div class="" style="display:inline-block; width:30%;">
                                    <canvas id="pie-chart-2"></canvas>
                                </div>

                                <script>
                                    // Pie Chart 1 Data
                                    var data1 = {
                                        labels: ["Data 1", "Data 2", "Data 3"],
                                        datasets: [{
                                            data: [30, 20, 50],
                                            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                                            hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
                                        }]
                                    };

                                    // Pie Chart 2 Data
                                    var data2 = {
                                        labels: ["Data 4", "Data 5", "Data 6"],
                                        datasets: [{
                                            data: [60, 40, 20],
                                            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                                            hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
                                        }]
                                    };

                                    // Get Pie Chart 1 Context
                                    var ctx1 = document.getElementById("pie-chart-1").getContext("2d");

                                    // Get Pie Chart 2 Context
                                    var ctx2 = document.getElementById("pie-chart-2").getContext("2d");

                                    // Create Pie Chart 1
                                    var pieChart1 = new Chart(ctx1, {
                                        type: 'pie',
                                        data: data1,
                                        options: {
                                            tooltips: {
                                                callbacks: {
                                                    label: function(tooltipItem, data) {
                                                        var label = data.labels[tooltipItem.index] || '';
                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        label += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                                        return label;
                                                    }
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Submission Chart'
                                                }
                                            }
                                        }
                                    });

                                    // Create Pie Chart 2
                                    var pieChart2 = new Chart(ctx2, {
                                        type: 'pie',
                                        data: data2,
                                        options: {
                                            tooltips: {
                                                callbacks: {
                                                    label: function(tooltipItem, data) {
                                                        var label = data.labels[tooltipItem.index] || '';
                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        label += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                                        return label;
                                                    }
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Language Chart'
                                                }
                                            }
                                        }
                                    });
                                </script>


                            </div>
                            <hr>
                            <div class="row mt-3 ms-2">
                                <div id="calendar_basic" style="width: auto; height: auto;"></div>

                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                                        dataTable.addRows([
                                            [new Date(2012, 3, 13), 5],
                                            [new Date(2012, 3, 14), 1],
                                            [new Date(2012, 3, 15), 9],
                                            [new Date(2012, 3, 16), 3],
                                            [new Date(2012, 3, 17), 4]
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
                                        @foreach ($submissions as $submission)
                                        <tr>
                                            <td>{{ $submission->id }}</td>
                                            <td>{{ optional($submission->problem)->title }}</td>
                                            <td>{{ optional($submission->problem)->oj }}</td>
                                            <td>{{ $submission->verdict }}</td>
                                            <td>{{ $submission->language }}</td>
                                            <td>{{ $submission->submissiontime }}</td>
                                        </tr>
                                        <?php $problem = $submission->problem; // Call problem() function here to ensure it gets logged
                                        ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                    <h1>Computer</h1>
                </div>
            </div>
        </div>

        @foreach ($submissions as $submission)
        @dd($submission)
        @endforeach

    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>