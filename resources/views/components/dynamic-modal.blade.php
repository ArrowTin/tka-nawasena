@props([
    'id' => 'modalCrud',
    'title' => 'Form',
    'fields' => [],
    'table' =>'table',
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

        <div class="modal-body row g-3">

          @foreach ($fields as $field)

            <div class="col-md-{{ $field['col'] ?? 12 }}">
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
                  @foreach ($field['options'] as $val => $text)
                    <option value="{{ $val }}">{{ $text }}</option>
                  @endforeach
                </select>
              @endif

              @if ($field['type'] === 'textarea')
                <textarea class="form-control" rows="3" name="{{ $field['name'] }}"></textarea>
              @endif

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


    const modalEl = document.getElementById('{{ $id }}');
    const modal = new bootstrap.Modal(modalEl);

    window.openCreateModal = function (url, title = "Tambah Data") {
        document.getElementById("{{ $id }}_title").innerText = title;

        document.getElementById("{{ $id }}_url").value = url;
        document.getElementById("{{ $id }}_method").value = "POST";
        document.getElementById("{{ $id }}_form").reset();

        modal.show();
    }

    window.openEditModal = async function (url, title = "Edit Data") {
        document.getElementById("{{ $id }}_title").innerText = title;

        document.getElementById("{{ $id }}_url").value = url;
        document.getElementById("{{ $id }}_method").value = "PUT";

        const response = await fetch(url, {
            headers: {
                "Accept": "application/json"
            }
        });

        const res = await response.json();
        const data = res.payload;
        
        Object.keys(data).forEach(key => {
            let input = document.querySelector(`#{{ $id }}_form [name="${key}"]`);
            if (input) input.value = data[key];
        });

        modal.show();
    }

    document.getElementById("{{ $id }}_form").addEventListener("submit", async function(e) {
        e.preventDefault();

        const url = document.getElementById("{{ $id }}_url").value;
        const method = document.getElementById("{{ $id }}_method").value;

        const formData = new FormData(this);

        if (method === "PUT") {
            formData.append("_method", "PUT");
        }

        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Accept": "application/json"
            },
            body: formData
        });

        // ❗ WAJIB pakai await
        const data = await response.json();
        modal.hide();

        // Cek berhasil
        if (data.status==='success') {
            Swal.fire("Berhasil", data.message ?? "Data berhasil disimpan!", "success");

            $('#{{$table}}').DataTable().ajax.reload(null, false);
            return;
        }

        // Cek gagal
        if (data.status === 'error') {
            Swal.fire(
                data.message ?? "Gagal",
                data.errors ? JSON.stringify(data.errors) : "",
                "error"
            );
            return;
        }

        // Jika server error (500 / 404 / dsb)
        Swal.fire("Error", data.message ?? response.statusText, "error");
    });


});
</script>
@endpush
