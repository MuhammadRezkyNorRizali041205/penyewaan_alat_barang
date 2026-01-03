<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }

        .company-info h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .invoice-number {
            text-align: right;
        }

        .invoice-number h2 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .invoice-number p {
            font-size: 12px;
            color: #666;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 30px;
        }

        .details-section {
            flex: 1;
        }

        .details-section h3 {
            color: #2c3e50;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .details-section p {
            font-size: 13px;
            margin-bottom: 5px;
        }

        .details-section strong {
            display: block;
            font-size: 14px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        table thead {
            background-color: #2c3e50;
            color: white;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: bold;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .summary-box {
            width: 300px;
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ecf0f1;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .summary-row.total {
            border-top: 2px solid #2c3e50;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #27ae60;
            color: white;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .price-align-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>INVOICE PENYEWAAN</h1>
                <p>Sistem Penyewaan Alat & Barang</p>
            </div>
            <div class="invoice-number">
                <h2>{{ $invoiceNumber }}</h2>
                <p>Tanggal: {{ $penyewaan->created_at->format('d/m/Y') }}</p>
                <p style="margin-top: 10px;"><span class="status-badge">{{ strtoupper($penyewaan->status) }}</span></p>
            </div>
        </div>

        <!-- Details -->
        <div class="invoice-details">
            <div class="details-section">
                <h3>Dari</h3>
                <p><strong>Penyedia Sewa</strong></p>
                <p>Jl. Sistem Informasi No. 1</p>
                <p>Indonesia</p>
            </div>

            <div class="details-section">
                <h3>Kepada</h3>
                <strong>{{ $penyewaan->penyewa->name }}</strong>
                <p>{{ $penyewaan->penyewa->email }}</p>
                <p style="margin-top: 15px;"><strong>Tanggal Mulai Sewa:</strong><br>{{ $penyewaan->tanggal_mulai->format('d/m/Y') }}</p>
                <p style="margin-top: 10px;"><strong>Tanggal Selesai Sewa:</strong><br>{{ $penyewaan->tanggal_selesai->format('d/m/Y') }}</p>
                <p style="margin-top: 10px;"><strong>Durasi Sewa:</strong><br>{{ $rentalDays }} hari</p>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th class="price-align-right">Harga/Hari</th>
                    <th class="price-align-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyewaan->alats as $alat)
                    <tr>
                        <td>{{ $alat->nama_alat }}</td>
                        <td>{{ $alat->pivot->jumlah }}</td>
                        <td class="price-align-right">Rp {{ number_format($alat->pivot->harga_satuan, 0, ',', '.') }}</td>
                        <td class="price-align-right">Rp {{ number_format($alat->pivot->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Durasi Sewa:</span>
                    <span>{{ $rentalDays }} hari</span>
                </div>
                <div class="summary-row total">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih telah menggunakan layanan kami.</p>
            <p>Invoice ini dicetak otomatis pada {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
