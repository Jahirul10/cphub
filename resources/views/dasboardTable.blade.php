<div id="table-container">
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Platform</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->name }}</td>
                @foreach ($platforms as $platform)
                <td>{{ $platform }}</td>
                <td>{{ $platformCounts[$student->id][$platform] }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>