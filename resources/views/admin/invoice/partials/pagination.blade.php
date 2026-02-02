<div class="row pagination-links">
<div class="col-6"></div>
    <div class="col-6 text-end">
        {{ $data->appends(request()->query())->links() }}
    </div>
</div>