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
        <tbody>
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