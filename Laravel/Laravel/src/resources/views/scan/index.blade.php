<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner - eKos</title>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; background: #f0f4f8; display: flex;
               gap: 20px; padding: 20px; min-height: 100vh; }
        .left { flex: 0 0 380px; }
        .right { flex: 1; }
        h1 { font-size: 1.3rem; color: #1a202c; margin-bottom: 15px; }
        h2 { font-size: 1rem; color: #4a5568; margin-bottom: 10px; }
        #reader { width: 350px; border-radius: 12px; overflow: hidden; }
        #result { margin-top: 15px; padding: 15px; border-radius: 12px;
                  background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                  min-height: 80px; display: flex; align-items: center;
                  justify-content: center; text-align: center; }
        #result.success { background: #f0fff4; border: 2px solid #48bb78; }
        #result.error   { background: #fff5f5; border: 2px solid #fc8181; }
        #result-text { font-size: 1rem; font-weight: bold; color: #2d3748; }
        .log-table { width: 100%; border-collapse: collapse; background: white;
                     border-radius: 12px; overflow: hidden;
                     box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .log-table th { background: #2b6cb0; color: white; padding: 10px 12px;
                        text-align: left; font-size: 0.85rem; }
        .log-table td { padding: 10px 12px; font-size: 0.85rem;
                        border-bottom: 1px solid #e2e8f0; }
        .log-table tr:last-child td { border-bottom: none; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 0.75rem;
                 font-weight: bold; }
        .badge.masuk  { background: #c6f6d5; color: #276749; }
        .badge.keluar { background: #fed7d7; color: #9b2c2c; }
        #new-log { display: none; }
    </style>
</head>
<body>
    <div class="left">
        <h1>📷 Scanner QR Kost</h1>
        <div id="reader"></div>
        <div id="result">
            <span id="result-text" style="color:#a0aec0">Arahkan kamera ke QR Code...</span>
        </div>
    </div>

    <div class="right">
        <h2>📋 Log Hari Ini</h2>
        <table class="log-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nama</th>
                    <th>Kamar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="log-body">
                @forelse($logsHariIni as $log)
                <tr>
                    <td>{{ $log->scanned_at->format('H:i:s') }}</td>
                    <td>{{ $log->nama_penyewa }}</td>
                    <td>{{ $log->kost->nama_kamar_lengkap ?? '-' }}</td>
                    <td><span class="badge {{ $log->jenis }}">{{ strtoupper($log->jenis) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#a0aec0;padding:20px">
                    Belum ada log hari ini
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        let isProcessing = false;

        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            async (decodedText) => {
                if (isProcessing) return;
                isProcessing = true;

                const resultDiv = document.getElementById('result');
                const resultText = document.getElementById('result-text');

                resultText.textContent = '⏳ Memproses...';
                resultDiv.className = '';

                try {
                    // Extract token dari URL yang di-scan
                    const url = new URL(decodedText);
                    const token = url.searchParams.get('token');

                    const res = await fetch(`/scan/verify?token=${token}`);
                    const data = await res.json();

                    if (data.success) {
                        resultDiv.className = 'success';
                        resultText.textContent = data.message;

                        // Tambah row baru ke tabel log
                        const tbody = document.getElementById('log-body');
                        const emptyRow = tbody.querySelector('td[colspan]');
                        if (emptyRow) emptyRow.closest('tr').remove();

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${data.scanned_at}</td>
                            <td>${data.nama_penyewa}</td>
                            <td>${data.nomor_kamar}</td>
                            <td><span class="badge ${data.jenis}">${data.jenis.toUpperCase()}</span></td>
                        `;
                        tbody.insertBefore(tr, tbody.firstChild);
                    } else {
                        resultDiv.className = 'error';
                        resultText.textContent = data.message;
                    }
                } catch (e) {
                    resultDiv.className = 'error';
                    resultText.textContent = '❌ QR tidak valid atau format salah.';
                }

                // Cooldown 3 detik sebelum bisa scan lagi
                setTimeout(() => {
                    isProcessing = false;
                    resultText.textContent = 'Arahkan kamera ke QR Code...';
                    resultDiv.className = '';
                }, 3000);
            }
        ).catch(err => console.error('Camera error:', err));
    </script>
</body>
</html>