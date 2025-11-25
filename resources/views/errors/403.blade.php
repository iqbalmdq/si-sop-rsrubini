<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e0e6fd61.js" crossorigin="anonymous"></script>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;background:#f3f4f6;color:#111827;margin:0}
        .container{max-width:720px;margin:60px auto;padding:24px}
        .card{background:#fff;border-radius:12px;box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -2px rgba(0,0,0,.05);padding:32px;text-align:center}
        .icon{font-size:64px;color:#ef4444}
        .title{font-size:28px;font-weight:700;margin:16px 0}
        .desc{font-size:16px;color:#4b5563;margin-bottom:24px}
        .actions{display:flex;gap:12px;justify-content:center}
        .btn{display:inline-block;padding:12px 18px;border-radius:8px;text-decoration:none;font-weight:600}
        .btn-primary{background:#2563eb;color:#fff}
        .btn-secondary{background:#10b981;color:#fff}
    </style>
 </head>
 <body>
    <div class="container">
        <div class="card">
            <div class="icon"><i class="fas fa-ban"></i></div>
            <div class="title">Akses Ditolak</div>
            <div class="desc">Anda tidak memiliki izin untuk mengakses halaman ini. Silakan masuk ke panel yang sesuai atau kembali ke beranda.</div>
            <div class="actions">
                <a class="btn btn-secondary" href="{{ url('/direktur') }}">Panel Direktur</a>
                <a class="btn btn-primary" href="{{ url('/bidang') }}">Panel Bidang</a>
            </div>
            <div style="margin-top:16px">
                <a href="{{ route('sop.index') }}">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
 </body>
 </html>
