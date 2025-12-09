@extends('layouts/layoutMaster')

@section('title', 'Daftar Ujian - TKA Nawasena')

@section('page-style')
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table { width: 100% !important; }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="resetModalCreate(); openCreateModal(apiUrl.store)">
    <i class="fa-solid fa-plus"></i> Tambah Ujian
</button>

{{-- ---------------- MODAL CREATE / EDIT EXAM ---------------- --}}
<x-dynamic-modal 
    id="modalExam"
    title="Tambah Ujian"
    :action="url('/api/management/exams')"
    table="examTable"
    :fields="[
        ['type'=>'select','label'=>'Mata Pelajaran', 'name'=>'subject_id','options'=>$subjects,'col'=>12],
        ['type'=>'text','label'=>'Judul Ujian', 'name'=>'title','col'=>12],
        ['type'=>'textarea','label'=>'Deskripsi', 'name'=>'description','col'=>12],
        ['type'=>'number','label'=>'Durasi (Menit)', 'name'=>'duration_minutes','col'=>12],
        ['type'=>'datetime_local','label'=>'Waktu Mulai', 'name'=>'start_time','col'=>6],
        ['type'=>'datetime_local','label'=>'Waktu Selesai', 'name'=>'end_time','col'=>6],
    ]"
    :relations="[
        'questions' => [
            'fields' => [
                ['type' => 'select', 'name' => 'question_ids[]', 'label' => 'Pilih Soal', 'options' => $questions->mapWithKeys(fn($q)=>[$q->id=>$q->question_text]), 'col'=>12, 'multiple'=>true]
            ]
        ]
    ]"
/>

{{-- ---------------- DATATABLE ---------------- --}}
<x-dynamic-datatable 
    title="Daftar Ujian"
    id="examTable"
    :api="url('/api/management/exams')"
    :columns="[
        ['data'=>'id','title'=>'ID'],
        ['data'=>'subject.name','title'=>'Mata Pelajaran'],
        ['data'=>'title','title'=>'Judul Ujian'],
        ['data'=>'description','title'=>'Deskripsi'],
        ['data'=>'duration_minutes','title'=>'Durasi (Menit)'],
        ['data'=>'start_time','title'=>'Waktu Mulai'],
        ['data'=>'end_time','title'=>'Waktu Selesai'],
        ['data'=>'questions','title'=>'Jumlah Soal','render'=>'countQuestions'],
        ['data'=>'id','title'=>'Aksi','render'=>'actionButtons']
    ]"
    :searchable="[
        ['label'=>'Mata Pelajaran','column'=>1,'filter'=>'subject.name'],
        ['label'=>'Judul Ujian','column'=>2,'filter'=>'title'],
        ['label'=>'Deskripsi','column'=>3,'filter'=>'description']
    ]"
/>

@endsection

@push('script')
<script src="{{asset('js/crud-helper.js')}}"></script>

<script>
    const apiUrl = CRUD.api("{{ url('/api/management/exams') }}");

    // ----------- Custom Renderers --------------
   
    function countQuestions(name, data, type, row, api, title) {
        
        // Buat HTML badge untuk data
        let html = data ? data.length : 0;
    
        const urlFetch = `${api.index}/${row.id}/${name}`;
        const urlPost  = `${api.index}/${row.id}/add-${name}`;
        
        // Escape JSON supaya aman dimasukkan ke HTML
        const selectedSafe = encodeURIComponent(JSON.stringify(data ?? []));
    
        // Kembalikan HTML dengan tombol tambah
        return `
        <div class="text-center">
           <span > ${html}</span></br>
            <span class="badge rounded-pill bg-primary bg-glow cursor-pointer"
                onclick="openAddItem(
                    '${name}',
                    '${urlFetch}',
                    '${urlPost}',
                    JSON.parse(decodeURIComponent('${selectedSafe}')),
                    '${title} : ${row.title}'
                )">
                <i class="fa-solid fa-plus"></i> Tambah
            </span>
             <span class="badge rounded-pill bg-info bg-glow cursor-pointer mt-1"
                onclick="openViewItem(
                    '${name}',
                    '${urlFetch}',
                    '${urlPost}',
                    JSON.parse(decodeURIComponent('${selectedSafe}')),
                    'Lihat : ${row.title}'
                )">
                <i class="fa-solid fa-eye"></i> Lihat
            </span>
        </div>
        

        `;
    }

    document.addEventListener("DOMContentLoaded", () => {
        const columns = [
            { data: 'id' },
            { data: 'subject.name' },
            { data: 'title' },
            { data: 'description' },
            { data: 'duration_minutes' },
            { data: 'start_time' },
            { data: 'end_time' },
            { 
                data: 'questions', 
                orderable: false, 
                render: function (data, type, row) {
                    return countQuestions(
                        'questions',
                        data,
                        type,
                        row,
                        apiUrl,
                        'Tambah Soal'
                    );
                }
            },
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    row.table = "examTable";
                    return CRUD.actionButtons(data, type, row, apiUrl);
                }
            }
        ];

        initDynamicDatatable("examTable", apiUrl.index, columns);
    });
</script>
@endpush
