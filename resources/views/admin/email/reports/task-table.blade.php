{{-- File: resources/views/admin/email/reports/task-table.blade.php --}}
<p>Dear Team,</p>
<p>
    Please find below today's task report from 
    <strong>{{ $userName }} ({{ $userRole }})</strong>:
</p>

@if(count($tasks) > 0)
    <table border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="text-align:left; width: 10%;">#</th>
                <th style="text-align:left;">Task Name</th>
                <th style="text-align:left; width: 20%;">Time (Minutes)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $index => $task)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $task['name'] }}</td>
                    <td>{{ $task['time'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p><em>No specific tasks recorded for this report.</em></p>
@endif

<br>
<p>
    Best Regards,<br>
    {{ $userName }}<br>
    {{ $userRole }}
</p>