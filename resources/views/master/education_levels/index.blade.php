@extends('layouts/layoutMaster')

@section('title', 'Tingkat Sekolah - TKA Nawasena')

@section('page-style')
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="openCreateModal(`{{ url('api/management/education-levels') }}`)">
    <i class="fa-solid fa-plus"></i> Tambah Tingkat
</button>


<x-dynamic-modal 
    id="modalCreate"
    title="Tambah Tingkat Sekolah"
    action="{{ url('/api/education-levels') }}"
    table="educationLevelTable"
    :fields="[
        ['type' => 'text', 'label' => 'Nama Tingkat', 'name' => 'name', 'col' => 12],
    ]"
/>


<x-dynamic-datatable 
    title="Tingkat Sekolah"
    id="educationLevelTable"
    api="{{ url('/api/management/education-levels') }}"
    :columns="[
        ['data' => 'id', 'title' => 'ID'],
        ['data' => 'name', 'title' => 'Nama'],
        ['data' => 'subjectTypes', 'title' => 'Jenis Mata Pelajaran', 'render' => 'subjectTypesList'],
        ['data' => 'id', 'title' => 'Aksi', 'render' => 'actionButtons']
    ]"
    :searchable="[
        ['label' => 'Nama', 'column' => 1,'filter'=>'name'],
        ['label' => 'Jenis Mata Pelajaran', 'column' => 2,'filter'=>'subjectTypes.name'],
    ]"
/>

@stack('page-script')

@endsection


@section('page-script')
<script>
    
function subjectTypesList(data) {
    if (!data) return '-';
    return data.map(item => item.name).join(', ');
}

function actionButtons(id) {
    return `
        <button class="btn btn-warning btn-sm" onclick="openEditModal('/api/management/education-levels/${id}')">
            Edit
        </button>
        <button class="btn btn-danger btn-sm" onclick="deleteItem('/api/management/education-levels/${id}')">
            Hapus
        </button>
    `;
}

function deleteItem(url) {
    Swal.fire({
        title: "Hapus data?",
        text: "Data akan dihapus permanen",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Ya, hapus"
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await fetch(url, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "Authorization": "Bearer "
                }
            });
            const data = await response.json();

            if (data.status=='success') {
                Swal.fire("Berhasil", "Data berhasil dihapus", "success");
                $('#educationLevelTable').DataTable().ajax.reload();
            } else {
                Swal.fire("Error", "Gagal menghapus data", "error");
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initDynamicDatatable("educationLevelTable",
        "{{ url('/api/management/education-levels') }}",
        [
            { data: 'id' },
            { data: 'name' },
            { data: 'subjectTypes.name', render: subjectTypesList },
            { 
                data: 'subject_types',
                visible: false,
                searchable: true,
                render: function(data) {
                    if (!data) return "";
                    return data.map(item => item.name).join(" ");
                }
            },
            { data: 'id', orderable: false, render: actionButtons },
        ]
    );
});

</script>
@endsection

