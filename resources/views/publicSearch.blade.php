<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Public Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/publicSearch-style.css">
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
                        <a class="nav-link" href="{{ url('/signup') }}">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">Log In</a>
                    </li>
            </div>
        </div>

    </nav>
    <!-- Content of search-page -->
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
                            <h2 class="fw-bold mb-2 text-uppercase text-center">University Of Rajshahi</h2>
                            <!-- <div class="row mt-5">
                                <div class="col-md mb-4">
                                    <label for="text" class="form-label">Codeforces Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Codeforces" aria-label="Codeforces">
                                </div>
                                <div class="col-md mb-4">
                                    <label for="text" class="form-label">Vjudge Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Vjudge" aria-label="Vjudge">
                                </div>
                                <div class="col-md">
                                    <label for="text" class="form-label">Spoj Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" placeholder="Spoj" aria-label="Spoj">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <button type="button" class="btn btn-primary btn-md w-50">Submit</button>

                            </div> -->
                            <div class="row mt-5">
                                <div class="col-md mb-4">
                                    <label for="codeforces" class="form-label">Codeforces Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="codeforces" placeholder="Codeforces" aria-label="Codeforces">
                                </div>
                                <div class="col-md mb-4">
                                    <label for="vjudge" class="form-label">Vjudge Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="vjudge" placeholder="Vjudge" aria-label="Vjudge">
                                </div>
                                <div class="col-md">
                                    <label for="spoj" class="form-label">Spoj Handle</label>
                                    <input type="text" class="form-control border-dark opacity-50 mt-sm-2" id="spoj" placeholder="Spoj" aria-label="Spoj">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <button type="button" class="btn btn-primary btn-md w-50" id="submitBtn">Submit</button>
                            </div>

                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                            $(document).ready(function() {
                                $('#submitBtn').click(function() {
                                    var codeforcesHandle = $('#codeforces').val();
                                    var vjudgeHandle = $('#vjudge').val();
                                    var spojHandle = $('#spoj').val();
                                    
                                    // Create the data object to be sent in the POST request
                                    var data = {
                                        codeforces: codeforcesHandle,
                                        vjudge: vjudgeHandle,
                                        spoj: spojHandle
                                    };
                                    
                                    // Send the POST request to the desired endpoint
                                    $.post('/searchdata', data, function(response) {
                                        // Handle the response from the server
                                        console.log(response);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>