@extends('dashboard')

@section('content')

{{-- ================= CSS KHUSUS ================= --}}
<style>
    /* Variabel Warna */
    :root {
        --accent-lime: #adff2f;
        --bg-card: #1a241b;
        --text-muted: #a0a0a0;
        --danger: #ff4d4d;
        --primary: #2d7fff;
    }

    /* Heading */
    .main-heading {
        font-size: 24px; margin-bottom: 25px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 10px;
    }
    .main-heading span { color: var(--accent-lime); }

    /* Card Styling */
    .card-modern {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(173, 255, 47, 0.15);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    /* Table Styles */
    .table-responsive { overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
    .custom-table th {
        text-align: left; padding: 15px; color: var(--accent-lime); font-size: 11px; text-transform: uppercase;
        border-bottom: 1px solid rgba(173, 255, 47, 0.2); letter-spacing: 1px;
    }
    .custom-table td {
        padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px; color: #eee; vertical-align: middle;
    }
    .custom-table tr:hover td { background: rgba(255,255,255,0.02); }

    /* Badge Kategori */
    .badge-category {
        display: inline-block; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px;
        background: rgba(173, 255, 47, 0.1); color: var(--accent-lime); border: 1px solid rgba(173, 255, 47, 0.2);
    }

    /* Link Style */
    .table-link { color: #5aa0ff; text-decoration: none; font-weight: 500; transition: .2s; }
    .table-link:hover { color: var(--accent-lime); text-decoration: underline; }

    /* Date Style */
    .date-badge {
        color: #ccc; font-family: monospace; font-size: 12px; background: rgba(0,0,0,0.3); padding: 4px 8px; border-radius: 4px; border: 1px solid #333;
    }
</style>

{{-- HEADING --}}
<div class="main-heading">
    <i class="fas fa-archive"></i>
    Arsip <span>Link</span>
</div>

{{-- CARD CONTAINER --}}
<div class="card-modern">
    <div style="margin-bottom: 20px;">
        <h4 style="margin:0; font-size:16px; color:var(--accent-lime); letter-spacing:0.5px;">
            <i class="fas fa-history mr-2"></i> RIWAYAT LINK
        </h4>
        <small style="color: var(--text-muted);">Daftar link yang telah diarsipkan.</small>
    </div>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>Kategori</th>
                    <th>Judul Lama</th>
                    <th width="35%">URL Lama</th>
                    <th width="15%" class="text-center">Tanggal Arsip</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsip as $a)
                <tr>
                    <td class="text-center" style="color: var(--text-muted);">{{ $loop->iteration }}</td>
                    
                    <td>
                        <span class="badge-category">
                            {{ $a->kategori->nama_kategori ?? '-' }}
                        </span>
                    </td>
                    
                    <td style="font-weight: 600;">{{ $a->judul_link }}</td>
                    
                    {{-- URL --}}
                    <td>
                        <a href="{{ $a->url }}" target="_blank" class="table-link" title="{{ $a->url }}">
                            <i class="fas fa-external-link-alt mr-1" style="font-size: 10px;"></i>
                            {{ \Illuminate\Support\Str::limit($a->url, 45) }}
                        </a>
                    </td>

                    {{-- TANGGAL --}}
                    <td class="text-center">
                        <span class="date-badge">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $a->created_at->format('d/m/Y') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-box-open fa-3x mb-3" style="opacity:0.5;"></i><br>
                        Belum ada data arsip.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection