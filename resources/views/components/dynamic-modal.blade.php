@props([
    'id' => 'modalCrud',
    'title' => 'Form',
    'fields' => [],
    'table' => 'table',
    'relations' => [],
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}_title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Form --}}
            <form id="{{ $id }}_form">

                <input type="hidden" id="{{ $id }}_method" value="POST">
                <input type="hidden" id="{{ $id }}_url" value="">
                <input type="hidden" id="{{ $id }}_disable_fields"
                    value='@json(collect($fields)->where("disable_on_additem", true)->pluck("name"))'>

                {{-- Body --}}
                <div class="modal-body ps-container" style="max-height:70vh; overflow-y:auto;">
                    <div class="row g-3">

                        {{-- NORMAL FIELDS --}}
                        @foreach ($fields as $field)
                            <div class="col-md-{{ $field['col'] ?? 12 }}" data-field="normal">
                                <label class="form-label">{{ $field['label'] }}</label>

                                @if ($field['type'] === 'text')
                                    <input type="text" class="form-control" name="{{ $field['name'] }}" @if(!empty($field['required'])) required @endif>
                                @endif

                                @if ($field['type'] === 'file')
                                    <input type="file" class="form-control" name="{{ $field['name'] }}" @if(!empty($field['required'])) required @endif>
                                @endif

                                @if ($field['type'] === 'datetime_local')
                                    <input type="datetime-local" class="form-control" name="{{ $field['name'] }}" @if(!empty($field['required'])) required @endif>
                                @endif

                                @if ($field['type'] === 'number')
                                    <input type="number" class="form-control" name="{{ $field['name'] }}" @if(!empty($field['required'])) required @endif>
                                @endif

                                @if ($field['type'] === 'select')
                                    <select class="form-select select2" name="{{ $field['name'] }}" @if(!empty($field['required'])) required @endif>
                                        <option value="">— Pilih —</option>
                                        @foreach (($field['options'] ?? []) as $val => $text)
                                            <option value="{{ $val }}">{{ $text }}</option>
                                        @endforeach
                                    </select>
                                @endif

                                @if ($field['type'] === 'textarea')
                                    <textarea class="form-control" rows="3" name="{{ $field['name'] }}" ></textarea>
                                @endif
                            </div>
                        @endforeach

                        {{-- RELATIONS --}}
                        @foreach ($relations as $name => $relation)
                            @if ($name === 'options')

                                {{-- OPTIONS WRAP --}}
                                <div class="col-12" id="wrap_options" data-field="relation" style="display:none">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h5>Opsi Jawaban</h5>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addOption()">Tambah
                                            Opsi</button>
                                    </div>
                                    <div id="optionList"></div>
                                </div>

                            @else
                                <div class="col-12"
                                    id="wrap_{{ \Illuminate\Support\Str::kebab($name) }}"
                                    data-field="relation"
                                    style="display:none"></div>
                            @endif
                        @endforeach

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer">
                    <div id="loadingSpinner" class="d-none">
                        <div class="spinner-border text-primary spinner-border-sm me-2"></div>
                        <span>Menyimpan...</span>
                    </div>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ========================================= --}}
{{--                JAVASCRIPT                 --}}
{{-- ========================================= --}}
@push('script')
<script>
document.addEventListener("DOMContentLoaded", () => {

    const modalId = "{{ $id }}";
    const modalEl = document.getElementById(modalId);
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    /* =====================================================
       SELECT2 INIT
    ===================================================== */
    function initSelect2() {
        if (!$.fn.select2) return console.error("Select2 belum terload!");

        $(`#${modalId} .select2`).select2({
            dropdownParent: $(`#${modalId}`),
            width: "100%"
        });
    }

    /* =====================================================
       STRING HELPERS (CamelCase & kebab-case)
    ===================================================== */
    function toCamel(str) {
        return str.replace(/([-_][a-z])/g, group =>
            group.toUpperCase().replace('-', '').replace('_', '')
        );
    }

    function toKebab(str) {
        return str.replace(/([a-z0-9])([A-Z])/g, '$1-$2').toLowerCase();
    }

    /* =====================================================
       RESET MODAL
    ===================================================== */
    window.resetModalCreate = function () {

        document.getElementById("btnSubmit").style.display = "block";

        document
            .querySelectorAll(`#${modalId}_form [data-field="normal"]`)
            .forEach(el => el.style.display = "block");

        document
            .querySelectorAll(`#${modalId}_form [data-field="relation"]`)
            .forEach(el => {
                el.style.display = "none";
                const list = el.querySelector("#optionList");
                if (list) list.innerHTML = "";
            });

        setTimeout(() => {
            $(`#${modalId} .select2`).val("").trigger("change");
        }, 50);
    };

    /* =====================================================
       OPEN CREATE MODAL
    ===================================================== */
    window.openCreateModal = function (url, title = "Tambah Data") {

        resetModalCreate();

        document.getElementById(`${modalId}_title`).innerText = title;
        document.getElementById(`${modalId}_url`).value = url;
        document.getElementById(`${modalId}_method`).value = "POST";

        document.getElementById(`${modalId}_form`).reset();

        const optWrap = document.getElementById("wrap_options");
        if (optWrap) optWrap.style.display = "block";

        const optionList = document.getElementById("optionList");
        if (optionList) {
            optionList.innerHTML = "";
            addOption();
            addOption();
        }

        modal.show();
        setTimeout(() => initSelect2(), 150);
    };

    /* =====================================================
       OPEN EDIT MODAL
    ===================================================== */
    window.openEditModal = async function (url, title = "Edit Data") {

        resetModalCreate();

        document.getElementById(`${modalId}_title`).innerText = title;
        document.getElementById(`${modalId}_url`).value = url;
        document.getElementById(`${modalId}_method`).value = "PUT";

        const res = await await apiFetch(url);
        const json = await res.json();
        const data = json.payload ?? {};

        Object.keys(data).forEach(key => {
            const input = document.querySelector(`#${modalId}_form [name="${key}"]`);
            if (!input) return;

            if (input.type === "file") {
                input.value = "";
                return;
            }

            input.value = data[key];

            if ($(input).hasClass("select2")) {
                $(input).trigger("change");
            }
        });

        if (data.options) {
            const wrap = document.getElementById("wrap_options");
            wrap.style.display = "block";

            const correctIds = data.options.filter(o => o.correct_answer !== null).map(o => o.id);

            fillOptions(data.options, correctIds);
        }

        modal.show();
        setTimeout(() => initSelect2(), 150);
    };

    /* =====================================================
       OPEN RELATION MODAL
    ===================================================== */
    window.openAddItem = async function (fieldName, fetchUrl, postUrl, selectedItems = [], title = "Pilih Relasi") {
        
        resetModalCreate();

        document.getElementById(`${modalId}_title`).innerText = title;
        document.getElementById(`${modalId}_url`).value = postUrl;
        document.getElementById(`${modalId}_method`).value = "POST";

        document
            .querySelectorAll(`#${modalId}_form [data-field="normal"]`)
            .forEach(el => el.style.display = "none");

        const wrapId = `wrap_${toKebab(fieldName)}`;
        const wrap = document.getElementById(wrapId);
        
        if (!wrap) return;

        wrap.style.display = "block";
        wrap.innerHTML = "Loading...";

        const response = await apiFetch(fetchUrl);
        const res = await response.json();
        const list = res.payload ?? [];

        const selected = selectedItems.map(i => Number(i.id));

        wrap.innerHTML = "";

        list.forEach(row => {
            wrap.innerHTML += `
                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="${fieldName}[]"
                        value="${row.id}"
                        ${selected.includes(row.id) ? "checked" : ""}>
                    <label class="form-check-label">
                        ${fieldName === 'questions' ? row.question_text : row.name}
                    </label>
                </div>
            `;
        });

        modal.show();
    };

    /* =====================================================
       OPEN VIEW RELATION
    ===================================================== */
    window.openViewItem = async function (fieldName, fetchUrl, postUrl = null, selectedItems = [], title = "Lihat Data") {

        resetModalCreate();

        document.getElementById(`${modalId}_title`).innerText = title;
        document.getElementById("btnSubmit").style.display = "none";

        document
            .querySelectorAll(`#${modalId}_form [data-field="normal"]`)
            .forEach(el => el.style.display = "none");

        const wrapId = `wrap_${toKebab(fieldName)}`;
        const wrap = document.getElementById(wrapId);
        if (!wrap) return;

        wrap.style.display = "block";
        wrap.innerHTML = "Loading...";

        const response = await apiFetch(fetchUrl);
        const res = await response.json();
        const list = res.payload ?? [];

        wrap.innerHTML = "";

        list.forEach(q => {
            const optionsHtml = (q.options ?? [])
                .map(o => {
                    const isCorrect = o.correct_answer !== null;
                    return `<li style="color:${isCorrect ? 'green' : 'inherit'}">
                        ${o.option_label}. ${o.option_text}
                    </li>`;
                })
                .join("");

            wrap.innerHTML += `
                <div class="card mb-2">
                    <div class="card-body">
                        <strong>Soal:</strong> ${q.question_text}<br/>
                        ${q.question_image ? `<img src="{{asset('storage')}}/${q.question_image}" class="img-fluid my-1"/>` : ""}
                        <ul class="mt-2">${optionsHtml}</ul>
                        ${q.explanation ? `<small><strong>Pembahasan:</strong> ${q.explanation}</small>` : ""}
                    </div>
                </div>
            `;
        });

        modal.show();
    };

    /* =====================================================
       ADD OPTION
    ===================================================== */
    window.addOption = function (id = null, label = "", text = "", correct = false) {
        const container = document.getElementById("optionList");
        if (!container) return;

        const index = container.children.length + 1;

        // Label A, B, C...
        const labelLetter = label || String.fromCharCode(64 + index);

        container.insertAdjacentHTML("beforeend", `
            <div class="border rounded p-2 mb-2 option-item" data-option="${id ?? 'new-'+index}">
                <div class="row g-2">

                    <input type="hidden" name="options[${index}][id]" value="${id ?? ''}">

                    <div class="col-md-2">
                        <label>Label</label>
                        <input type="text" class="form-control" 
                            name="options[${index}][label]" 
                            value="${labelLetter}">
                    </div>

                    <div class="col-md-7">
                        <label>Teks Opsi</label>
                        <input type="text" class="form-control" 
                            name="options[${index}][text]" 
                            value="${text}">
                    </div>

                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-check mt-3">

                            <!-- FIX: value harus LABEL -->
                            <input type="checkbox"
                                class="form-check-input correct-option"
                                name="correct_option_ids[]"
                                value="${labelLetter}"
                                ${correct ? "checked" : ""}>
                            <label class="form-check-label ms-1">Benar?</label>
                        </div>
                    </div>

                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-danger mt-3" onclick="removeOption(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                </div>
            </div>
        `);
    };



    /* =====================================================
       FILL OPTIONS (EDIT)
    ===================================================== */
    window.fillOptions = function (options = [], correct = []) {
        const wrap = document.getElementById("optionList");
        wrap.innerHTML = "";

        options.forEach((o, i) => {
            addOption(o.id, o.option_label, o.option_text, correct.includes(o.id));
        });
    };

    /* =====================================================
       REMOVE OPTION
    ===================================================== */
    window.removeOption = (btn) => {
        const row = btn.closest(".option-item");
        if (row) row.remove();
    };

    /* =====================================================
       HANDLE TYPE CHANGE
    ===================================================== */
    function handleTypeChange() {
        const select = document.querySelector(`#${modalId}_form [name="question_type_id"]`);
        const typeText = select?.options[select.selectedIndex]?.text?.toLowerCase() ?? "";

        const wrapOpt = document.getElementById("wrap_options");
        if (!wrapOpt) return;

        if (typeText === "esai") {
            wrapOpt.style.display = "none";
            document.querySelector("#optionList").innerHTML = "";
        } else {
            wrapOpt.style.display = "block";

            const list = document.querySelector("#optionList");
            if (list.children.length === 0) {
                addOption();
                addOption();
            }
        }
    }

    $(document).on("change", `#${modalId}_form [name="question_type_id"]`, handleTypeChange);

    /* =====================================================
       SUBMIT FORM
    ===================================================== */
    document.getElementById(`${modalId}_form`).addEventListener("submit", async (e) => {
        e.preventDefault();

        showLoading();

        const url = document.getElementById(`${modalId}_url`).value;
        const method = document.getElementById(`${modalId}_method`).value;

        const fd = new FormData(e.target);
        if (method === "PUT") fd.append("_method", "PUT");

        const res = await apiFetch(url, {
            method: "POST",
            headers: { "Accept": "application/json" },
            body: fd
        }).catch(() => {
            hideLoading();
            Swal.fire("Error", "Terjadi kesalahan jaringan!", "error");
        });

        const data = await res.json();
        hideLoading();
        modal.hide();

        if (data.status === "success") {
            Swal.fire("Berhasil", data.message ?? "Data tersimpan", "success");
            $('#{{ $table }}').DataTable().ajax.reload(null, false);
        } else {
            Swal.fire("Gagal", data.message ?? "Validasi error", "error");
        }
    });
});

/* =====================================================
   LOADING HELPERS
===================================================== */
function showLoading() {
    document.getElementById("btnSubmit").disabled = true;
    document.getElementById("loadingSpinner").classList.remove("d-none");
}

function hideLoading() {
    document.getElementById("btnSubmit").disabled = false;
    document.getElementById("loadingSpinner").classList.add("d-none");
}

/* =====================================================
   API FETCH HELPER — AUTO ADD Authorization: Bearer
===================================================== */
window.apiFetch = function (url, options = {}) {

    options.headers = options.headers || {};

    const token = window.API_TOKEN || localStorage.getItem("api_token");
    if (token) {
        options.headers["Authorization"] = "Bearer " + token;
    }

    if (!options.headers["Accept"]) {
        options.headers["Accept"] = "application/json";
    }

    return fetch(url, options);
};
</script>
@endpush
