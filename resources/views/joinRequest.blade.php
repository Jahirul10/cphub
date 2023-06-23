<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <!--Navbar Design start -->
    <nav class="navbar navbar-expand-lg bg-light">
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
                        <a class="nav-link active" href="#"><b>Join</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('logout') }}">Log Out</a>
                    </li>
            </div>
        </div>

    </nav>
    <!-- Content of join request page -->
    <div class="vh-100 d-flex justify-content-center mt-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-10">
                    <!-- Success message -->
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="card bg-white">
                        <div class="card-body p-5">
                            <h2 class="fw-bold mb-2 text-uppercase text-start">Join Request</h2>
                            <div class="row mt-5">
                                <div class="col-md mb-4">
                                    <label for="studentId" class="form-label">Student ID</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Student ID" id="studentId">
                                    <!-- Error message for student ID -->
                                    <span id="studentIdError" class="text-danger"></span>
                                </div>
                                <div class="col-md mb-4">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Phone" id="phone">
                                </div>
                                <div class="col-md mb-4">
                                    <label for="session" class="form-label">Session</label>
                                    <select class="form-select border-dark opacity-50 mt-sm-2" id="session">
                                        <option selected disabled>Select Session</option>
                                        <option value="2017-18">2017-18</option>
                                        <option value="2018-19">2018-19</option>
                                        <option value="2019-20">2019-20</option>
                                        <option value="2020-21">2020-21</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md mb-4">
                                    <label for="codeforcesHandle" class="form-label">Codeforces Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Codeforces Handle" id="codeforcesHandle">
                                </div>
                                <div class="col-md mb-4">
                                    <label for="vjudgeHandle" class="form-label">Vjudge Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Vjudge Handle" id="vjudgeHandle">
                                </div>
                                <div class="col-md mb-4">
                                    <label for="spojHandle" class="form-label">Spoj Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Spoj Handle" id="spojHandle">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Requesting to...</label>
                                    <input type="email" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Email Address" id="email">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <button type="button" class="btn btn-primary btn-md w-50" onclick="joiningRequestForm()">Join Request</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function joiningRequestForm() {
            // Get the form values
            var studentId = document.getElementById('studentId').value;
            var phone = document.getElementById('phone').value;
            var session = document.getElementById('session').value;
            var codeforcesHandle = document.getElementById('codeforcesHandle').value;
            var vjudgeHandle = document.getElementById('vjudgeHandle').value;
            var spojHandle = document.getElementById('spojHandle').value;
            var email = document.getElementById('email').value;

            // Get the CSRF token value
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Create a FormData object and append the form values
            var formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('studentId', studentId);
            formData.append('phone', phone);
            formData.append('session', session);
            formData.append('codeforcesHandle', codeforcesHandle);
            formData.append('vjudgeHandle', vjudgeHandle);
            formData.append('spojHandle', spojHandle);
            formData.append('email', email);

            // Send a POST request to the joinRequest route with the form data
            fetch('/join-request', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Handle the response
                    if (data.hasOwnProperty('success') && data.success === true) {
                        console.log("here");
                        // Join request submitted successfully
                        window.location.href = '/successful-request';
                    } else if (data.hasOwnProperty('error')) {
                        if (data.error === 'student_exists') {
                            document.getElementById('studentIdError').innerText = 'Student with this ID has already joined.';
                        } else if (data.error === 'join_request_exists') {
                            document.getElementById('studentIdError').innerText = 'Joining request has already been sent with this ID.';
                        } else {
                            // Handle other error cases if needed
                        }
                    }
                })
                .catch(error => {
                    // Handle any errors
                    console.error(error);
                });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>