<div class="row pagination-links">
    <div class="col-8"></div>
    <div class="col-4 text-end">
        {{ $data->appends(request()->query())->links() }}
    </div>
</div>