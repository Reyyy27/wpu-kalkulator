<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kalkulator Bangun Datar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="resource/css/app.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .card {
            text-align: center;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 20px;
        }

        .result-box {
            border: 1px solid #ced4da;
            padding: 15px;
            border-radius: 5px;
            background-color: #ffffff;
            font-size: 1.25rem;
            text-align: center;
            margin-top: 20px;
        }

        input {
            margin: 5px;
        }

        .btn-group button {
            margin: 5px;
        }

        .btn {
            margin: 5px;
        }

        select {
            margin: 5px;
        }

        
        .edit-mode {
            
            background-color: #e7f0ff; /* Warna latar belakang biru muda */
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2); /* Bayangan biru */
        }
        .edit-mode .btn {
            background-color: #007bff; /* Tombol biru */
            color: #ffffff;
        }
        .edit-mode .btn:hover {
            background-color: #0056b3; /* Tombol biru gelap saat hover */
        }

    </style>
</head>
<body>
<div class="container">
    <div class="card mx-auto" style="max-width: 400px;">
        <h2 class="mb-4" style="padding-top:10px">Kalkulator Luas Bangun Datar</h2>
        <div class="card-body">
            <form id="kalkulatorForm">
                @csrf
                <!-- Dropdown untuk nama_bangun -->
                <select id="nama_bangun" class="form-control mb-2" required>
                    <option value="" disabled selected>Pilih Bangun Datar</option>
                    <option value="Persegi">Persegi</option>
                    <option value="Persegi Panjang">Persegi Panjang</option>
                    <option value="Segitiga">Segitiga</option>
                    <option value="Jajar Genjang">Jajar Genjang</option>
                    <option value="Belah Ketupat">Belah Ketupat</option>
                </select>
                
                <input type="text" id="angka_1" class="form-control mb-2" placeholder="Sisi 1" required>
                <input type="text" id="angka_2" class="form-control mb-2" placeholder="Sisi 2" required>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Hitung Luas</button>
                    <button type="button" id="clearButton" class="btn btn-danger">Clear</button>
                </div>
            </form>

            <div class="result-box">
                <p>Hasil: <span id="result"></span></p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>Data Hasil Perhitungan</h4>
        <table class="table table-bordered" id="hasilTable">
            <thead>
                <tr>
                    <th>Nama Bangun Datar</th>
                    <th>Angka 1</th>
                    <th>Angka 2</th>
                    <th>Hasil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Baris tabel akan diisi oleh jQuery -->
            </tbody>
        </table>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_nama_bangun" class="form-label">Nama Bangun Datar</label>
                            <input type="text" class="form-control" id="edit_nama_bangun" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_angka_1" class="form-label">Angka 1</label>
                            <input type="text" class="form-control" id="edit_angka_1" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_angka_2" class="form-label">Angka 2</label>
                            <input type="text" class="form-control" id="edit_angka_2" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-wgWcX4eF2Xnh4AfuWFOlUrpQ4EVV0aAYX0an9A9SvFpo67bV9S9L2XhD5E6OyxYp" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#kalkulatorForm').on('submit', function(e) {
        e.preventDefault();

        const namaBangun = $('#nama_bangun').val();
        const angka1 = parseFloat($('#angka_1').val());
        const angka2 = parseFloat($('#angka_2').val());
        let hasil;

        switch (namaBangun) {
            case 'Persegi':
                hasil = angka1 * angka1;
                break;
            case 'Persegi Panjang':
                hasil = angka1 * angka2;
                break;
            case 'Segitiga':
                hasil = 0.5 * angka1 * angka2;
                break;
            case 'Jajar Genjang':
                hasil = angka1 * angka2;
                break;
            case 'Belah Ketupat':
                hasil = 0.5 * angka1 * angka2;
                break;
        }

        $('#result').text(hasil);

        // Tambah data ke tabel
        $('#hasilTable tbody').append(`
            <tr data-id="">
                <td>${namaBangun}</td>
                <td>${angka1}</td>
                <td>${angka2}</td>
                <td>${hasil}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-button" data-id="">Edit</button>
                    <button class="btn btn-danger btn-sm delete-button" data-id="">Hapus</button>
                </td>
            </tr>
        `);

        // Kirim data ke server
        $.ajax({
            url: '/simpan-bangun-datar',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: 'application/json',
            data: JSON.stringify({
                nama_bangun: namaBangun,
                angka_1: angka1,
                angka_2: angka2,
                hasil: hasil
            }),
            success: function(response) {
                alert('Data berhasil disimpan.');
                // Update data-id pada baris tabel
                $('#hasilTable tbody tr:last').data('id', response.id);
            },
            error: function() {
                alert('Terjadi kesalahan, data tidak dapat disimpan.');
            }
        });
    });

    $(document).on('click', '.edit-button', function() {
        const $row = $(this).closest('tr');
        const id = $row.data('id');
        const namaBangun = $row.children().eq(0).text();
        const angka1 = $row.children().eq(1).text();
        const angka2 = $row.children().eq(2).text();

        $('#edit_id').val(id);
        $('#edit_nama_bangun').val(namaBangun);
        $('#edit_angka_1').val(angka1);
        $('#edit_angka_2').val(angka2);

        new bootstrap.Modal($('#editModal')).show();
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#edit_id').val();
        const namaBangun = $('#edit_nama_bangun').val();
        const angka1 = parseFloat($('#edit_angka_1').val());
        const angka2 = parseFloat($('#edit_angka_2').val());
        let hasil;

        switch (namaBangun) {
            case 'Persegi':
                hasil = angka1 * angka1;
                break;
            case 'Persegi Panjang':
                hasil = angka1 * angka2;
                break;
            case 'Segitiga':
                hasil = 0.5 * angka1 * angka2;
                break;
            case 'Jajar Genjang':
                hasil = angka1 * angka2;
                break;
            case 'Belah Ketupat':
                hasil = 0.5 * angka1 * angka2;
                break;
        }

        // Kirim data yang telah diubah ke server
        $.ajax({
            url: `/edit-bangun-datar/${id}`,
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: 'application/json',
            data: JSON.stringify({
                nama_bangun: namaBangun,
                angka_1: angka1,
                angka_2: angka2,
                hasil: hasil
            }),
            success: function(response) {
                alert('Data berhasil diperbarui.');
                
                // Update baris tabel dengan data yang diedit
                $(`#hasilTable tbody tr[data-id="${id}"]`).html(`
                    <td>${namaBangun}</td>
                    <td>${angka1}</td>
                    <td>${angka2}</td>
                    <td>${hasil}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-button" data-id="${id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-button" data-id="${id}">Hapus</button>
                    </td>
                `);
                
                // Tutup modal setelah sukses
                $('#editModal').modal('hide');
            },
            error: function() {
                alert('Terjadi kesalahan, data tidak dapat diperbarui.');
            }
        });
    });

    $('#hasilTable').on('click', '.delete-button', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        $.ajax({
            url: `/hapus-perhitungan/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                alert(response.message);
                row.remove();
            },
            error: function() {
                alert('Terjadi kesalahan, data tidak dapat dihapus.');
            }
        });
    });

    $('#clearButton').on('click', function() {
        $('#kalkulatorForm')[0].reset();
        $('#result').text('');
    });

    $('#nama_bangun').on('change', function() {
        const selectedValue = $(this).val();

        $('#angka_1').val('');
        $('#angka_2').val('');
        $('#result').text('');

        switch (selectedValue) {
            case 'Persegi':
                $('#angka_1').attr('placeholder', 'Sisi 1');
                $('#angka_2').attr('placeholder', 'Sisi 2');
                break;
            case 'Segitiga':
                $('#angka_1').attr('placeholder', 'Alas');
                $('#angka_2').attr('placeholder', 'Tinggi');
                break;
            case 'Persegi Panjang':
                $('#angka_1').attr('placeholder', 'Panjang');
                $('#angka_2').attr('placeholder', 'Lebar');
                break;
            case 'Jajar Genjang':
                $('#angka_1').attr('placeholder', 'Alas');
                $('#angka_2').attr('placeholder', 'Tinggi');
                break;
            case 'Belah Ketupat':
                $('#angka_1').attr('placeholder', 'Diagonal 1');
                $('#angka_2').attr('placeholder', 'Diagonal 2');
                break;
            default:
                $('#angka_1').attr('placeholder', 'Sisi 1');
                $('#angka_2').attr('placeholder', 'Sisi 2');
                break;
        }
    });
});

    </script>
</body>
</html>
