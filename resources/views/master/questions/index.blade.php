@extends('layouts/layoutMaster')

@section('title', 'Bank Soal - TKA Nawasena')

@section('page-style')
    @vite(['resources/assets/vendor/fonts/fontawesome.scss'])
    <style>
        .card-datatable table { width: 100% !important; }
    </style>
@endsection

@section('content')

<button class="btn btn-primary mb-3"
    onclick="resetModalCreate(); openCreateModal(apiUrl.store)">
    <i class="fa-solid fa-plus"></i> Tambah Soal
</button>

{{-- ---------------- MODAL CREATE / EDIT SOAL ---------------- --}}

<x-dynamic-modal 
    id="modalQuestion"
    title="Tambah Soal"
    :action="url('/api/management/questions')"
    table="questionTable"
    :fields="[
        ['type'=>'select','label'=>'Mata Pelajaran', 'name'=>'subject_id','options'=>$subjects,'col'=>6,'required'=>true],
        ['type'=>'select','label'=>'Tipe Soal', 'name'=>'question_type_id','options'=>$types,'col'=>6, 'required'=>true],
        ['type'=>'textarea','label'=>'Teks Soal', 'name'=>'question_text','col'=>12],
        ['type'=>'file','label'=>'Gambar Soal', 'name'=>'question_image','col'=>12],
        ['type'=>'textarea','label'=>'Pembahasan', 'name'=>'explanation','col'=>12],
        ['type'=>'select','label'=>'Tingkat Kesulitan', 'name'=>'difficulty',
            'options'=>['easy'=>'Mudah','medium'=>'Sedang','hard'=>'Sulit'], 'col'=>6],
    ]"
    :relations="[
        'options' => [
            'fields' => [
                ['type' => 'text', 'name' => 'option_label', 'label' => 'Label (A/B/C/D)', 'col' => 3],
                ['type' => 'textarea', 'name' => 'option_text', 'label' => 'Teks Opsi', 'col' => 7],
                ['type' => 'checkbox', 'name' => 'is_correct', 'label' => 'Benar?', 'col' => 2],
            ]
        ]
    ]"
    
/>


{{-- ---------------- DATATABLE ---------------- --}}
<x-dynamic-datatable 
    title="Bank Soal"
    id="questionTable"
    :api="url('/api/management/questions')"
    :columns="[
        ['data'=>'id','title'=>'ID'],
        ['data'=>'subject.name','title'=>'Mata Pelajaran'],
        ['data'=>'type.name','title'=>'Tipe Soal'],
        ['data'=>'question_text','title'=>'Soal','render'=>'shortText'],
        ['data'=>'options','title'=>'Opsi Jawaban','render'=>'listOptions'],
        ['data'=>'correctAnswers','title'=>'Jawaban Benar','render'=>'listCorrect'],
        ['data'=>'id','title'=>'Aksi','render'=>'actionButtons']
    ]"

    :searchable="[
        ['label'=>'Mata Pelajaran','column'=>1,'filter'=>'subject.name'],
        ['label'=>'Tipe Soal','column'=>2,'filter'=>'type.name'],
        ['label'=>'Soal','column'=>3,'filter'=>'question_text']
    ]"
/>

@endsection

@push('script')
<script src="{{asset('js/crud-helper.js')}}"></script>

<script>
    const apiUrl = CRUD.api("{{ url('/api/management/questions') }}");

    // ----------- Custom Renderers --------------
    function shortText(data) {
        if (!data) return "-";
        return data.substring(0, 50) + "...";
    }

    function listOptions(data, type, row) {
        if (!data) return "-";
        return data
            .map(o => `<span class='badge bg-info mb-1'>${o.option_label}. ${o.option_text}</span>`)
            .join("<br>");
    }

    function listCorrect(options, type, row) {
        if (!options || !Array.isArray(options)) return "-";

        // Ambil semua opsi yang correct_answer tidak null
        const corrects = options.filter(opt => opt.correct_answer !== null);

        if (corrects.length === 0) return "-";

        // Tampilkan semua label opsi yang benar
        return corrects
            .map(opt => `<span class="badge bg-success me-1">${opt.option_label}</span>`)
            .join(" ");
    }




    document.addEventListener("DOMContentLoaded", () => {
        const columns = [
            { data: 'id' },
            { data: 'subject.name' },
            { data: 'type.name' },
            { data: 'question_text', render: shortText },
            { 
                data: 'options', 
                orderable: false,
                render: listOptions 
            },
            { 
                data: 'options', 
                orderable: false,
                render: listCorrect 
            },
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    row.table = "questionTable";
                    return CRUD.actionButtons(data, type, row, apiUrl);
                }
            }
        ];

        initDynamicDatatable("questionTable", apiUrl.index, columns);
    });

   
</script>
@endpush
