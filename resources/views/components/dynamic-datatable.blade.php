@props([
    'title' => 'Advanced Search',
    'id' => 'dynamicTable',
    'api' => '',
    'columns' => [],
    'searchable' => [],
])

<div class="card m-2">
    <h5 class="card-header">{{$title}}</h5>

    <div class="card-body">
        <form class="dt_adv_search" id="{{ $id }}_search_form">
            <div class="row g-3">
                @foreach ($searchable as $s)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label">{{ $s['label'] }}:</label>
                        <input type="text"
                               class="form-control dt-input"
                               placeholder="{{ $s['placeholder'] ?? $s['label'] }}"
                               data-column="{{ $s['column'] }}"
                               data-filter="{{ $s['filter'] }}"/>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <div class="card-datatable table-responsive">
        <table id="{{ $id }}" class="table table-bordered dt-advanced-search">
            <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column['title'] }}</th>
                @endforeach
            </tr>
            </thead>
        </table>
    </div>
</div>




@push('page-script')
<script>
    function initDynamicDatatable(id, api, columns) {

        let dt = $('#' + id).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: api,
                type: "GET",
                data: function (d) {
                    return {
                        page: (d.start / d.length) + 1,
                        per_page: d.length,
                        sort_by: d.columns[d.order[0].column].data,
                        sort_dir: d.order[0].dir,
                        keyword: d.search.value,  
                        filters: $('#' + id + '_search_form .dt-input').map(function () {
                            return {
                                column: $(this).data('filter'),
                                value: $(this).val()
                            };
                        }).get()
                    };
                },
                dataSrc: function (json) {
                    json.recordsTotal = json.payload.total;
                    json.recordsFiltered = json.payload.total;
                    return json.payload.data;
                }
            },
            columns: columns
        });

        // Reload saat pengguna mengetik
        $('#' + id + '_search_form .dt-input').on('keyup change', function () {
            dt.ajax.reload();
        });

        return dt;
    }

</script>
@endpush
