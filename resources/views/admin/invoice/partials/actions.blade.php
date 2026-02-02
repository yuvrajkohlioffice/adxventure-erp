<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="javascript:void(0)" onclick="Followup({{ $row->id }}, '{{ $row->client->name ?? $row->lead->name }}')">
                Follow Up
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="javascript:void(0)" onclick="Whatsapp({{ $row->id }})">
                WhatsApp
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="javascript:void(0)" onclick="MarkAsPaid({{ $row->id }}, {{ $row->balance }}, '{{ $row->client->name ?? $row->lead->name }}')">
                Mark Paid
            </a>
        </li>
        @if($row->status == 2)
        <li><a class="dropdown-item" href="{{ route('bill', $row->id) }}">View Bill</a></li>
        @else
        <li><a class="dropdown-item" href="{{ $row->pdf }}" target="_blank">View PDF</a></li>
        @endif
        
        @if(!$row->is_project)
        <li><a class="dropdown-item" href="{{ route('projects.create', ['invoiceId' => $row->id]) }}">Create Project</a></li>
        @endif
    </ul>
</div>