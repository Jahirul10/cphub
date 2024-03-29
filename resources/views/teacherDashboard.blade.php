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
                        <a class="nav-link" aria-current="page" href="{{ url('comparison-form') }}">Compare</a>
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
                                        // console.log(response);

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
                                                // console.log(counts);

                                                // Iterate over platformCounts and add <td> for each platform
                                                Object.values(counts).forEach(function(count) {
                                                    row.append('<td>' + count + '</td>');
                                                });
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
                                },
                                title: {
                                    display: true,
                                    text: 'Verdicts Chart'
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
                                    text: 'Languages Chart'
                                }
                            }
                        }
                    });
                }

                function showStudentDetails(studentId, studentName) {
                    // Find the student details section
                    var studentDetails = document.getElementById('student-details');

                    // Update the image path with the studentId variable
                    var imagePath = "{{ asset('images/') }}" + "/pp1.png";

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
                        <li class="list-inline-item"><a title="" data-placement="top" data-toggle="tooltip" class="btn btn-primary btn-sm" href="" data-original-title="Facebook">
                                Codeforces
                                </a>
                                </li>
                                <li class="list-inline-item"><a title="" data-placement="top" data-toggle="tooltip" class="btn btn-primary btn-sm" href="" data-original-title="Twitter">Vjudge</a></li>
                        <li class="list-inline-item"><a title="" data-placement="top" data-toggle="tooltip" class="btn btn-primary btn-sm" href="" data-original-title="Skype">Spoj</a>
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
                                    <h5>Last 10 submissions</h5>
                        <table class="table table-sm mt-2">
                        <thead class="table-dark">
                            <tr>
                            <th scope="col">Problem</th>
                                <th scope="col">OJ</th>
                                <th scope="col">Verdict</th>
                            </tr>
                            </thead>
                            <tbody id="last-10-submissions-table">
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

                            // Update the link for the Facebook icon
                            var facebookIconLink = document.querySelector('a[data-original-title="Facebook"]');
                            var codeforcesHandle = data.codeforcesHandle;
                            facebookIconLink.href = `https://codeforces.com/profile/${codeforcesHandle}`;

                            // Update the link for the Twitter icon
                            var twitterIconLink = document.querySelector('a[data-original-title="Twitter"]');
                            var twitterHandle = data.vjudgeHandle; // Replace 'twitterHandle' with the actual key from the response data
                            twitterIconLink.href = `https://vjudge.net/user/${twitterHandle}`;

                            // Update the link for the GitHub icon
                            var githubIconLink = document.querySelector('a[data-original-title="Skype"]');
                            var githubHandle = data.spojHandle; // Replace 'githubHandle' with the actual key from the response data
                            githubIconLink.href = `https://www.spoj.com/users/${githubHandle}`;
                            console.log(twitterHandle, githubHandle);

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
                            var last10SubmissionsTable = document.getElementById('last-10-submissions-table');
                            last10SubmissionsTable.innerHTML = '';

                            data.last10Submissions.forEach(submission => {
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
                                last10SubmissionsTable.appendChild(row);
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