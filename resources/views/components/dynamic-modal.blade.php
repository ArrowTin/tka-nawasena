@props([
    'id' => 'modalCrud',
    'title' => 'Form',
    'fields' => [],
    'table' =>'table',
    'relations' => [],
])


<div class="modal fade" id="{{ $id }}" tabindex="-1"  data-bs-backdrop="static" 
data-bs-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="{{ $id }}_title">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="{{ $id }}_form">

        <input type="hidden" id="{{ $id }}_method" value="POST">
        <input type="hidden" id="{{ $id }}_url" value="">

        {{-- Field yang akan di-disable saat openAddItem --}}
        <input type="hidden" id="{{ $id }}_disable_fields"
               value='@json(collect($fields)->where("disable_on_additem", true)->pluck("name"))'>

        <div class="modal-body ps-container" style="max-height:70vh; overflow-y:auto;">
          <div class="row g-3">


            @foreach ($fields as $field)
              <div class="col-md-{{ $field['col'] ?? 12 }}" data-field="normal">
                <label class="form-label">{{ $field['label'] }}</label>

                @if ($field['type'] === 'text')
                  <input type="text" class="form-control" name="{{ $field['name'] }}">
                @endif

                @if ($field['type'] === 'file')
                    <input type="file" class="form-control" name="{{ $field['name'] }}">
                @endif
                

                @if ($field['type'] === 'number')
                  <input type="number" class="form-control" name="{{ $field['name'] }}">
                @endif

                @if ($field['type'] === 'select')
                <select class="form-select select2" name="{{ $field['name'] }}">
                    <option value="">— Pilih —</option>
                    @foreach ($field['options'] ?? [] as $val => $text)
                      <option value="{{ $val }}">{{ $text }}</option>
                    @endforeach
                  </select>
                @endif

                @if ($field['type'] === 'textarea')
                  <textarea class="form-control" rows="3" name="{{ $field['name'] }}"></textarea>
                @endif
              </div>
            @endforeach

            {{-- RELATIONS — tampil hanya saat openAddItem --}}
            @foreach ($relations as $name => $relation)
            {{-- DYNAMIC OPTIONS INPUT --}}
            @if ($name === 'options')
                <div class="col-12" id="wrap_options" data-field="relation" style="display:none">
      
                    <div class="d-flex justify-content-between mb-2">
                        <h5>Opsi Jawaban</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addOption()">Tambah Opsi</button>
                    </div>
      
                    <div id="optionList"></div>
      
                    
                </div>
                    
                @else
                    
                <div class="col-12"
                    id="wrap_{{ \Illuminate\Support\Str::kebab($relation) }}"
                    data-field="relation"
                    style="display:none">
                </div>
                @endif

            @endforeach

        </div>


        </div>

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

