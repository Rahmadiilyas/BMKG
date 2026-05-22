@extends('dashboard')

@section('content')

    <h2 class="main-heading">
        MONITORING <span>DASHBOARD</span>
    </h2>

    <div class="stats-grid">
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ count($teknisi) }}</h3>
                <p>Teknisi Aktif</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: #ff9f43; background: rgba(255, 159, 67, 0.1);">
                <i class="fas fa-hdd"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalSizeMB }} <span style="font-size:14px">MB</span></h3>
                <p>Terpakai ({{ $totalFile }} File)</p>
            </div>
        </div>

        </div>

    <div class="card-modern">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3 style="margin:0; font-size:16px; font-weight:800;">
                <i class="fas fa-user-cog" style="color:var(--accent-lime); margin-right:8px;"></i>
                DAFTAR TEKNISI
            </h3>
            </div>

        <div class="table-responsive hide-scrollbar">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="50" style="text-align:center;">No</th>
                        <th style="text-align:center;">Foto</th>
                        <th>Nama Teknisi</th>
                        <th>No HP</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teknisi as $i => $t)
                        <tr>
                            <td style="text-align:center; color:var(--text-muted);">{{ $i+1 }}</td>
                            <td style="text-align:center;">
                                <img src="{{ asset('uploads/teknisi/'.$t->gambar) }}" 
                                     class="img-avatar"
                                     onerror="this.src='{{ asset('img/default-user.png') }}'">
                            </td>
                            <td>
                                <strong style="color:#fff;">{{ $t->nama_teknisi }}</strong>
                            </td>
                            <td style="color:var(--text-muted);">{{ $t->no_hp ?? '-' }}</td>
                            <td>{{ $t->email ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px; color:var(--text-muted);">
                                <i class="fas fa-info-circle" style="margin-bottom:10px; display:block; font-size:20px;"></i>
                                Belum ada data teknisi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection