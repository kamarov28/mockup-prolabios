<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Terjadi Kesalahan') - PT Prolabios Multi Niaga</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #C9252C;
            --color-primary-hover: #A81B21;
            --color-primary-light: #FDF2F2;
            --color-slate-900: #0F172A;
            --color-slate-700: #334155;
            --color-slate-600: #475569;
            --color-slate-500: #64748B;
            --color-slate-200: #E2E8F0;
            --color-slate-100: #F1F5F9;
            --color-slate-50: #F8FAFC;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--color-slate-50);
            color: var(--color-slate-900);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            line-height: 1.6;
        }
        .error-card {
            background: #ffffff;
            border: 1px solid var(--color-slate-200);
            border-radius: 1rem;
            padding: 3rem 2rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .error-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 1rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--color-primary);
            background: var(--color-primary-light);
            border: 1px solid #FECDD3;
            border-radius: 9999px;
            margin-bottom: 1.5rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .error-code {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1;
            color: var(--color-slate-900);
            margin-bottom: 0.75rem;
            letter-spacing: -0.03em;
        }
        .error-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--color-slate-900);
            margin-bottom: 0.75rem;
        }
        .error-message {
            font-size: 0.95rem;
            color: var(--color-slate-600);
            margin-bottom: 2rem;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.925rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background-color: var(--color-primary);
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            color: #ffffff;
        }
        .btn-secondary {
            background-color: var(--color-slate-100);
            color: var(--color-slate-700);
            border: 1px solid var(--color-slate-200);
        }
        .btn-secondary:hover {
            background-color: var(--color-slate-200);
            color: var(--color-slate-900);
        }
        .brand-footer {
            margin-top: 2rem;
            font-size: 0.8125rem;
            color: var(--color-slate-500);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-badge">@yield('badge', 'STATUS')</div>
        <div class="error-code">@yield('code', '404')</div>
        <h1 class="error-title">@yield('heading', 'Terjadi Kesalahan')</h1>
        <p class="error-message">@yield('message', 'Halaman yang Anda tuju tidak dapat diproses atau telah dipindahkan.')</p>

        <div class="error-actions">
            @yield('actions')
            <a href="{{ url('/') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Kembali ke Beranda
            </a>
            <a href="{{ url('/kontak') }}" class="btn btn-secondary">
                Hubungi Kami
            </a>
        </div>
    </div>

    <div class="brand-footer">
        &copy; {{ date('Y') }} PT Prolabios Multi Niaga. All rights reserved.
    </div>
</body>
</html>
