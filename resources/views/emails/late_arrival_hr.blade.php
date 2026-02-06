<div style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <p>Dear HR Team,</p>
    <p>This is to inform you that <strong>{{ $name }} ({{ $role }})</strong> arrived late today.</p>
    
    <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #d9534f; margin: 15px 0;">
        <strong>Date:</strong> {{ $date }}<br>
        <strong>Scheduled Time:</strong> {{ $scheduled_time }}<br>
        <strong>Arrival Time:</strong> {{ $arrival_time }}<br>
        <strong>Delay Duration:</strong> <span style="color: #d9534f;">{{ $delay_duration }}</span><br>
        <strong>Reason:</strong> {{ $reason }}<br>
        <strong>Monthly Late Count:</strong> {{ $count }}
    </div>

    <p>Please take note of this for attendance records.</p>
    <p>Best Regards,<br>Automated Attendance System</p>
</div>