<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Kamar - eKos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; background: #1a202c; min-height: 100vh;
               padding: 24px 16px; color: white; }
        h1 { font-size: 1.3rem; margin-bottom: 20px; text-align: center; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 12px; max-width: 600px; margin: 0 auto; }
        .card { background: #2d3748; border-radius: 12px; padding: 16px 12px;
                text-align: center; text-decoration: none; color: white;
                transition: background 0.2s; display: block; }
        .card:hover { background: #4a5568; }
        .kamar { font-size: 1.2rem; font-weight: bold; }
        .nama { font-size: 0.8rem; color: #a0aec0; margin-top: 4px; }
        .lantai-label { font-size: 0.85rem; color: #718096; margin: 20px 0 8px;
                        max-width: 600px; margin-left: auto; margin-right: auto; }
    </style>
</head>
<body>
    <h1>🏠 Pilih Kamar Kamu</h1>

    @foreach ($kosts->groupBy('lantai') as $lantai => $kamars)
        <div class="lantai-label">Lantai {{ $lantai }}</div>
        <div class="grid">
            @foreach ($kamars as $kost)
                <a href="{{ route('qr.show', $kost->id) }}" class="card">
                    <div class="kamar">{{ $kost->nama_kamar_lengkap }}</div>
                    <div class="nama">{{ $kost->nama_penyewa }}</div>
                </a>
            @endforeach
        </div>
    @endforeach
</body>
</html>