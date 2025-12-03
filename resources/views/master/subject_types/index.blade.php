@extends('layouts/layoutMaster')

@section('title', 'Jenis Mata Pelajaran - TKA Nawasena')

@section('page-style')
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table { width: 100% !important; }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="openCreateModal(apiUrl.store)">
    <i class="fa-solid fa-plus"></i> Tambah Jenis Mata Pelajaran
</button>

<x-dynamic-modal 
    id="modalCreate"
    title="Tambah Jenis Mata Pelajaran"
    :action="url('/api/management/subject-types')"
    table="subjectTypeTable"
    :fields="[
        ['type' => 'text', 'label' => 'Nama Tingkat', 'name' => 'name', 'col' => 12],
    ]"
/>

<x-dynamic-datatable 
    title="Jenis Mata Pelajaran"
    id="subjectTypeTable"
    :api="url('/api/management/subject-types')"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'educationLevels', 'title' => 'Tingkat Sekolah', 'render' => 'listNames'],
        ['data' => 'id', 'title' => 'Aksi', 'render' => 'actionButtons']
    ]"
    :searchable="[
        ['label' => 'Nama', 'column' => 1, 'filter'=>'name'],
        ['label' => 'Tingkat Sekolah', 'column' => 2, 'filter'=>'educationLevels.name'],
    ]"
/>

@endsection

@section('page-script')

<script src="{{asset('js/crud-helper.js')}}"></script>

@stack('page-script')


<script>
    const apiUrl = CRUD.api("{{ url('/api/management/subject-types') }}");

    document.addEventListener("DOMContentLoaded", () => {

        const columns = [
            { data: 'id' },
            { data: 'name' },
            { data: 'educationLevels.name', render: CRUD.listNames },

            // searchable hidden column
            {
                data: 'educationLevels',
                visible: false,
                searchable: true,
                render: (data) => data ? data.map(s => s.name).join(' ') : ""
            },

            // universal action buttons
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    row.table = "subjectTypeTable"; // penting untuk reload
                    return CRUD.actionButtons(data, type, row, apiUrl);
                }
            }
        ];

        initDynamicDatatable("subjectTypeTable", apiUrl.index, columns);
    });
</script>
@endsection



