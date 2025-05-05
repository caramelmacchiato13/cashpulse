<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">COE Smart Electric Vehicle</h2>
    <h3 style="text-align:center;">Laporan Arus Kas</h3>
    <h4 style="text-align:center;">Periode: {{ $periode->format('F Y') }}</h4>

    <h3>Kas Masuk</h3>
    <table>
        <tr><th>Jenis</th><th>Jumlah</th></tr>
        <tr><td>Operasional</td><td>Rp {{ number_format($kasmasuk_operasional, 0, ',', '.') }}</td></tr>
        <tr><td>Investasi</td><td>Rp {{ number_format($kasmasuk_investasi, 0, ',', '.') }}</td></tr>
        <tr><td>Pendanaan</td><td>Rp {{ number_format($kasmasuk_pendanaan, 0, ',', '.') }}</td></tr>
    </table>

    <h3>Kas Keluar</h3>
    <table>
        <tr><th>Jenis</th><th>Jumlah</th></tr>
        <tr><td>Operasional</td><td>Rp {{ number_format($kaskeluar_operasional, 0, ',', '.') }}</td></tr>
        <tr><td>Investasi</td><td>Rp {{ number_format($kaskeluar_investasi, 0, ',', '.') }}</td></tr>
        <tr><td>Pendanaan</td><td>Rp {{ number_format($kaskeluar_pendanaan, 0, ',', '.') }}</td></tr>
    </table>

    <h3>Ringkasan Arus Kas</h3>
    <table>
        <tr><th>Kas Awal</th><th>Total Perubahan</th><th>Kas Akhir</th></tr>
        <tr>
            <td>Rp {{ number_format($kas_awal, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($total_perubahan, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($kas_akhir, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
