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
        select {
            margin: 5px;
        }

        .btn-group button {
            margin: 5px;
        }
        .btn {
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
                    <button type="submit" class="btn btn-success" id="submitButton">Hitung Luas</button>
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
        <!-- filter -->
        <form action="/bangun-datar" method="get">
            @csrf
            <div class="row mb-3">
                <div class="col-sm-2">
                    <label for="" class="form-label">Nama Bangun</label>
                    <select name="nama_bangun" class="form-select">
                        <option value="">-</option>
                        <option value="Persegi">Persegi</option>
                        <option value="Persegi Panjang">Persegi Panjang</option>
                        <option value="Segitiga">Segitiga</option>
                        <option value="Jajar Genjang">Jajar Genjang</option>
                        <option value="Belah Ketupat">Belah Ketupat</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="" class="form-label">Angka 1</label>
                    <div class="input-group">
                        <select name="operator1" class="form-select">
                            <option value="=">=</option>
                            <option value=">">&gt;</option>
                            <option value="<">&lt;</option>
                            <option value="!=">&ne;</option>
                        </select>
                        <input name="angka1" type="number" class="form-control" placeholder="Angka 1" value="{{ isset($_GET['angka1']) ? $_GET['angka1'] : '' }}">
                    </div>
                </div>
                
                <div class="col-sm-3">
                    <label for="" class="form-label">Angka 2</label>
                    <div class="input-group">
                        <select name="operator2" class="form-select">
                            <option value="=">=</option>
                            <option value=">">&gt;</option>
                            <option value="<">&lt;</option>
                            <option value="!=">&ne;</option>
                        </select>
                        <input name="angka2" type="number" class="form-control" placeholder="Angka 2" value="{{ isset($_GET['angka2']) ? $_GET['angka2'] : '' }}">
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <label for="" class="form-label">Hasil</label>
                    <select name="hasil" class="form-select">
                        <option value="">-</option>
                        <option value="terbesar">Terbesar</option>
                        <option value="terkecil">Terkecil</option>
                    </select>
                </div>
                <div class="col-sm-2" style="padding-top: 12px">
                    <button type="submit" class="btn btn-primary mt-4">Search</button>
                </div>
            </div>
        </form>

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
                <!-- Data akan ditambahkan di sini melalui JavaScript -->
                @foreach ($bangunDatar as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->nama_bangun }}</td>
                        <td>{{ $item->angka_1 }}</td>
                        <td>{{ $item->angka_2 }}</td>
                        <td>{{ $item->hasil }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-button" data-id="{{ $item->id }}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-button" data-id="{{ $item->id }}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9oKpe3TCwFpe17P4B+8k1FJ7x44brvI7eqoA1MT4gxpmfjf6WLM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-pfNXPgFjw8Y+e0BdB7gxnNT7L0v2i4Y2l7O8XEB6WfATwQ96h2xaR1Z8IKc+q9w6L" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Mode saat ini: 'input' atau 'edit'
    let currentMode = 'input';
    let currentId = null;

    // Event listener untuk submit form kalkulator
    $('#kalkulatorForm').on('submit', function(e) {
        e.preventDefault();

        const namaBangun = $('#nama_bangun').val();
        const angka1 = parseFloat($('#angka_1').val());
        const angka2 = parseFloat($('#angka_2').val());
        let hasil;

        // Logika perhitungan berdasarkan bangun datar yang dipilih
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

        if (currentMode === 'input') {
            // Kirim data ke server dan tambahkan ke tabel
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
                    $('#hasilTable tbody').append(`
                        <tr data-id="${response.id}">
                            <td>${response.nama_bangun}</td>
                            <td>${response.angka_1}</td>
                            <td>${response.angka_2}</td>
                            <td>${response.hasil}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-button" data-id="${response.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-button" data-id="${response.id}">Hapus</button>
                            </td>
                        </tr>
                    `);
                    // Reset form setelah data disimpan
                    $('#kalkulatorForm')[0].reset();
                    $('#result').text('');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan, data tidak dapat disimpan.');
                }
            });
        } else if (currentMode === 'edit') {
            // Kirim data yang telah diedit ke server
            $.ajax({
                url: `/edit-bangun-datar/${currentId}`,
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
                    let row = $(`#hasilTable tbody tr[data-id="${response.id}"]`);
                    row.find('td').eq(0).text(response.nama_bangun);
                    row.find('td').eq(1).text(response.angka_1);
                    row.find('td').eq(2).text(response.angka_2);
                    row.find('td').eq(3).text(response.hasil);
                    // Kembali ke mode input setelah edit
                    currentMode = 'input';
                    $('#kalkulatorForm')[0].reset();
                    $('#result').text('');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan, data tidak dapat diperbarui.');
                }
            });
        }
    });

    // Edit button click
    $('#hasilTable').on('click', '.edit-button', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        const namaBangun = row.find('td').eq(0).text();
        const angka1 = row.find('td').eq(1).text();
        const angka2 = row.find('td').eq(2).text();

        // Set data to form
        $('#nama_bangun').val(namaBangun);
        $('#angka_1').val(angka1);
        $('#angka_2').val(angka2);

        // Set mode ke edit
        currentMode = 'edit';
        currentId = id;
        $('.card').addClass('edit-mode'); // Menandai bahwa sedang dalam mode edit
    });

    // Hapus button click
    $('#hasilTable').on('click', '.delete-button', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');

        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: `/hapus-bangun-datar/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    alert('Data berhasil dihapus.');
                    row.remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Terjadi kesalahan, data tidak dapat dihapus.');
                }
            });
        }
    });

    // Event listener untuk tombol clear
    $('#clearButton').on('click', function() {
        $('#kalkulatorForm')[0].reset();
        $('#result').text('');
        currentMode = 'input'; // Reset mode ke input
        $('.card').removeClass('edit-mode'); // Hapus penandaan mode edit
    });
});
</script>
</body>
</html>
