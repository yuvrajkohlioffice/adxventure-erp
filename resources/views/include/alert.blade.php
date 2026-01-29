@if (session('message'))
<div class="alert alert-success" id="success-message">
    {{ session('message') }}
</div>
@endif
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
{{-- @if(session('errors')) --}}
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if(session('custom_errors'))
<div class="alert alert-danger">
    <strong>Errors found in some rows:</strong>
    <ul>
        @foreach(session('custom_errors') as $error)
            <li>
                <strong>Row:</strong>
                {{ is_array($error['row']) ? implode(', ', $error['row']) : $error['row'] }}
                <ul>
                    @foreach($error['errors'] as $field => $messages)
                        <li>
                            <strong>{{ $field }}:</strong>
                            {{ is_array($messages) ? implode(', ', $messages) : $messages }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
@endif





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 5000); // 2000 milliseconds = 2 seconds
});
$(document).ready(function() {
    setTimeout(function() {
        $('#danger-message').fadeOut('slow');
    }, 5000); // 2000 milliseconds = 2 seconds
});
</script>