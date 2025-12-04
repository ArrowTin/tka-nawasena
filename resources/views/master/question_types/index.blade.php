@extends('layouts/layoutMaster')

@section('title', 'Jenis Soal - TKA Nawasena')

@section('page-style')
    
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table { width: 100% !important; }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="resetModalCreate(); openCreateModal(apiUrl.store)">
    <i class="fa-solid fa-plus"></i> Tambah Jenis Soal
</button>

{{-- ================================
    DYNAMIC MODAL CREATE/EDIT
================================ --}}
<x-dynamic-modal 
    id="modalCreate"
    title="Tambah Jenis Soal"
    :action="url('/api/management/question-types')"
    table="questionTypeTable"
    :fields="[
        ['type' => 'text', 'label' => 'Nama Jenis Soal', 'name' => 'name', 'col' => 12],
        ['type' => 'textarea', 'label' => 'Deskripsi', 'name' => 'description', 'col' => 12],
    ]"
/>

{{-- ================================
    DATATABLE
================================ --}}
<x-dynamic-datatable 
    title="Jenis Soal"
    id="questionTypeTable"
    :api="url('/api/management/question-types')"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Nama Jenis Soal'],
        ['data' => 'description', 'title' => 'Deskripsi'],
        ['data' => 'id', 'title' => 'Aksi', 'render' => 'actionButtons']
    ]"
    :searchable="[
        ['label' => 'Nama', 'column' => 3, 'filter'=>'name'],
    ]"
/>

@endsection


@push('script')


{{-- Baru helper --}}
<script src="{{asset('js/crud-helper.js')}}"></script>



<script>
const apiUrl = CRUD.api("{{ url('/api/management/question-types') }}");

document.addEventListener("DOMContentLoaded", () => {

    const columns = [
        { data: 'id' },
        { data: 'name' },
        { data: 'description' },
        {
            data: 'id',
            orderable: false,
            render: function(data, type, row) {
                row.table = "questionTypeTable";
                return CRUD.actionButtons(data, type, row, apiUrl);
            }
        }
    ];

    initDynamicDatatable("questionTypeTable", apiUrl.index, columns);
});
</script>
@endpush
