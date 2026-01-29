<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}/title>
</head>
<body>

<h2>{{ $subject }}</h2>

<p>Task List : </p>
@if(false)
<table border="1">
    <tr>
        <th>S.No</th>
        <th>Task Name</th>
        <th>Assign Date</th>
        <th>Priority</th>
        <th>Assign Date</th>
        <th>Status</th>
    </tr>
        @if(count($data) > 0){
            @foreach($data as $dd){
                <tr>
                    <td>{{ $dd->name }}</td>
                    <td>{{ date("d M,Y",strtotime($dd->created_at)) }}</td>
                    <td>{{ $dd->category }}</td>
                    <td>{{ $dd->deadline }}</td>
                    <td>{{ $dd->status }}</td>
                </tr>
            @endforeach
        @endif
</table>
@enddif
</body>
</html>
