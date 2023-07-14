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
                        <a class="nav-link" href="#">Join</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Log Out</a>
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
                                    <h4>name</h4>
                                    <h4>student/h4>
                                    <h4>session</h4>
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

                            <div class="mt-3">
                                <h2 class="p-2">Submission Records</h2>
                                <div class="container">
                                    <h2>Search Results</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Problem</th>
                                                <th>Language</th>
                                                <th>Date</th>
                                                <th>Verdict</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mergedData as $item)
                                                <tr>
                                                    <td>{{ $item[0] }}</td>
                                                    <td>{{ $item[1] }}</td>
                                                    <td>{{ $item[2] }}</td>
                                                    <td>{{ $item[3] }}</td>
                                                    <td>{{ $item[4] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center">
                                        {{ $data->links() }}
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
    </div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>