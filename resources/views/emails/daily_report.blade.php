<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    {{-- Header --}}
    <h3>Today Report</h3>

    {{-- Body Message --}}
    <p>Dear Team,</p>
    <p>Please find below today's task report from <strong>{{ $user->name }} ({{ $user->roles->first()->name ?? 'Role' }})</strong>:</p>

    {{-- TASK TABLE --}}
    @if(!empty($data['task_name']) && count($data['task_name']) > 0)
        {{-- EXACT STYLING FROM ORIGINAL CODE --}}
        <table border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="text-align:left;">#</th>
                    <th style="text-align:left;">Task Name</th>
                    <th style="text-align:left;">Time (Minutes)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['task_name'] as $index => $taskName)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $taskName }}</td>
                        <td>{{ $data['task_timing'][$index] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Footer --}}
    <br>
    <p>
        Best Regards,<br>
        {{ $user->name }}<br>
        {{ $user->roles->first()->name ?? '' }}
    </p>
</body>
</html>