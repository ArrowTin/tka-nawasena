@extends('layouts/layoutMaster')

@section('title', 'Matapelajaran - TKA Nawasena')

@section('page-style')
    
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table { width: 100% !important; }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="resetModalCreate(); openCreateModal(apiUrl.store)">
    <i class="fa-solid fa-plus"></i> Tambah Matapelajaran
</button>

{{-- ================================
    DYNAMIC MODAL CREATE/EDIT
================================ --}}
<x-dynamic-modal 
    id="modalCreate"
    title="Tambah Matapelajaran"
    :action="url('/api/management/subjects')"
    table="subjectTable"
    :fields="[
        ['type' => 'select', 'label' => 'Kategori', 'name' => 'category_id', 'col' => 12,'options' => $categories],
        ['type' => 'text', 'label' => 'Kode', 'name' => 'code', 'col' => 6],
        ['type' => 'text', 'label' => 'Nama Mapel', 'name' => 'name', 'col' => 6],
        ['type' => 'textarea', 'label' => 'Deskripsi', 'name' => 'description', 'col' => 12],
    ]"
    :relations="['categories']"
/>

{{-- ================================
    DATATABLE
================================ --}}
<x-dynamic-datatable 
    title="Matapelajaran"
    id="subjectTable"
    :api="url('/api/management/subjects')"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'code', 'title' => 'Kode'],
        ['data' => 'name', 'title' => 'Nama Mapel'],
        ['data' => 'category.category_name', 'title' => 'Kategori'],
        ['data' => 'id', 'title' => 'Aksi', 'render' => 'actionButtons']
    ]"
    :searchable="[
        ['label' => 'Nama', 'column' => 3, 'filter'=>'name'],
        ['label' => 'Kategori', 'column' => 1, 'filter'=>'category.name'],
    ]"
/>

@endsection


@push('script')


{{-- Baru helper --}}
<script src="{{asset('js/crud-helper.js')}}"></script>



<script>
const apiUrl = CRUD.api("{{ url('/api/management/subjects') }}");

document.addEventListener("DOMContentLoaded", () => {

    const columns = [
        { data: 'id' },
        { data: 'code' },
        { data: 'name' },
        { data: 'category.category_name' },
        {
            data: 'id',
            orderable: false,
            render: function(data, type, row) {
                row.table = "subjectTable";
                return CRUD.actionButtons(data, type, row, apiUrl);
            }
        }
    ];

    initDynamicDatatable("subjectTable", apiUrl.index, columns);
});
</script>
@endpush
