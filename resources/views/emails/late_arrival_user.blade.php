<div style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <p>Dear <strong>{{ $name }}</strong>,</p>
    <p style="font-size: 0.9em; color: #777; margin-top: -10px;">{{ $role }}</p>

    @if($count >= 3)
        <div style="border: 1px solid #ebccd1; background-color: #f2dede; color: #a94442; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <strong>Policy Notice:</strong> We noticed that you have been late <strong>{{ $count_ordinal }}</strong> times this month. 
            As per company policy, being late three or more times may result in a <strong>half-day salary deduction</strong>.
        </div>
        <p>Please make every effort to arrive on time. Punctuality reflects professionalism and ensures a positive workflow for the entire team.</p>
    @else
        <p>This is a gentle reminder that you arrived late today, <strong>{{ $date }}</strong>.</p>
        <div style="background: #fcf8e3; padding: 15px; border-left: 4px solid #8a6d3b; margin: 15px 0;">
            <strong>Scheduled:</strong> {{ $scheduled_time }}<br>
            <strong>Actual Arrival:</strong> {{ $arrival_time }}<br>
            <strong>Delay:</strong> {{ $delay_duration }}
        </div>
        <p>Consistent punctuality supports better discipline and teamwork. We appreciate your cooperation.</p>
    @endif

    <p>We value your contributions and believe in your commitment to professional improvement.</p>
    <p>Keep giving your best!<br><strong>Team AdxVenture</strong></p>
</div>