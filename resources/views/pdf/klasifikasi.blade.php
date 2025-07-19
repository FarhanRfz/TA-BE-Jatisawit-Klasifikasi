<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Klasifikasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <h2>Hasil Klasifikasi Perkembangan Anak</h2>
    <p><strong>Waktu Klasifikasi:</strong> {{ $data->waktu_klasifikasi }}</p>
    <p><strong>Nama Anak:</strong> {{ $data->nama_anak }}</p>
    <p><strong>Umur:</strong> {{ $data->umur }} bulan</p>
    <p><strong>Berat Badan:</strong> {{ $data->berat_badan }} kg</p>
    <p><strong>Tinggi Badan:</strong> {{ $data->tinggi_badan }} cm</p>
    <p><strong>Jenis Kelamin:</strong> {{ ucfirst($data->jenis_kelamin) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($data->status_stunting) }}</p>
    <p><strong>Keterangan:</strong> {{ $data->deskripsi_status }}</p>
</body>
</html>
