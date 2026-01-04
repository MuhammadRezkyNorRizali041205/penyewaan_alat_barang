<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', sans-serif;
            color: #2c3e50;
            line-height: 1.5;
            background: #f8f9fa;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 50px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 4px solid #0066cc;
            padding-bottom: 30px;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            font-size: 24px;
            font-weight: 700;
            color: #0066cc;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .company-info p {
            color: #7f8c8d;
            font-size: 11px;
            line-height: 1.6;
        }

        .company-info p:first-of-type {
            color: #555;
            font-size: 12px;
            font-weight: 500;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-type {
            font-size: 28px;
            font-weight: 700;
            color: #0066cc;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .invoice-details {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.8;
        }

        .invoice-details div {
            margin: 4px 0;
        }

        .invoice-details .label {
            font-weight: 600;
            color: #2c3e50;
            display: inline-block;
            width: 110px;
        }

        /* Status Badge */
        .payment-status {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 5px solid #28a745;
            padding: 16px 18px;
            margin-bottom: 35px;
            border-radius: 3px;
        }

        .payment-status-text {
            color: #1e7e34;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        /* Section */
        .section {
            margin-bottom: 35px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0066cc;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8eef5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Customer & Payment Info */
        .info-grid {
            display: flex;
            gap: 60px;
            margin-bottom: 35px;
        }

        .info-block {
            flex: 1;
        }

        .info-block .info-label {
            font-size: 10px;
            color: #95a5a6;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .info-block .info-value {
            font-size: 12px;
            color: #2c3e50;
            line-height: 1.8;
        }

        .info-block .info-value strong {
            font-weight: 600;
            color: #0066cc;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background: #f8f9fa;
            border-top: 2px solid #0066cc;
            border-bottom: 2px solid #0066cc;
        }

        .items-table th {
            padding: 14px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #0066cc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 12px;
            color: #2c3e50;
        }

        .items-table tbody tr:hover {
            background: #f8f9fa;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #0066cc;
        }

        .qty-col,
        .price-col,
        .subtotal-col {
            text-align: right;
        }

        /* Totals */
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 35px;
        }

        .totals-box {
            width: 280px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 11px 0;
            font-size: 11px;
            border-bottom: 1px solid #e8eef5;
        }

        .totals-row.total {
            border-bottom: 2px solid #0066cc;
            border-top: 2px solid #0066cc;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            padding: 14px 12px;
            margin: 8px -12px -11px -12px;
        }

        .totals-row.total .label,
        .totals-row.total .value {
            color: #fff;
        }

        .totals-row .label {
            font-weight: 500;
            color: #2c3e50;
        }

        .totals-row .value {
            text-align: right;
            color: #2c3e50;
        }

        /* Notes */
        .notes-section {
            background: #f8f9fa;
            border-left: 4px solid #0066cc;
            padding: 14px;
            border-radius: 3px;
        }

        .notes-section p {
            font-size: 12px;
            color: #2c3e50;
            line-height: 1.6;
            margin: 0;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #e8eef5;
            padding-top: 25px;
            margin-top: 35px;
            font-size: 10px;
            color: #95a5a6;
            text-align: center;
            line-height: 1.8;
        }

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }

            .container {
                padding: 0;
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-logo">SEWA ALAT</div>
                <p>Sistem Informasi Penyewaan Alat & Barang</p>
                <p style="margin-top: 6px;">Jl. Sultan Adam No. 123, Banjarmasin</p>
                <p>Telp: (021) 123-4567 | Email: info@sewaalat.com</p>
            </div>
            <div class="invoice-meta">
                <div class="invoice-type">INVOICE</div>
                <div class="invoice-details">
                    <div><span class="label">Nomor</span><br>{{ $invoiceNumber }}</div>
                    <div style="margin-top: 8px;"><span class="label">Tanggal Terbit</span><br>{{ $invoiceDate->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 8px;"><span class="label">Tanggal Pembayaran</span><br>{{ $penyewaan->paid_at->translatedFormat('d F Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="payment-status">
            <div class="payment-status-text">PEMBAYARAN BERHASIL</div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-block">
                <div class="info-label">Penyewa</div>
                <div class="info-value">
                    <strong>{{ $penyewaan->penyewa->name }}</strong><br>
                    {{ $penyewaan->penyewa->email }}<br>
                    @if($penyewaan->penyewa->phone)
                        {{ $penyewaan->penyewa->phone }}
                    @endif
                </div>
            </div>

            <div class="info-block">
                <div class="info-label">Periode Penyewaan</div>
                <div class="info-value">
                    <strong>{{ Carbon\Carbon::parse($penyewaan->tanggal_mulai)->translatedFormat('d F Y') }}</strong><br>
                    hingga<br>
                    <strong>{{ Carbon\Carbon::parse($penyewaan->tanggal_selesai)->translatedFormat('d F Y') }}</strong><br>
                    <span style="color: #0066cc; font-weight: 600;">{{ $penyewaan->tanggal_selesai->diffInDays($penyewaan->tanggal_mulai) + 1 }} Hari</span>
                </div>
            </div>

            <div class="info-block">
                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value">
                    <strong>
                        @switch($payment->payment_method)
                            @case('transfer')
                                Transfer Bank
                            @break
                            @case('card')
                                Kartu Kredit
                            @break
                            @case('e-wallet')
                                E-Wallet
                            @break
                            @default
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        @endswitch
                    </strong><br>
                    <span style="color: #7f8c8d; font-size: 11px;">ID: {{ $payment->transaction_id }}</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="section">
            <div class="section-title">Daftar Barang/Alat</div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="qty-col">Jumlah</th>
                        <th class="price-col">Harga Satuan</th>
                        <th class="subtotal-col">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penyewaan->alats as $alat)
                        <tr>
                            <td><strong>{{ $alat->nama }}</strong></td>
                            <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                            <td class="qty-col">{{ $alat->pivot->jumlah }}</td>
                            <td class="price-col">
                                Rp {{ number_format($alat->pivot->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="subtotal-col">
                                Rp {{ number_format($alat->pivot->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-box">
                <div class="totals-row">
                    <span class="label">Subtotal</span>
                    <span class="value">Rp {{ number_format($penyewaan->total_harga, 0, ',', '.') }}</span>
                </div>

                <div class="totals-row">
                    <span class="label">Pajak</span>
                    <span class="value">Rp 0</span>
                </div>

                <div class="totals-row total">
                    <span class="label">TOTAL PEMBAYARAN</span>
                    <span class="value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>

                <div class="totals-row">
                    <span class="label">Telah Dibayar</span>
                    <span class="value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>

                <div class="totals-row">
                    <span class="label">Sisa Tagihan</span>
                    <span class="value">Rp 0</span>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        @if($penyewaan->catatan)
            <div class="section">
                <div class="section-title">Catatan</div>
                <div class="notes-section">
                    <p>{{ $penyewaan->catatan }}</p>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini adalah bukti pembayaran yang sah. Harap disimpan untuk keperluan administrasi.</p>
            <p style="margin-top: 5px;">Generated: {{ now()->translatedFormat('d F Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
