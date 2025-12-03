/**
 * ====================================================================
 * UNIVERSAL CRUD HELPER — untuk semua datatable
 * ====================================================================
 */

window.CRUD = {
    toKebab(str) {
        return str
            .replace(/([a-z0-9])([A-Z])/g, '$1-$2') 
            .toLowerCase();                         
    },

    // Generate URL otomatis berdasarkan baseApi
    api(base) {
        return {
            index:  base,
            store:  base,
            fetch:   (name) => `${base}/${name}`,
            edit:   (id) => `${base}/${id}`,
            addItem: (id,name) => `${base}/${id}/add-${name}`,
            delete: (id) => `${base}/${id}`,
        };
    },

    // Render list nama relasi
    listNames(name, data, type, row, api, title) {
        // Buat HTML badge untuk data
        let html = data
            ? data.map(item => `<span class="badge rounded-pill bg-info bg-glow">${item.name}</span>`).join(' ')
            : '-';
    
        const urlFetch = api.fetch(CRUD.toKebab(name));
        const urlPost  = api.addItem(row.id, CRUD.toKebab(name));
        
        // Escape JSON supaya aman dimasukkan ke HTML
        const selectedSafe = encodeURIComponent(JSON.stringify(data ?? []));
    
        // Kembalikan HTML dengan tombol tambah
        return `
            ${html}
            <span class="badge rounded-pill bg-primary bg-glow cursor-pointer"
                onclick="openAddItem(
                    '${name}',
                    '${urlFetch}',
                    '${urlPost}',
                    JSON.parse(decodeURIComponent('${selectedSafe}')),
                    '${title}'
                )">
                <i class="fa-solid fa-plus"></i> Tambah
            </span>
        `;
    }
    ,

    // Render tombol aksi
    actionButtons(data, type, row, api) {
        return `
            <button class="btn btn-warning btn-sm"
                onclick="resetModalCreate(); openEditModal('${api.edit(row.id)}')">
                Edit
            </button>

            <button class="btn btn-danger btn-sm"
                onclick="CRUD.confirmDelete('${api.delete(row.id)}', '${row.name}', '${row.table}')">
                Hapus
            </button>
        `;
    },

    // Fungsi Delete universal
    async deleteItem(url) {
        const token = ""; // isi token jika perlu

        const response = await fetch(url, {
            method: "DELETE",
            headers: {
                "Accept": "application/json",
                "Authorization": "Bearer " + token
            }
        });

        return response.json();
    },

    // Konfirmasi delete universal
    confirmDelete(url, name, tableId) {
        Swal.fire({
            title: "Hapus data?",
            html: `Yakin ingin menghapus <b>${name}</b>?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus",
            confirmButtonColor: "#d33",
        }).then(async (res) => {
            if (!res.isConfirmed) return;

            const data = await CRUD.deleteItem(url);

            if (data.status === 'success') {
                Swal.fire("Berhasil", `Data <b>${name}</b> berhasil dihapus`, "success");
                $(`#${tableId}`).DataTable().ajax.reload();
            } else {
                Swal.fire("Error", data.message ?? "Gagal menghapus data", "error");
            }
        });
    }
};
