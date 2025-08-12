<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Klasifikasi Perkembangan Anak</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20mm;
        }
        .header {
            text-align: center;
            padding: 10mm 0;
            border-bottom: 2px solid #3498db;
        }
        .header h2 {
            color: #2c3e50;
            margin: 0;
        }
        .content {
            margin-top: 20mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20mm;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #ecf0f1;
            color: #2c3e50;
        }
        .status {
            color: #3c89e7ff;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 10pt;
            color: #7f8c8d;
            position: fixed;
            bottom: 10mm;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Hasil Klasifikasi Perkembangan Anak</h2>
        <p>Puskesmas Jatisawit - {{ date('d-m-Y') }}</p>
    </div>
    <div class="content">
        <table>
            <tr><th>Waktu Klasifikasi</th><td>{{ $data->waktu_klasifikasi }}</td></tr>
            <tr><th>Nama Anak</th><td>{{ $data->nama_anak }}</td></tr>
            <tr><th>Umur</th><td>{{ $data->umur }} bulan</td></tr>
            <tr><th>Berat Badan</th><td>{{ $data->berat_badan }} kg</td></tr>
            <tr><th>Tinggi Badan</th><td>{{ $data->tinggi_badan }} cm</td></tr>
            <tr><th>Jenis Kelamin</th><td>{{ ucfirst($data->jenis_kelamin) }}</td></tr>
        </table>
        <table>
            <tr><th>Status Stunting</th><td class="status">{{ ucfirst($data->status_stunting) }}</td></tr>
            <tr><th>Keterangan</th><td>{{ $data->deskripsi_status }}</td></tr>
        </table>
    </div>
    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem Puskesmas Jatisawit.</p>
        <p>Harap konsultasikan dengan tenaga medis untuk tindakan lebih lanjut.</p>
    </div>
</body>
</html>