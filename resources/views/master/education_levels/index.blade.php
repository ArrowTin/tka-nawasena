@extends('layouts/layoutMaster')

@section('title', 'Tingkat Sekolah - TKA Nawasena')

@section('page-style')
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table { width: 100% !important; }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="resetModalCreate(); openCreateModal(apiUrl.store)">
    <i class="fa-solid fa-plus"></i> Tambah Tingkat
</button>

<x-dynamic-modal 
    id="modalCreate"
    title="Tambah Tingkat Sekolah"
    :action="url('/api/management/education-levels')"
    table="educationLevelTable"
    :fields="[
        ['type' => 'text', 'label' => 'Nama Tingkat', 'name' => 'name', 'col' => 12],
    ]"
    :relations="['subjectTypes'=>[]]"
/>

<x-dynamic-datatable 
    title="Tingkat Sekolah"
    id="educationLevelTable"
    :api="url('/api/management/education-levels')"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'subjectTypes', 'title' => 'Jenis Mata Pelajaran', 'render' => 'listNames'],
        ['data' => 'subjectTypes', 'title' => 'Filter JP (hidden)'],
        ['data' => 'id', 'title' => 'Aksi', 'render' => 'actionButtons']
    ]"
    :searchable="[
        ['label' => 'Nama', 'column' => 1, 'filter'=>'name'],
        ['label' => 'Jenis Mata Pelajaran', 'column' => 2, 'filter'=>'subjectTypes.name'],
    ]"
/>

@endsection

@push('script')

<script src="{{asset('js/crud-helper.js')}}"></script>



<script>
    const apiUrl = CRUD.api("{{ url('/api/management/education-levels') }}");
    
    document.addEventListener("DOMContentLoaded", () => {

        const columns = [
            { data: 'id' },
            { data: 'name' },
            {
                data: 'subject_types',
                orderable: false, 
                render: function (data, type, row) {
                    return CRUD.listNames(
                        'subjectTypes',
                        data,
                        type,
                        row,
                        apiUrl,
                        'Tambah Jenis Mata Pelajaran'
                    );
                }
            },

            // searchable hidden column
            {
                data: 'subjectTypes',
                visible: false,
                searchable: true,
                render: (data) => data ? data.map(s => s.name).join(' ') : ""
            },

            // universal action buttons
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    row.table = "educationLevelTable"; // penting untuk reload
                    return CRUD.actionButtons(data, type, row, apiUrl);
                }
            }
        ];

        initDynamicDatatable("educationLevelTable", apiUrl.index, columns);
    });
</script>
@endpush



