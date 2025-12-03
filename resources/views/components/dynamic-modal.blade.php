@props([
    'id' => 'modalCrud',
    'title' => 'Form',
    'fields' => [],
    'table' =>'table',
    'relations' => [],
])

<div class="modal fade" id="{{ $id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
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

        <div class="modal-body row g-3">

          @foreach ($fields as $field)
            <div class="col-md-{{ $field['col'] ?? 12 }}" data-field="normal">
              <label class="form-label">{{ $field['label'] }}</label>

              @if ($field['type'] === 'text')
                <input type="text" class="form-control" name="{{ $field['name'] }}">
              @endif
              

              @if ($field['type'] === 'number')
                <input type="number" class="form-control" name="{{ $field['name'] }}">
              @endif

              @if ($field['type'] === 'select')
                <select class="form-select" name="{{ $field['name'] }}">
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
          @foreach ($relations as $relation)
              <div class="col-12"
                  id="wrap_{{ \Illuminate\Support\Str::kebab($relation) }}"
                  data-field="relation"
                  style="display:none">
              </div>
          @endforeach



        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

@push('page-script')
<script>
document.addEventListener("DOMContentLoaded", function () {

const modalId = "modalCreate";
const modal = bootstrap.Modal.getOrCreateInstance(
    document.getElementById(modalId)
);

// 🔹 RESET modal ke mode normal
window.resetModalCreate = function () {

    // tampilkan semua field normal
    document.querySelectorAll(`#${modalId}_form [data-field="normal"]`)
        .forEach(el => el.style.display = "block");

    // sembunyikan semua relasi
    document.querySelectorAll(`#${modalId}_form [data-field="relation"]`)
        .forEach(el => {
            el.style.display = "none";
            el.innerHTML = "";
        });
};

// 🔹 open create
window.openCreateModal = function (url, title = "Tambah Data") {
    resetModalCreate();

    document.getElementById(`${modalId}_title`).innerText = title;
    document.getElementById(`${modalId}_url`).value = url;
    document.getElementById(`${modalId}_method`).value = "POST";

    document.getElementById(`${modalId}_form`).reset();

    modal.show();
};

// 🔹 open edit
window.openEditModal = async function (url, title = "Edit Data") {
    resetModalCreate();

    document.getElementById(`${modalId}_title`).innerText = title;
    document.getElementById(`${modalId}_url`).value = url;
    document.getElementById(`${modalId}_method`).value = "PUT";

    const response = await fetch(url, { headers: { "Accept": "application/json" }});
    const res = await response.json();

    Object.keys(res.payload).forEach(key => {
        let input = document.querySelector(`#${modalId}_form [name="${key}"]`);
        if (input) input.value = res.payload[key];
    });

    modal.show();
};

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

// 🔹 open modal khusus relasi (subjectTypes, roles, dll)
  window.openAddItem = async function(fieldName, fetchUrl, postUrl, selectedItems = [], title = "Pilih Relasi") {

    resetModalCreate();

    document.getElementById(`${modalId}_title`).innerText = title;
    document.getElementById(`${modalId}_url`).value = postUrl;
    document.getElementById(`${modalId}_method`).value = "POST";

    // sembunyikan normal fields
    document.querySelectorAll(`#${modalId}_form [data-field="normal"]`)
        .forEach(el => el.style.display = "none");

    // 🚀 KONVERSI DULU KE CAMELCASE supaya sama seperti ID wrap
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

    /** FORM SUBMIT */
    document.getElementById("{{ $id }}_form").addEventListener("submit", async function(e) {
        e.preventDefault();

        const url = document.getElementById("{{ $id }}_url").value;
        const method = document.getElementById("{{ $id }}_method").value;

        const formData = new FormData(this);

        if (method === "PUT") formData.append("_method", "PUT");

        const response = await fetch(url, {
            method: "POST",
            headers: { "Accept": "application/json" },
            body: formData
        });

        const data = await response.json();
        modal.hide();

        if (data.status == 'success') {
            Swal.fire("Berhasil", data.message ?? "Data berhasil disimpan!", "success");
            $('#{{ $table }}').DataTable().ajax.reload(null, false);
        } else {
            Swal.fire("Gagal", data.message ?? JSON.stringify(data.errors ?? {}), "error");
        }
    });

});

</script>
@endpush
