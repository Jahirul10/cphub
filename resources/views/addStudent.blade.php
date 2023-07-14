<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student requests</title>
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
                        <a class="nav-link active" href=""><b>Add Student</b></a>
                    </li>
                    <a class="nav-link" href="{{ url('logout') }}">Log Out</a>
            </div>
        </div>

    </nav>
    <!-- Content of search-page -->
    <div class="vh-100 d-flex justify-content-center mt-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <h2 class="p-2">Student request</h2>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Session</th>
                                        <th scope="col">Codeforces Handle</th>
                                        <th scope="col">Vjudge Handle</th>
                                        <th scope="col">Spoj Handle</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requestedStudents as $student)
                                    <tr id="row-{{ $student->id }}">
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->phone }}</td>
                                        <td>{{ $student->session }}</td>
                                        <td>{{ $student->cfhandle }}</td>
                                        <td>{{ $student->vjhandle }}</td>
                                        <td>{{ $student->spojhandle }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-success me-2" onclick="acceptRequest(this)" data-student-id="{{ $student->id }}">Accept</button>
                                                <button class="btn btn-danger" onclick="rejectRequest(this)" data-student-id="{{ $student->id }}">Reject</button>

                                            </div>
                                        </td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        function acceptRequest(button) {
            var studentId = button.getAttribute('data-student-id');
            // Handle accept request logic here
            console.log('Accept request with ID:', studentId);

            // Send an AJAX request to accept the request and add details to the tables
            fetch(`/accept-request/${studentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        studentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // If the acceptance was successful, remove the row from the view
                    console.log('Response:', data);
                    if (data.success) {
                        const row = document.getElementById(`row-${studentId}`);
                        row.remove();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function rejectRequest(button) {
            var studentId = button.getAttribute('data-student-id');
            // Handle reject request logic here
            console.log('Reject request with ID:', studentId);

            // Send an AJAX request to delete the row from the database
            fetch(`/delete-request/${studentId}`, { // <-- Update the URL to use studentId instead of requestId
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // If the deletion was successful, remove the row from the view
                    if (data.success) {
                        const row = document.getElementById(`row-${studentId}`);
                        row.remove();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>