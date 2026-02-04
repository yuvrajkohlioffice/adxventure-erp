<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <h2>{{ $subject }}</h2>
    <p>Task List :</p>
    
    {{-- EXACT TABLE STYLING AS ORIGINAL: border="1" --}}
    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Task Name</th>
                <th>Assign Date</th>
                <th>Priority</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $index => $task)
                <tr>
                    {{-- Counter starts at 1 --}}
                    <td>{{ $index + 1 }}</td>
                    
                    <td>{{ $task->name }}</td>
                    
                    <td>{{ date("d M, Y", strtotime($task->created_at)) }}</td>
                    
                    {{-- PRIORITY LOGIC (Replicating your status() function) --}}
                    <td>
                        @switch($task->category)
                            @case(1) NORMAL @break
                            @case(2) MEDIUM @break
                            @case(3) HIGH @break
                            @case(4) URGENT @break
                            @default UNKNOWN
                        @endswitch
                    </td>

                    {{-- STATUS LOGIC --}}
                    <td>
                        @if($task->status == 4)
                            Done
                        @else
                            Pending
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>