@push('script')
<script>
document.addEventListener("DOMContentLoaded", function () {

const modalId = "{{ $id }}";
const modalEl = document.getElementById(modalId);
const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

/* =====================================================
   SELECT2 — init
===================================================== */
  function initSelect2() {
      if (!$.fn.select2) {
          console.error("Select2 belum terload!");
          return;
      }
      $(`#${modalId} .select2`).select2({
          dropdownParent: $(`#${modalId}`),
          width: "100%"
      });
  }


/* =====================================================
   RESET MODAL
===================================================== */
window.resetModalCreate = function () {

    // tampilkan field normal
    document.querySelectorAll(`#${modalId}_form [data-field="normal"]`)
        .forEach(el => el.style.display = "block");

    // sembunyikan field relasi
    // sembunyikan field relasi TAPI JANGAN HAPUS STRUKTURNYA
document.querySelectorAll(`#${modalId}_form [data-field="relation"]`)
    .forEach(el => {
        el.style.display = "none";

        // HANYA reset optionList jika ada
        const list = el.querySelector("#optionList");
        if (list) list.innerHTML = "";
    });


    // reset select2
    setTimeout(() => {
        $(`#${modalId} .select2`).val("").trigger("change");
    }, 50);
};

/* =====================================================
   OPEN CREATE
===================================================== */
window.openCreateModal = function (url, title = "Tambah Data") {

    resetModalCreate();

    document.getElementById(`${modalId}_title`).innerText = title;
    document.getElementById(`${modalId}_url`).value = url;
    document.getElementById(`${modalId}_method`).value = "POST";

    const form = document.getElementById(`${modalId}_form`);
    form.reset();

     // tampilkan opsi
    const wrapOpt = document.getElementById("wrap_options");
    if (wrapOpt) wrapOpt.style.display = "block";

    // ============================================
    // ⬇️ TAMBAHAN PENTING: default 2 opsi
    // ============================================
    const optionList = document.getElementById("optionList");
    if (optionList) {
        optionList.innerHTML = "";
        addOption();
        addOption();
    }
    // ============================================


    setTimeout(() => {
        handleTypeChange();
    }, 200);


    // reset Select2
    $(`#${modalId} .select2`).val("").trigger("change");

    modal.show();

    // init select2 setelah modal muncul
    setTimeout(() => initSelect2(), 150);
};

/* =====================================================
   OPEN EDIT
===================================================== */
window.openEditModal = async function (url, title = "Edit Data") {

    resetModalCreate();

    document.getElementById(`${modalId}_title`).innerText = title;
    document.getElementById(`${modalId}_url`).value = url;
    document.getElementById(`${modalId}_method`).value = "PUT";

    setTimeout(() => {
        handleTypeChange();
    }, 200);


    const response = await fetch(url, { headers: { "Accept": "application/json" }});
    const res = await response.json();
    const data = res.payload ?? {};

    // jika ada opsi
    if (data.options) {
        document.getElementById("wrap_options").style.display = "block";
        const correctIds = data.options
            .filter(o => o.correct_answer !== null)
            .map(o => o.id);

        fillOptions(data.options, correctIds);

    }


    // isi field
    Object.keys(data).forEach(key => {
        let input = $(`#${modalId}_form [name="${key}"]`);

        if (input.length) {
            input.val(data[key]);

            // jika select2 → trigger change
            if (input.hasClass("select2")) {
                input.trigger("change");
            }
        }
    });

    modal.show();

    // init select2
    setTimeout(() => initSelect2(), 150);
};

/* =====================================================
   TO CAMEL + KEBAB
===================================================== */
function toCamel(str) {
    return str.replace(/([-_][a-z])/g, group =>
        group.toUpperCase().replace('-', '').replace('_', '')
    );
}

function toKebab(str) {
    return str
        .replace(/([a-z0-9])([A-Z])/g, '$1-$2')
        .toLowerCase();
}

/* =====================================================
   OPEN RELATION (checkbox)
===================================================== */
window.openAddItem = async function(fieldName, fetchUrl, postUrl, selectedItems = [], title = "Pilih Relasi") {

    resetModalCreate();

    document.getElementById(`${modalId}_title`).innerText = title;
    document.getElementById(`${modalId}_url`).value = postUrl;
    document.getElementById(`${modalId}_method`).value = "POST";

    document.querySelectorAll(`#${modalId}_form [data-field="normal"]`)
        .forEach(el => el.style.display = "none");

    const wrapId = `wrap_${toKebab(fieldName)}`;
    const wrap = document.getElementById(wrapId);

    if (!wrap) return;

    wrap.style.display = "block";
    wrap.innerHTML = "Loading...";

    const response = await fetch(fetchUrl, { headers: { "Accept": "application/json" }});
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
                <label class="form-check-label">${row.name}</label>
            </div>
        `;
    });

    modal.show();
};

window.addOption = function(id = null, label = "", text = "", correct = false) {
    const container = document.getElementById("optionList");
    if (!container) return;

    const index = container.children.length + 1;
    const optionId = id ?? `new-${index}`;
    const labelLetter = label || String.fromCharCode(64 + index);

    container.insertAdjacentHTML(
        "beforeend",
        `
        <div class="border rounded p-2 mb-2 option-item" data-option="${optionId}">
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
                        <input type="checkbox" class="form-check-input"
                            name="correct_option_ids[]" value="${optionId}"
                            ${correct ? "checked" : ""}>
                        <label class="form-check-label ms-1">Benar?</label>
                    </div>
                </div>

                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-danger mt-3"
                        onclick="removeOption(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>

            </div>
        </div>`
    );
};





    
    
// Saat openEditModal memuat data opsi
window.fillOptions = function(options = [], correct = []) {
  const wrap = document.getElementById("optionList");
  wrap.innerHTML = "";

  options.forEach((o, idx) => {
      addOption(
          o.id,                      // ID asli
          o.option_label,
          o.option_text,
          correct.includes(o.id)     // true/false
      );
  });
};

window.removeOption = function(btn) {
    const item = btn.closest('.option-item');
    if (item) item.remove();
};


function handleTypeChange() {
    
  const select = document.querySelector(`#${modalId}_form [name="question_type_id"]`);

  const typeText = select ? select.options[select.selectedIndex].text : null;
    console.log(typeText);
    
    const wrapOpt = document.getElementById("wrap_options");

    if (!wrapOpt) return;

    if (typeText?.toLowerCase() == "esai") { 
        // Esai → hide opsi
        wrapOpt.style.display = "none";
        document.querySelector("#optionList").innerHTML = "";
    } else {
        // Pilihan ganda → show opsi
        wrapOpt.style.display = "block";

        // Jika kosong → tambah minimal 2 opsi
        if (document.querySelector("#optionList").children.length === 0) {
            addOption();
            addOption();
        }
    }
}




/* =====================================================
   FORM SUBMIT
===================================================== */
document.getElementById("{{ $id }}_form").addEventListener("submit", async function(e) {
    e.preventDefault();

    showLoading(); // ⬅️ TAMBAHKAN DISINI

    const url = document.getElementById("{{ $id }}_url").value;
    const method = document.getElementById("{{ $id }}_method").value;

    const formData = new FormData(this);

    if (method === "PUT") formData.append("_method", "PUT");

    const response = await fetch(url, {
        method: "POST",
        headers: { "Accept": "application/json" },
        body: formData
    }).catch(err => {
        hideLoading();
        Swal.fire("Error", "Terjadi kesalahan jaringan!", "error");
    });

    const data = await response.json();

    hideLoading(); // ⬅️ DAN DISINI

    modal.hide();

    if (data.status == 'success') {
        Swal.fire("Berhasil", data.message ?? "Data berhasil disimpan!", "success");
        $('#{{ $table }}').DataTable().ajax.reload(null, false);
    } else {
        Swal.fire("Gagal", data.message ?? JSON.stringify(data.errors ?? {}), "error");
    }
});


$(document).on("change", `#${modalId}_form [name="question_type_id"]`, function () {
        handleTypeChange();
});

});
function showLoading() {
    document.getElementById("btnSubmit").disabled = true;
    document.getElementById("loadingSpinner").classList.remove("d-none");
}

function hideLoading() {
    document.getElementById("btnSubmit").disabled = false;
    document.getElementById("loadingSpinner").classList.add("d-none");
}

</script>
@endpush

