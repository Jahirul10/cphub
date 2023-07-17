<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bar Charts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('js/Chart.min.js') }}"></script>
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
                        <a class="nav-link" aria-current="page" href="{{ url('home') }}">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/signup') }}">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">Log In</a>
                    </li>
            </div>
        </div>

    </nav>

    <div class="vh-100 d-flex justify-content-center mt-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-10">
                    <div class="card bg-white">
                        <div class="card-body p-5">
                            <h3 class="fw-bold mb-2 text">OJ Solve</h3>
                            <div class="row justify-content-center mb-5">
                                <div class="col-md-10">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>

                            <hr class="mt-5">
                            <h3 class="fw-bold mb-2 text">Verdict Comparison</h3>
                            <div class="row justify-content-center mb-5">
                                <div class="col-md-10">
                                    <canvas id="verdictChart"></canvas>
                                </div>
                            </div>

                            <hr class="mt-5">
                            <h3 class="fw-bold mb-2 text">Submitted Languages</h3>
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <canvas id="barChart1"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var dataset1 = JSON.parse('{{ json_encode([$totalCodeforcesSolved_User_1, $totalVjudgeSolved_User_1, $totalSpojSolved_User_1]) }}');
        var dataset2 = JSON.parse('{{ json_encode([$totalCodeforcesSolved_User_2, $totalVjudgeSolved_User_2, $totalSpojSolved_User_2]) }}');

        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Codeforces', 'Vjudge', 'Spoj'],
                datasets: [{
                        label: '1st User',
                        data: dataset1,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',

                        borderWidth: 2,
                        borderSkipped: false,
                    },
                    {
                        label: '2nd User',
                        data: dataset2,

                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderWidth: 2,
                        borderRadius: 5,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>

    <script>
        var ctx = document.getElementById('verdictChart').getContext('2d');
        var verdict_User_1 = JSON.parse('{!! json_encode($verdict_User_1) !!}');
        var verdict_User_2 = JSON.parse('{!! json_encode($verdict_User_2) !!}');

        var labels = Object.keys(verdict_User_1);
        var data1 = Object.values(verdict_User_1);
        var data2 = Object.values(verdict_User_2);

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: '1st User',
                        data: data1,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    },
                    {
                        label: '2nd User',
                        data: data2,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        var ctx = document.getElementById('barChart1').getContext('2d');
        var language_User_1 = JSON.parse('{!! json_encode($language_User_1) !!}');
        var language_User_2 = JSON.parse('{!! json_encode($language_User_2) !!}');

        var labels = Object.keys(language_User_1);
        var data1 = Object.values(language_User_1);
        var data2 = Object.values(language_User_2);

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: '1st User',
                        data: data1,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    },
                    {
                        label: '2nd User',
                        data: data2,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>