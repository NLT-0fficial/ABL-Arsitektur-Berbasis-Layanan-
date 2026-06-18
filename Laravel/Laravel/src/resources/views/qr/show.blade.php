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
        .qr-img { width: 250px; height: 250px; border-radius: 10px; }
        .hint { font-size: 0.78rem; color: #a0aec0; margin-top: 14px; }
        .back { margin-top: 20px; color: #a0aec0; font-size: 0.85rem;
                text-decoration: none; display: block; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="nama">{{ $kost->nama_penyewa }}</div>
        <div class="kamar">Kamar {{ $kost->nama_kamar_lengkap }}</div>

        <img class="qr-img"
             src="{{ route('qr.generate', $kost->id) }}"
             alt="QR Code Kamar {{ $kost->nama_kamar_lengkap }}">

        <div class="hint">Tunjukkan QR ini ke kamera di pintu masuk</div>
    </div>

    <a href="{{ route('qr.index') }}" class="back">← Ganti Kamar</a>
</body>
</html>