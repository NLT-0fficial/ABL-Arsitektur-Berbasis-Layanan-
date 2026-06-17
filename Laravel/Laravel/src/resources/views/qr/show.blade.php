<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Kamar {{ $kost->nama_kamar_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; background: #1a202c; min-height: 100vh;
               display: flex; flex-direction: column; align-items: center;
               justify-content: center; padding: 20px; color: white; }
        .card { background: white; border-radius: 20px; padding: 30px 25px;
                text-align: center; color: #1a202c; width: 100%; max-width: 320px; }
        .nama { font-size: 1.2rem; font-weight: bold; margin-bottom: 4px; }
        .kamar { font-size: 0.9rem; color: #718096; margin-bottom: 20px; }
        #qr-image { width: 250px; height: 250px; border-radius: 10px; }
        .timer-bar { width: 100%; height: 6px; background: #e2e8f0;
                     border-radius: 3px; margin-top: 15px; overflow: hidden; }
        .timer-fill { height: 100%; background: #48bb78;
                      transition: width 1s linear, background 1s; }
        .timer-text { font-size: 0.8rem; color: #718096; margin-top: 8px; }
        .back { margin-top: 20px; color: #a0aec0; font-size: 0.85rem;
                text-decoration: none; display: block; text-align: center; }
        .refreshing { opacity: 0.5; }
    </style>
</head>
<body>
    <div class="card">
        <div class="nama">{{ $kost->nama_penyewa }}</div>
        <div class="kamar">Kamar {{ $kost->nama_kamar_lengkap }}</div>

        <img id="qr-image" src="{{ route('qr.generate', $kost->id) }}" alt="QR Code">

        <div class="timer-bar">
            <div class="timer-fill" id="timer-fill" style="width: 100%"></div>
        </div>
        <div class="timer-text" id="timer-text">Refresh dalam 30 detik</div>
    </div>

    <a href="{{ route('qr.index') }}" class="back">← Ganti Kamar</a>

    <script>
        const kostId = {{ $kost->id }};
        const totalSeconds = 30;
        let remaining = totalSeconds;
        let timerInterval;

        function refreshQr() {
            const img = document.getElementById('qr-image');
            img.classList.add('refreshing');

            // Tambah timestamp untuk bypass cache browser
            img.src = `/qr/${kostId}/generate?t=${Date.now()}`;
            img.onload = () => img.classList.remove('refreshing');

            remaining = totalSeconds;
        }

        function updateTimer() {
            remaining--;
            const pct = (remaining / totalSeconds) * 100;
            const fill = document.getElementById('timer-fill');
            const text = document.getElementById('timer-text');

            fill.style.width = pct + '%';
            fill.style.background = remaining > 10 ? '#48bb78' : '#f56565';
            text.textContent = `Refresh dalam ${remaining} detik`;

            if (remaining <= 0) {
                refreshQr();
            }
        }

        // Mulai timer
        timerInterval = setInterval(updateTimer, 1000);
    </script>
</body>
</html>