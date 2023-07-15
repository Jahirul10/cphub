<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a class="nav-link" href="{{ url('add-student') }}">Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('logout') }}">Log Out</a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Add Student</a></li>
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
        <div class="row">
            <div class="col-9">
                <div class="card">
                    <div class="input-group input-group-lg m-auto p-3 pb-1">
                        <input class="form-control" type="search" placeholder="Search" aria-label="search">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- <img src="..." class="card-img-top" alt="..."> -->
                    <div class="card-body">
                        <h4 class="card-title mb-2">Sessions</h4>
                        <button type="button" class="btn btn-outline-primary me-3 active" data-session="2020-21">2020-21</button>
                        <button type="button" class="btn btn-outline-primary me-3" data-session="2019-20">2019-20</button>
                        <button type="button" class="btn btn-outline-primary me-3" data-session="2018-19">2018-19</button>
                        <button type="button" class="btn btn-outline-primary me-3" data-session="2017-18">2017-18</button>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                // Add a CSRF token to the request headers
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                $('.btn').click(function() {
                                    $('.btn').removeClass('active');
                                    $(this).addClass('active');

                                    var session = $(this).data('session');

                                    // console.log(session);
                                    $.post('/teacher-table-update/' + session, function(response) {
                                        var students = response.students;
                                        var platformCounts = response.platformCounts;
                                        // console.log(students);
                                        // console.log(platformCounts);

                                        var tableBody = $('#updatedSessionData');
                                        tableBody.empty(); // Clear existing table body

                                        // Iterate over the students and update the table rows
                                        students.forEach(function(student) {
                                            var row = $('<tr></tr>');
                                            row.on('click', function() {
                                                showStudentDetails(student.id, student.name);
                                            });

                                            row.append('<th scope="row">' + student.id + '</th>');
                                            row.append('<td>' + student.name + '</td>');

                                            // Check if platformCounts for the student exists
                                            if (platformCounts.hasOwnProperty(student.id)) {
                                                var counts = platformCounts[student.id];

                                                // Iterate over platformCounts and add <td> for each platform
                                                Object.values(counts).forEach(function(count) {
                                                    row.append('<td>' + count + '</td>');
                                                });
                                            } else {
                                                // Add <td> with 0 if platformCounts doesn't exist for the student
                                                row.append('<td>0</td>');
                                            }

                                            tableBody.append(row);
                                        });
                                    });
                                });
                            });
                        </script>



                        <hr>
                        <div id="table-container">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Codefores</th>
                                        <th scope="col">Vjudge</th>
                                        <th scope="col">Spoj</th>
                                    </tr>
                                </thead>
                                <tbody id="updatedSessionData">
                                    @foreach ($students as $student)
                                    <tr onclick="showStudentDetails('{{ $student->id }}', '{{ $student->name }}')">
                                        <th scope="row">{{ $student->id }}</th>
                                        <td>{{ $student->name }}</td>
                                        @foreach ($platformCounts[$student->id] ?? [] as $platform => $count)
                                        <td>{{ $count }}</td>
                                        @endforeach
                                        @if (!isset($platformCounts[$student->id]))
                                        <td>0</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var pieChart1, pieChart2; // Declare variables to hold chart instances

                // Function to initialize the pie charts
                function initializePieCharts() {
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
                                }
                            }
                        }
                    });
                }

                function showStudentDetails(studentId, studentName) {
                    // Find the student details section
                    var studentDetails = document.getElementById('student-details');

                    // Update the image path with the studentId variable
                    var imagePath = "{{ asset('images/') }}" + "/pp" + studentId + ".png";

                    var studentDetailsContainer = document.getElementById('student-details-container');




                    // Update the student ID in the section
                    studentDetails.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="thumb-lg member-thumb m-3 pt-2">
                                <img src="${imagePath}" class="rounded-circle img-thumbnail" alt="profile-image" width="80">
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="text-start">
                                <a href="/student/${studentId}"  style="color: black;">
                                    <h5>${studentName}</h5>
                                </a>
                                <p>${studentId}</p>
                            </div>
                        </div>
                    </div>
                    <ul class="social-links list-inline">
                        <li class="list-inline-item"><a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                </svg>
                                </a>
                                </li>
                                <li class="list-inline-item"><a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                    <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                </svg></a></li>
                        <li class="list-inline-item"><a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="Skype"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                    </svg></a>
                                    </li>
                                    </ul>


                                    <div class="row mt-3 ms-1 me-1">
                                        <div class="p-0" style="display:inline-block; width:50%;">
                                            <canvas id="pie-chart-1"></canvas>
                                        </div>
                                        <div class="p-0" style="display:inline-block; width:50%;">
                                            <canvas id="pie-chart-2"></canvas>
                                        </div>
                                    </div>

                                    <div class="text-start row mt-4 ms-2 me-2">
                                    <h5>Last 7 days</h5>
                        <table class="table table-sm mt-2">
                        <thead class="table-dark">
                            <tr>
                            <th scope="col">Problem</th>
                                <th scope="col">OJ</th>
                                <th scope="col">Verdict</th>
                            </tr>
                            </thead>
                            <tbody id="last-week-submissions-table">
                            <!-- Submissions will be added dynamically here -->
                            </tbody>
                        </table>
                        </div>

                        `;

                    // Show the student details container
                    studentDetailsContainer.style.display = 'block';

                    // Send a POST request to the server
                    fetch(`/student/${studentId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Handle the response data
                            console.log(data);

                            // Call the initializePieCharts function
                            initializePieCharts();

                            // Update Pie Chart 1 with new data
                            pieChart1.data.labels = Object.keys(data.verdictsCount);
                            pieChart1.data.datasets[0].data = Object.values(data.verdictsCount);
                            pieChart1.update();


                            // Update Pie Chart 2 with new data
                            pieChart2.data.labels = Object.keys(data.languagesCount);
                            pieChart2.data.datasets[0].data = Object.values(data.languagesCount);
                            pieChart2.update();

                            // Update the last 7 days' submissions table
                            var lastWeekSubmissionsTable = document.getElementById('last-week-submissions-table');
                            lastWeekSubmissionsTable.innerHTML = '';

                            data.lastWeekSubmissions.forEach(submission => {
                                var row = document.createElement('tr');
                                row.innerHTML = `
                                    <td><a href="${submission.problem_url}" target="_blank">${submission.problem_title}</a></td>
                                    <td>${submission.problem_oj}</td>
                                    <td>
                                        <span class="submission-info" data-submission-id="${submission.submission_id}" data-submissiontime="${submission.submissiontime}">
                                            ${submission.verdict}
                                        </span>
                                    </td>
                                `;
                                lastWeekSubmissionsTable.appendChild(row);
                            });

                            // Add hover functionality to verdicts in the table
                            var submissionInfoElements = document.getElementsByClassName('submission-info');
                            Array.from(submissionInfoElements).forEach(element => {
                                var originalContent = element.innerHTML;
                                var popupBox = document.createElement('div');
                                popupBox.classList.add('popup-box');

                                element.addEventListener('mouseover', function() {
                                    var submissionId = element.getAttribute('data-submission-id');
                                    var submissionTime = element.getAttribute('data-submissiontime');

                                    popupBox.innerHTML = `
            <div class="popup-content">
                <span class="submission-id">ID: ${submissionId}</span><br>
                <span class="submission-time">Time: ${submissionTime}</span>
            </div>
        `;
                                    element.appendChild(popupBox);
                                    popupBox.style.display = 'block';
                                });

                                element.addEventListener('mouseout', function() {
                                    popupBox.style.display = 'none';
                                    element.innerHTML = originalContent;
                                });
                            });

                        })
                        .catch(error => {
                            // Handle the error
                            console.error(error);
                        });
                }
            </script>

            <div class="col-3">
                <div id="student-details-container" class="text-center card mb-4" style="display: none;">
                    <div class="member-card pb-2">
                        <div class="float-right">
                            <button id="close-btn" class="btn btn-outline border-0 float-end">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                                </svg>
                            </button>

                            <script>
                                var closeButton = document.getElementById('close-btn');
                                var studentDetailsContainer = document.getElementById('student-details-container');

                                closeButton.addEventListener('click', function() {
                                    studentDetailsContainer.style.display = 'none';
                                });
                            </script>

                            <div id="student-details" class="">
                            </div>

                        </div>
                    </div>
                </div>

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
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>