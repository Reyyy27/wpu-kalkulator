<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kalkulator Sederhana</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h2 class="mb-4" style="padding-top:10px">Kalkulator Sederhana</h2>
        <div class="card-body">
            <form id="kalkulatorForm">
                @csrf
                <input type="number" id="angka_1" class="form-control mb-2" placeholder="Angka 1" required>
                <input type="number" id="angka_2" class="form-control mb-2" placeholder="Angka 2" required>

                <input type="hidden" id="operation" name="operation">

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-warning" data-operation="tambah">+</button>
                    <button type="button" class="btn btn-warning" data-operation="kurang">-</button>
                    <button type="button" class="btn btn-warning" data-operation="kali">X</button>
                    <button type="button" class="btn btn-warning" data-operation="bagi">/</button>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Hitung</button>
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
        <form action="/" method="get">
            @csrf
            <div class="row mb-3">
                <div class="col-sm-2">
                    <label for="" class="form-label">Tipe</label>
                    <select name="tipe" class="form-select">
                        <option value="">-</option>
                        <option value="tambah">Tambah</option>
                        <option value="kurang">Kurang</option>
                        <option value="kali">Kali</option>
                        <option value="bagi">Bagi</option>
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
<!--  -->
        <table class="table table-bordered" id="hasilTable">
            <thead>
                <tr>
                    <th>Tipe Operasi</th>
                    <th>Angka 1</th>
                    <th>Angka 2</th>
                    <th>Hasil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perhitungan as $data)
                <tr data-id="{{ $data->id }}">
                    <td>{{ $data->tipe }}</td>
                    <td>{{ $data->angka_1 }}</td>
                    <td>{{ $data->angka_2 }}</td>
                    <td>{{ $data->hasil }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-button">Edit</button>
                        <button class="btn btn-danger btn-sm delete-button">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>
</div>

<script>
   $(document).ready(function() {
    // Pilih operasi
    $('.btn-group button').on('click', function() {
        $('#operation').val($(this).data('operation'));
    });

    // Form submit
    $('#kalkulatorForm').on('submit', function(e) {
    e.preventDefault();

    var angka1 = parseFloat($('#angka_1').val());
    var angka2 = parseFloat($('#angka_2').val());
    var operation = $('#operation').val();
    var hasil;

    // Kalkulasi hasil berdasarkan tipe operasi
    if (operation == 'tambah') {
        hasil = angka1 + angka2;
    } else if (operation == 'kurang') {
        hasil = angka1 - angka2;
    } else if (operation == 'kali') {
        hasil = angka1 * angka2;
    } else if (operation == 'bagi') {
        if (angka2 === 0) {
            alert('Error: Pembagian dengan nol tidak diperbolehkan.');
            return;
        }
        hasil = angka1 / angka2;
    }

    $('#result').text(hasil);

    var formAction = $(this).data('id') ? '/edit/' + $(this).data('id') : '/simpan-perhitungan';
    var method = $(this).data('id') ? 'PUT' : 'POST';
    var formData = {
        _token: '{{ csrf_token() }}',
        tipe: operation,
        angka_1: angka1,
        angka_2: angka2,
        hasil: hasil
    };

    $.ajax({
        url: formAction,
        method: method,
        data: formData,
        success: function(response) {
            alert(response.message);

            if (method === 'POST') {
                // Tambah baris baru di tabel
                $('#hasilTable tbody').append(`
                    <tr data-id="${response.id}">
                        <td>${response.tipe}</td>
                        <td>${response.angka_1}</td>
                        <td>${response.angka_2}</td>
                        <td>${response.hasil}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-button">Edit</button>
                            <button class="btn btn-danger btn-sm delete-button">Hapus</button>
                        </td>
                    </tr>
                `);
            } else {
                // Update baris tabel
                var row = $('#hasilTable tbody tr[data-id="' + response.id + '"]');
                row.find('td').eq(0).text(response.tipe);
                row.find('td').eq(1).text(response.angka_1);
                row.find('td').eq(2).text(response.angka_2);
                row.find('td').eq(3).text(response.hasil);
            }

            $('#result').text('');
            $('#angka_1').val('');
            $('#angka_2').val('');
            $('#operation').val('');
            $('#kalkulatorForm').removeClass('edit-mode').removeData('id');
        },
        error: function() {
            alert('Terjadi kesalahan, data tidak dapat disimpan.');
        }
    });
});


    // Clear button
    $('#clearButton').on('click', function() {
        $('#angka_1').val('');
        $('#angka_2').val('');
        $('#result').text('');
        $('#operation').val('');
        $('#kalkulatorForm').removeClass('edit-mode').removeData('id');
    });

    // Edit button
    $('#hasilTable').on('click', '.edit-button', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');
        var angka1 = row.find('td').eq(1).text();
        var angka2 = row.find('td').eq(2).text();
        var tipe = row.find('td').eq(0).text();
        var hasil = row.find('td').eq(3).text();

        // Update form dengan data yang diambil
        $('#angka_1').val(angka1);
        $('#angka_2').val(angka2);
        $('#operation').val(tipe);
        $('#result').text(hasil);
        $('#kalkulatorForm').addClass('edit-mode').data('id', id);
    });

    // Hapus button
    $('#hasilTable').on('click', '.delete-button', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        $.ajax({
            url: '/hapus-perhitungan/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
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
});

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
</body>
</html>
