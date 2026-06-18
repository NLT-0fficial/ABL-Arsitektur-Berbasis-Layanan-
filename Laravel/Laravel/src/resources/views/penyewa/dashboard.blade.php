<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penyewa — Ekos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #1a202c;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: white;
        }

        .app-name {
            font-size: 1rem;
            font-weight: 600;
            color: #a0aec0;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px 25px;
            text-align: center;
            color: #1a202c;
            width: 100%;
            max-width: 340px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .greeting {
            font-size: 0.85rem;
            color: #a0aec0;
            margin-bottom: 4px;
        }

        .nama {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .kamar {
            display: inline-block;
            background: #ebf8ff;
            color: #2b6cb0;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 999px;
            margin-bottom: 20px;
        }

        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .qr-label {
            font-size: 0.78rem;
            color: #718096;
            margin-bottom: 12px;
        }

        .qr-img {
            width: 220px;
            height: 220px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .hint {
            font-size: 0.75rem;
            color: #a0aec0;
            margin-top: 14px;
            line-height: 1.5;
        }

        /* Kalau belum punya kamar */
        .no-kamar {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 12px;
            padding: 16px;
            color: #c53030;
            font-size: 0.88rem;
            line-height: 1.6;
            margin-top: 8px;
        }

        .logout {
            margin-top: 20px;
            color: #718096;
            font-size: 0.82rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.2s;
        }

        .logout:hover { color: #e53e3e; }
    </style>
</head>
<body>

    <div class="app-name">Ekos</div>

    <div class="card">
        <div class="greeting">Halo,</div>
        <div class="nama">{{ auth()->user()->name }}</div>

        @if ($kost)
            <span class="kamar">Kamar {{ $kost->nama_kamar_lengkap }}</span>

            <hr class="divider">

            <div class="qr-label">QR Code kamu</div>

            <img class="qr-img"
                 src="{{ route('penyewa.qr.generate') }}"
                 alt="QR Code Kamar {{ $kost->nama_kamar_lengkap }}">

            <div class="hint">
                Tunjukkan QR ini ke kamera di pintu masuk/keluar.<br>
                QR bersifat permanen — tidak perlu di-screenshot ulang.
            </div>
        @else
            <hr class="divider">
            <div class="no-kamar">
                ⚠️ Kamu belum memiliki kamar yang terdaftar.<br>
                Hubungi admin untuk informasi lebih lanjut.
            </div>
        @endif
    </div>

    {{-- Logout --}}
    <a href="#" class="logout"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        ← Logout
    </a>

    <form id="logout-form"
          action="{{ route('filament.admin.auth.logout') }}"
          method="POST"
          style="display:none;">
        @csrf
    </form>

</body>
</html>