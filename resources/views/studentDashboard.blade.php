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
                    <!-- Upcoming feature
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contest</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{url ('successful-request')}}">Join</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('logout') }}">Log Out</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Join Request</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Log Out</a></li>
                        </ul>
                    </li> -->
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4 mb-4">
        <div class="row mb-4">
            <div class="col-9">
                <div class="card">
                    <div class="row">
                        <div class="col-2">
                            <img class="rounded-circle shadow ms-4 mt-4 mb-4" alt="avatar2" src="42-.png" />
                        </div>
                        <div class="col ps-5">
                            <ul class=" mt-5">
                                <ul class="">
                                    <h4>{{ $student->name }}</h4>
                                    <h4>{{ $student->id }}</h4>
                                    <h4>{{ $student->phone }}</h4>
                                    <h4>{{ $student->session }}</h4>
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
                                <!-- <input class="form-check-input" id="all-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input"> -->
                                <input class="form-check-input checkbox-filter" id="all-checkbox" type="checkbox" value="" aria-label="Checkbox for following text input">
                                <label for="all-checkbox" class="form-label">All</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input checkbox-filter" id="codeforces-checkbox" type="checkbox" value="" aria-label="Checkbox for following text input">
                                <!-- <input class="form-check-input" id="codeforces-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input"> -->
                                <label for="codeforces-checkbox" class="form-label">Codeforces</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input checkbox-filter" id="vjudge-checkbox" type="checkbox" value="" aria-label="Checkbox for following text input">
                                <!-- <input class="form-check-input" id="vjudge-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input"> -->
                                <label for="vjudge-checkbox" class="form-label">Vjudge</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input checkbox-filter" id="spoj-checkbox" type="checkbox" value="" aria-label="Checkbox for following text input">
                                <!-- <input class="form-check-input" id="spoj-checkbox" checked type="checkbox" value="" aria-label="Checkbox for following text input"> -->
                                <label for="spoj-checkbox" class="form-label">Spoj</label>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-success" id="filter_button">Filter</button>
                            </div>
                        </div>
                    </div>

                    <!-- <script>
                        function handleCheckboxChange() {
                            const allCheckbox = document.querySelector('#all-checkbox');
                            const codeforcesCheckbox = document.querySelector('#codeforces-checkbox');
                            const vjudgeCheckbox = document.querySelector('#vjudge-checkbox');
                            const spojCheckbox = document.querySelector('#spoj-checkbox');

                            var selectedJudgesCount = allCheckbox.checked + codeforcesCheckbox.checked + vjudgeCheckbox.checked +
                                spojCheckbox.checked;
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
                            } else if (codeforcesCheckbox.checked && vjudgeCheckbox.checked && spojCheckbox.checked) {
                                allCheckbox.checked = true;
                            }
                        }

                        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                        checkboxes.forEach((checkbox) => {
                            checkbox.addEventListener('change', handleCheckboxChange);
                        });
                    </script> -->
                    <script>
                        $(document).ready(function() {
                            // Function to update the checkbox states
                            function updateCheckboxStates() {
                                var codeforcesChecked = $('#codeforces-checkbox').prop('checked');
                                var vjudgeChecked = $('#vjudge-checkbox').prop('checked');
                                var spojChecked = $('#spoj-checkbox').prop('checked');

                                if (codeforcesChecked && vjudgeChecked && spojChecked) {
                                    $('#all-checkbox').prop('checked', true);
                                } else {
                                    $('#all-checkbox').prop('checked', false);
                                }
                            }

                            // Event handler for 'All' checkbox
                            $('#all-checkbox').change(function() {
                                var allChecked = $(this).prop('checked');
                                $('#codeforces-checkbox, #vjudge-checkbox, #spoj-checkbox').prop('checked', allChecked);
                            });

                            // Event handlers for other checkboxes
                            $('#codeforces-checkbox, #vjudge-checkbox, #spoj-checkbox').change(function() {
                                updateCheckboxStates();
                            });
                        });
                    </script>
                    <script>
                        $(document).ready(function() {
                            var platform;
                            // Handle checkbox interactions
                            $('.checkbox-filter').change(function() {
                                // console.log('checked')
                                filterSubmissions();
                            });
                            const allChecked_button = document.getElementById('all-checkbox');
                            const cf_checked_button = document.getElementById('codeforces-checkbox');
                            const vj_checked_button = document.getElementById('vjudge-checkbox');
                            const spoj_checked_button = document.getElementById('spoj-checkbox');

                            // check for query params and set checkbox values
                            const checkboxValuesChange = () => {
                                const secondPartOfUrl = window.location.href.split('?')[1];
                                params = secondPartOfUrl && secondPartOfUrl.split('&');
                                // console.log(params);
                                var platform = params && params.filter(function(item) {
                                    if (item.includes('platform')) return true;
                                })
                                platform = platform && platform[0]


                                if (platform) {

                                    platform = platform.split('=')[1];
                                    // console.log(platform);
                                    if (platform.includes('codeforces')) {
                                        cf_checked_button.checked = true;
                                    }
                                    if (platform.includes('vjudge')) {
                                        vj_checked_button.checked = true;
                                    }
                                    if (platform.includes('spoj')) {
                                        spoj_checked_button.checked = true;
                                    }
                                    if (platform.includes('all')) {
                                        allChecked_button.checked = true;
                                    }
                                    if (platform.includes('codeforces') && platform.includes('vjudge') && platform.includes(
                                            'spoj')) {
                                        allChecked_button.checked = true;
                                    }
                                }
                            }

                            checkboxValuesChange();




                            // Filter submissions based on checkbox states
                            function filterSubmissions() {
                                var allChecked = $('#all-checkbox').is(':checked');
                                var codeforcesChecked = $('#codeforces-checkbox').is(':checked');
                                var vjudgeChecked = $('#vjudge-checkbox').is(':checked');
                                var spojChecked = $('#spoj-checkbox').is(':checked');

                                // Show/hide submissions based on checkbox states
                                // $('.submission-row').each(function() {
                                // var submissionOJ = $(this).data('oj');

                                // if (allChecked || (codeforcesChecked && submissionOJ === 'codeforces') || (vjudgeChecked && submissionOJ === 'Vjudge') || (spojChecked && submissionOJ === 'spoj')) {
                                //     $(this).show();
                                // } else {
                                //     $(this).hide();
                                // }
                                // });

                                platform = generateQueryString();

                                // add the platforms as a query string of the current url and change the href attribute
                                // console.log(typeof url)
                                // console.log(url);


                                // console.log(url);
                                // if(allChecked){
                                // }
                                //     if(allChecked || codeforcesChecked || vjudgeChecked || spojChecked){
                                //         console.log(url);
                                //         // window.location.href = url;
                                // }


                                // console.log(platforms);
                                // console.log(codeforcesChecked,vjudgeChecked,spojChecked);
                            }

                            // Initial filtering on page load
                            filterSubmissions();

                            // filter button listerner

                            document.getElementById('filter_button').addEventListener('click', function() {
                                // console.log(platform);
                                var url = window.location.href;
                                // split url by ? and take the first
                                url = url.split('?')[0];
                                // console.log(url);
                                if (platform != '') {
                                    url = url + '?platform=' + platform
                                    window.location.href = url;
                                }
                            })
                        });

                        const generateQueryString = () => {
                            var allChecked = $('#all-checkbox').is(':checked');
                            var codeforcesChecked = $('#codeforces-checkbox').is(':checked');
                            var vjudgeChecked = $('#vjudge-checkbox').is(':checked');
                            var spojChecked = $('#spoj-checkbox').is(':checked');

                            var q = '';
                            if (allChecked) {
                                q = 'codeforces*vjudge*spoj'
                            } else {
                                if (codeforcesChecked) {
                                    if (q != '') {
                                        q = q + '*codeforces';
                                    } else {
                                        q = 'codeforces';
                                    }
                                }
                                if (vjudgeChecked) {
                                    if (q != '') {
                                        q = q + '*vjudge';
                                    } else {
                                        q = 'vjudge';
                                    }
                                }
                                if (spojChecked) {
                                    if (q != '') {
                                        q = q + '*spoj';
                                    } else {
                                        q = 'spoj';
                                    }
                                }
                            }
                            return q;
                        }
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

                                <div id="verdictCountsData" data-verdictCounts="{{ json_encode($verdictCounts) }}"></div>
                                <div id="languageCountsData" data-languageCounts="{{ json_encode($languageCounts) }}"></div>
                                <script>
                                    var verdictCounts = JSON.parse(document.getElementById('verdictCountsData').getAttribute('data-verdictCounts'));
                                    var languageCounts = JSON.parse(document.getElementById('languageCountsData').getAttribute('data-languageCounts'));
                                    // Pie Chart 1 Data
                                    // Extract labels and data from the verdictCounts variable
                                    var labels = [];
                                    var data = [];
                                    var labelslanguages = [];
                                    var datalanguages = [];
                                    verdictCounts.forEach(function(item) {
                                        labels.push(item.verdict);
                                        data.push(item.count);
                                    });
                                    languageCounts.forEach(function(item) {
                                        labelslanguages.push(item.language);
                                        datalanguages.push(item.count);
                                    });
                                    // console.log(languageCounts);
                                    // Create the chart data object
                                    var data1 = {
                                        labels: labels,
                                        datasets: [{
                                            data: data,
                                            backgroundColor: ["#4caf50", "#9966ff", "#FFCE56", "#4bc0c0", '#FF6384', "#ff9f40", "#c9cbcf"], // Customize the colors as needed
                                            hoverBackgroundColor: ["#4caf50", "#36A2EB", "#FFCE56", "#4bc0c0", '#FF6384', "#ff9f40", "#c9cbcf"] // Customize the hover colors as needed
                                        }]
                                    };

                                    // Pie Chart 2 Data
                                    var data2 = {
                                        labels: labelslanguages,
                                        datasets: [{
                                            data: datalanguages,
                                            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#FF6384", "#36A2EB", "#FFCE56", "#FF6384", "#36A2EB", "#FFCE56"],
                                            hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#FF6384", "#36A2EB", "#FFCE56", "#FF6384", "#36A2EB", "#FFCE56"]
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
                                        <tr class="submission-row" data-oj="{{ optional($submission->problem)->oj }}">
                                            <td>{{ $submission->submission_id }}</td>
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
                                <div class="pagination justify-content-center">
                                    {{ $submissions->links('pagination::bootstrap-5') }}
                                </div>
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
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-5 ms-2">
            <div class="col-12">
                <div class="chart-container" style="width: 100%;">
                    <div id="calendar_basic" style="height: auto;"></div>
                </div>
            </div>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <div id="dailyCountData" data-dailycount="{{ json_encode($dailycount) }}"></div>
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
                    for (var i = 0; i < dailycount.length; i++) {
                        dataTable.addRow([new Date(dailycount[i][0], dailycount[i][1], dailycount[i][2]), dailycount[i][3]]);
                    }
                    // dataTable.addRows([
                    //     [new Date(2012, 3, 13), 5],
                    //     [new Date(2012, 3, 14), 1],
                    //     [new Date(2012, 3, 15), 9],
                    //     [new Date(2012, 3, 16), 3],
                    //     [new Date(2012, 3, 17), 4],
                    //     [new Date(2012, 3, 20), 4],
                    //     [new Date(2012, 3, 21), 4],
                    // ]);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>