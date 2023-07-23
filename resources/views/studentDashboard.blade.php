<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
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
                            <img class="rounded-circle shadow ms-4 mt-4 mb-4" src="{{ asset('images/') }}/pp1.png" alt="avatar2" />
                        </div>
                        <div class="col ps-5">
                            <ul class=" mt-5 ms-5">
                                <h4>{{ $student->name }}</h4>
                                <p><em>{{ $student->id }}</em></p>
                                <p>Phone: {{ $student->phone }}</p>
                                <p>Session: {{ $student->session }}</p>
                            </ul>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col">
                        <div class="row mt-4 mb-3 align-items-center">
                            <!-- Checkbox inputs -->
                            <div class="col-2">
                                <input class="form-check-input checkbox-filter" id="codeforces" type="checkbox" value="" aria-label="Checkbox for following text input" checked>
                                <label for="codeforces" class="form-label">Codeforces</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input checkbox-filter" id="vjudge" type="checkbox" value="" aria-label="Checkbox for following text input" checked>
                                <label for="vjudge" class="form-label">Vjudge</label>
                            </div>
                            <div class="col-2">
                                <input class="form-check-input checkbox-filter" id="spoj" type="checkbox" value="" aria-label="Checkbox for following text input" checked>
                                <label for="spoj" class="form-label">Spoj</label>
                            </div>

                            <!-- Filter button -->
                            <div class="col-2">
                                <button class="btn btn-success" id="filter_button">Filter</button>
                            </div>
                        </div>
                        <div class="card">

                            <!-- pie-chart -->
                            <div class="row justify-content-evenly mt-3 mb-3">
                                <div class="" style="display:inline-block; width:30%;">
                                    <canvas id="pie-chart-1"></canvas>
                                </div>
                                <div class="" style="display:inline-block; width:30%;">
                                    <canvas id="pie-chart-2"></canvas>
                                </div>
                            </div>

                            <hr>

                            <!-- submission-history-table -->
                            <div class="mt-3">
                                <h2 class="p-2">Submission Records</h2>
                                <table class="table table-striped">
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
                                    <tbody id="submission_table">
                                    </tbody>
                                </table>
                                <!-- Pagination -->
                                <nav>
                                    <ul class="pagination justify-content-center" id="pagination"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    var pieChart1;
                    var pieChart2;

                    $(document).ready(function() {
                        // Enable/disable filter button based on checkbox status
                        $('input.checkbox-filter').on('change', function() {
                            var checked = $('input.checkbox-filter:checked').length;
                            $('#filter_button').prop('disabled', checked === 0);
                        });

                        // Trigger 'change' event on checkboxes to update filter button initially
                        $('input.checkbox-filter').trigger('change');

                        $('#filter_button').click(function() {
                            var platforms = [];
                            $('input.checkbox-filter:checked').each(function() {
                                platforms.push($(this).attr('id'));
                            });

                            var url = window.location.href;
                            var segments = url.split('/');
                            var studentId = segments[segments.length - 1];

                            $.ajax({
                                url: '/filter-submissions',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    platforms: platforms,
                                    studentId: studentId
                                },
                                success: function(response) {
                                    console.log(response);

                                    var submissions = response.submissions;
                                    var verdictCounts = response.verdictsCount;
                                    var languageCounts = response.languagesCount;

                                    // Destroy existing chart instances
                                    if (pieChart1) {
                                        pieChart1.destroy();
                                    }

                                    if (pieChart2) {
                                        pieChart2.destroy();
                                    }


                                    // Pie Chart 1 Data
                                    var labels = [];
                                    var data = [];
                                    // Convert response.verdictsCount object into an array of objects
                                    for (var key in response.verdictsCount) {
                                        if (response.verdictsCount.hasOwnProperty(key)) {
                                            // Push directly to labels and data arrays
                                            labels.push(key);
                                            data.push(response.verdictsCount[key]);
                                        }
                                    }

                                    var data1 = {
                                        labels: labels,
                                        datasets: [{
                                            data: data,
                                            backgroundColor: ["#4caf50", "#9966ff", "#FFCE56", "#4bc0c0", '#FF6384', "#ff9f40", "#c9cbcf"],
                                            hoverBackgroundColor: ["#4caf50", "#36A2EB", "#FFCE56", "#4bc0c0", '#FF6384', "#ff9f40", "#c9cbcf"]
                                        }]
                                    };

                                    // Pie Chart 2 Data
                                    var labelslanguages = [];
                                    var datalanguages = [];
                                    for (var key in response.languagesCount) {
                                        if (response.languagesCount.hasOwnProperty(key)) {
                                            // Push directly to labelslanguages and datalanguages arrays
                                            labelslanguages.push(key);
                                            datalanguages.push(response.languagesCount[key]);
                                        }
                                    }

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
                                    pieChart1 = new Chart(ctx1, {
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
                                    pieChart2 = new Chart(ctx2, {
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



                                    var tableBody = $('#submission_table');
                                    tableBody.empty();

                                    var currentPage = 1;
                                    var rowsPerPage = 50;
                                    var totalPages = Math.ceil(submissions.length / rowsPerPage);

                                    function showRows() {
                                        var start = (currentPage - 1) * rowsPerPage;
                                        var end = start + rowsPerPage;

                                        tableBody.empty();

                                        for (var i = start; i < end; i++) {
                                            if (i >= submissions.length) {
                                                break;
                                            }

                                            var submission = submissions[i];
                                            var row = $('<tr>');

                                            $('<td>').text(submission.submission_id).appendTo(row);
                                            $('<td>').text(submission.problem_title).appendTo(row);
                                            $('<td>').text(submission.problem_oj).appendTo(row);
                                            $('<td>').text(submission.verdict).appendTo(row);
                                            $('<td>').text(submission.language).appendTo(row);
                                            $('<td>').text(submission.submissiontime).appendTo(row);

                                            tableBody.append(row);
                                        }
                                    }

                                    function updatePagination() {
                                        var pagination = $('#pagination');
                                        pagination.empty();

                                        var firstButton = $('<li class="page-item"><a class="page-link" href="#">1</a></li>');
                                        var prevButton = $('<li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>');
                                        var nextButton = $('<li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>');
                                        var lastButton = $('<li class="page-item"><a class="page-link" href="#">' + totalPages + '</a></li>');

                                        firstButton.click(function(e) {
                                            e.preventDefault(); // Prevent the default behavior of the anchor tag
                                            if (currentPage !== 1) {
                                                currentPage = 1;
                                                showRows();
                                                updatePagination();
                                            }
                                        });

                                        prevButton.click(function(e) {
                                            e.preventDefault(); // Prevent the default behavior of the anchor tag
                                            if (currentPage > 1) {
                                                currentPage--;
                                                showRows();
                                                updatePagination();
                                            }
                                        });

                                        nextButton.click(function(e) {
                                            e.preventDefault(); // Prevent the default behavior of the anchor tag
                                            if (currentPage < totalPages) {
                                                currentPage++;
                                                showRows();
                                                updatePagination();
                                            }
                                        });

                                        lastButton.click(function(e) {
                                            e.preventDefault(); // Prevent the default behavior of the anchor tag
                                            if (currentPage !== totalPages) {
                                                currentPage = totalPages;
                                                showRows();
                                                updatePagination();
                                            }
                                        });

                                        pagination.append(firstButton);
                                        pagination.append(prevButton);

                                        var maxVisiblePages = 5; // Set the maximum number of visible page buttons
                                        var startPage = Math.max(currentPage - Math.floor(maxVisiblePages / 2), 1);
                                        var endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);

                                        // Adjust startPage when it is not within the valid range
                                        if (endPage === totalPages && startPage > 1) {
                                            startPage = Math.max(endPage - maxVisiblePages + 1, 1);
                                        }

                                        if (startPage > 1) {
                                            pagination.append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                                        }

                                        for (var i = startPage; i <= endPage; i++) {
                                            var pageButton = $('<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>');
                                            pageButton.click(function(e) {
                                                e.preventDefault(); // Prevent the default behavior of the anchor tag
                                                currentPage = parseInt($(this).text());
                                                showRows();
                                                updatePagination();
                                            });

                                            // Add 'active' class to the current page button
                                            if (i === currentPage) {
                                                pageButton.addClass('active');
                                            }

                                            pagination.append(pageButton);
                                        }

                                        if (endPage < totalPages) {
                                            pagination.append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                                        }

                                        pagination.append(nextButton);
                                        pagination.append(lastButton);
                                    }

                                    showRows();
                                    updatePagination();
                                },
                                error: function(xhr, status, error) {
                                    console.log(error);
                                }
                            });
                        });

                        // Trigger click event on filter button to simulate initial AJAX request
                        $('#filter_button').trigger('click');
                    });
                </script>
            </div>
            <div class="col-3">


                <div class="card">
                    <div class="member-card pb-2">
                        <div class="float-right">
                            <div class="row mt-3 ms-2 me-2">
                                <h5>Top solvers</h5>
                                <table class="table table-sm mt-2">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Solved</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topSolving as $topSolve)
                                        <tr>
                                            <td>{{ $topSolve->id }}</td>
                                            <td>{{ $topSolve->name }}</td>
                                            <td>{{$topSolve->solved}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <hr>

        <!-- heat map -->
        <div class="row mt-5 ms-2">
            <div class="col-12">
                <div class="chart-container" style="width: 100%;">
                    <div id="calendar_basic" style="height: auto;"></div>
                </div>
            </div>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <div id="submissionsCountData" data-submissionsCount="{{ json_encode($submissionsCount) }}"></div>
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
                    var submissionsCount = JSON.parse(document.getElementById('submissionsCountData').getAttribute('data-submissionsCount'));
                    console.log(submissionsCount);
                    for (var i = 0; i < submissionsCount.length; i++) {
                        dataTable.addRow([new Date(submissionsCount[i][0], submissionsCount[i][1], submissionsCount[i][2]), submissionsCount[i][3]]);
                    }
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