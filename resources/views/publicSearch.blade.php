<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Public Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
                </ul>
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
                                <form id="searchForm"> <!-- Add the form element here -->
                                    <div class="row">
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label for="codeforces" class="form-label">Codeforces Handle</label>
                                                <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="codeforces" placeholder="Codeforces" aria-label="Codeforces" value="">
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label for="vjudge" class="form-label">Vjudge Handle</label>
                                                <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="vjudge" placeholder="Vjudge" aria-label="Vjudge" value="">
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label for="spoj" class="form-label">Spoj Handle</label>
                                                <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="spoj" placeholder="Spoj" aria-label="Spoj" value="">
                                            </div>
                                        </div>
                                        <div class="col-md mt-4">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-md w-100 mt-sm-3" id="submitBtn">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form> <!-- Close the form element here -->
                            </div>

                            <div class="d-none">
                                <hr>
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
                            </div>

                            <script>
                                document.getElementById('submitBtn').addEventListener('click', function(event) {
                                    event.preventDefault(); // Prevent the default form submission

                                    // Get the input values
                                    var codeforcesHandle = document.getElementById('codeforces').value;
                                    var vjudgeHandle = document.getElementById('vjudge').value;
                                    var spojHandle = document.getElementById('spoj').value;

                                    // Create the AJAX request
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '/searchdata');
                                    xhr.setRequestHeader('Content-Type', 'application/json');
                                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                                    // Handle the AJAX response
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                            var response = JSON.parse(xhr.responseText);
                                            console.log(response);
                                            // Update the UI with the response data
                                            // For example, update the submission table with response.submissions
                                            // document.getElementById('submission_table').innerHTML = response.submissions;
                                        }
                                    };

                                    // Send the AJAX request with the input data
                                    xhr.send(JSON.stringify({
                                        codeforcesHandle: codeforcesHandle,
                                        vjudgeHandle: vjudgeHandle,
                                        spojHandle: spojHandle
                                    }));
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>