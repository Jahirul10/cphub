<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Public Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

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
                        <a class="nav-link" aria-current="page" href="{{ url('comparison-form') }}">Compare</a>
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

                            <div id="submissionHistory" class="d-none">
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
                                <!-- Pagination -->
                                <nav>
                                    <ul class="pagination justify-content-center" id="pagination"></ul>
                                </nav>
                            </div>

                            <script>
                                document.getElementById('submitBtn').addEventListener('click', function(event) {
                                    event.preventDefault(); // Prevent the default form submission

                                    // Get the input values
                                    var codeforcesHandle = document.getElementById('codeforces').value;
                                    var vjudgeHandle = document.getElementById('vjudge').value;
                                    var spojHandle = document.getElementById('spoj').value;

                                    // Create the AJAX request
                                    $.ajax({
                                        url: '/searchdata',
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                            _token: $('meta[name="csrf-token"]').attr('content'), // Add this line to include the CSRF token
                                            codeforces: codeforcesHandle, // Modify the key to 'codeforces'
                                            vjudge: vjudgeHandle, // Modify the key to 'vjudge'
                                            spoj: spojHandle // Modify the key to 'spoj'
                                        },
                                        success: function(response) {
                                            console.log(response);

                                            // Unhide the table
                                            document.getElementById('submissionHistory').classList.remove('d-none');


                                            var tableBody = $('#submission_table');
                                            tableBody.empty();

                                            var currentPage = 1;
                                            var rowsPerPage = 50;
                                            var totalPages = Math.ceil(response.submissions.length / rowsPerPage);

                                            console.log("check-before-function");

                                            function showRows() {
                                                var start = (currentPage - 1) * rowsPerPage;
                                                var end = start + rowsPerPage;


                                                tableBody.empty();

                                                for (var i = start; i < end; i++) {
                                                    if (i >= response.submissions.length) {
                                                        break;
                                                    }

                                                    var submission = response.submissions[i];
                                                    var row = $('<tr>');


                                                    $('<td>').text(submission[0]).appendTo(row);
                                                    $('<td>').text(submission[1]).appendTo(row);
                                                    $('<td>').text(submission[2]).appendTo(row);
                                                    $('<td>').text(submission[3]).appendTo(row);
                                                    $('<td>').text(submission[4]).appendTo(row);
                                                    $('<td>').text(submission[5]).appendTo(row);

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
                                            console.log("check-last");


                                        },
                                        error: function(xhr, status, error) {
                                            console.log(error);
                                        }
                                    });
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