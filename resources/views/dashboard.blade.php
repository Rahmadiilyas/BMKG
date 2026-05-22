<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Teknisi - BMKG</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/heic2any/dist/heic2any.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0f1710;
            --bg-card: #1a241b;
            --accent-lime: #adff2f;
            --header-dark: #162117;
            --text-main: #fff;
            --text-muted: #a0a0a0;
            --border-glass: rgba(173, 255, 47, .15);
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(15, 23, 16, .95), rgba(15, 23, 16, .95)),
                url('https://www.transparenttextures.com/patterns/dark-matter.png');
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* --- UTILITIES --- */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* --- HEADER --- */
        header {
            background: var(--header-dark);
            height: 80px;
            display: flex;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid var(--border-glass);
            justify-content: space-between;
            flex-shrink: 0;
            z-index: 100;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 20px;
            font-weight: 800;
        }

        .logo-area span {
            color: var(--accent-lime);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 15px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-info {
            text-align: right;
            font-size: 12px;
        }

        .user-info strong {
            display: block;
            color: var(--accent-lime);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid var(--accent-lime);
        }

        /* --- LAYOUT GRID --- */
        .container-layout {
            display: grid;
            grid-template-columns: 260px 1fr 300px;
            gap: 20px;
            padding: 20px;
            flex: 1;
            overflow: hidden;
            height: calc(100vh - 80px);
        }

        /* --- CARDS & PANELS --- */
        .card-modern {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border-glass);
            display: flex;
            flex-direction: column;
        }

        /* --- SIDEBAR --- */
        .sidebar-section {
            font-size: 10px;
            font-weight: 800;
            color: var(--accent-lime);
            margin: 15px 0 5px 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 12px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: .2s;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: rgba(173, 255, 47, .1);
            color: var(--accent-lime);
        }

        /* --- MAIN CONTENT AREA --- */
        .content-area {
            overflow-y: auto;
            padding-right: 5px;
        }

        .main-heading {
            font-size: 24px;
            margin-bottom: 25px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-heading span {
            color: var(--accent-lime);
        }

        /* --- STATS GRID --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #000;
            border: 1px solid #333;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: .3s;
        }

        .stat-card:hover {
            border-color: var(--accent-lime);
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(173, 255, 47, 0.1);
            color: var(--accent-lime);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 24px;
            color: #fff;
        }

        .stat-info p {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* --- TABLE STYLES --- */
        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            white-space: nowrap;
        }

        .custom-table th {
            text-align: left;
            padding: 15px;
            color: var(--accent-lime);
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-glass);
        }

        .custom-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 14px;
            color: #eee;
        }

        .custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .img-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #333;
        }

        /* --- CALENDAR & RIGHT PANEL --- */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
        }

        .calendar-day {
            padding: 6px 0;
            border-radius: 6px;
            color: var(--text-muted);
        }

        .cal-today {
            background: var(--accent-lime) !important;
            color: #000 !important;
            font-weight: 800;
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 1024px) {
            body {
                height: auto;
                overflow-y: auto;
            }

            header {
                flex-direction: column;
                height: auto;
                padding: 15px;
                gap: 15px;
            }

            .container-layout {
                display: flex;
                flex-direction: column;
                height: auto;
                padding: 15px;
                gap: 20px;
            }

            .card-modern.sidebar-container {
                flex-direction: row;
                overflow-x: auto;
                white-space: nowrap;
                gap: 10px;
                padding: 15px;
            }

            .sidebar-section {
                display: none;
            }

            /* Hide labels on mobile */
            .sidebar-item {
                margin-bottom: 0;
                flex-shrink: 0;
                background: rgba(255, 255, 255, 0.05);
            }

            .content-area {
                overflow: visible;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            /* Stack cards */
        }

        /* Styling Modal */
.modal-overlay {
    display: none; /* Sembunyi secara default */
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    width: 90%;
    max-width: 400px;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-overlay.active {
    display: flex;
}
.modern-input {
    width: 100%; 
    padding: 12px; 
    background: #000; 
    border: 1px solid #333; 
    color: white; 
    border-radius: 10px;
    box-sizing: border-box;
}

.type-selector {
    padding: 10px;
    border-radius: 8px;
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 600;
    transition: 0.3s;
}

input[type="radio"]:checked + .type-selector {
    background: var(--accent-lime);
    color: #000;
}

.btn-primary {
    width: 100%; 
    background: var(--accent-lime); 
    color: black; 
    border: none; 
    padding: 14px; 
    border-radius: 12px; 
    font-weight: 800; 
    cursor: pointer;
    transition: 0.3s;
}

.btn-primary:hover {
    filter: brightness(1.1);
    transform: translateY(-2px);
}

.btn-secondary {
    background: rgba(255,255,255,0.1); 
    color: white; 
    border: 1px solid #444; 
    padding: 0 15px; 
    border-radius: 10px; 
    font-weight: bold; 
    cursor: pointer;
}
@media (min-width: 1400px) {
    /* Header lebih tinggi dikit agar gagah */
    header {
        height: 90px;
        padding: 0 50px;
    }

    /* Grid layout lebih lebar */
    .container-layout {
        grid-template-columns: 320px 1fr 380px;
        gap: 30px;
        padding: 30px 50px;
    }

    /* Kartu statistik lebih besar */
    .stat-card { padding: 30px; }
    .stat-icon { width: 70px; height: 70px; font-size: 28px; }
    .stat-info h3 { font-size: 36px; }

    /* Modal pengaturan akun lebih lebar di PC */
    .modal-content {
        max-width: 500px;
    }
    
    /* Font Kalender */
    .calendar-grid {
        font-size: 14px;
        gap: 8px;
    }
    .calendar-day {
        padding: 12px 0;
    }
}
    </style>
</head>

<body>

    <header>
        <div class="logo-area">
            <img src="{{ asset('img/logo.png') }}" height="40" alt="Logo">
            BMKG <span>MAMUJU</span>
        </div>

        <div class="user-profile" onclick="openAccountModal()" style="cursor: pointer;">
    <div class="user-info">
        <strong>{{ Auth::check() ? Auth::user()->name : 'Guest' }}</strong>
        <span style="color:var(--text-muted)">Pengaturan Akun</span>
    </div>
    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::check() ? Auth::user()->name : 'BMKG') }}&background=adff2f&color=000" class="user-avatar">
</div>

<div id="accountModal" class="modal-overlay">
    <div class="modal-content card-modern">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--accent-lime); font-size: 18px;">
                <i class="fas fa-user-shield"></i> Pengaturan Akun
            </h3>
            <button onclick="closeAccountModal()" style="background:none; border:none; color:white; cursor:pointer; font-size:24px;">&times;</button>
        </div>

        <form action="{{ route('admin.updateAccount') }}" method="POST">
            @csrf
            
            <div style="display: flex; gap: 10px; margin-bottom: 20px; background: rgba(0,0,0,0.3); padding: 5px; border-radius: 12px;">
                <label style="flex: 1; cursor: pointer; text-align: center;">
                    <input type="radio" name="update_type" value="email" onclick="toggleInput('email')" style="display:none;" checked>
                    <div class="type-selector">Ubah Email</div>
                </label>
                <label style="flex: 1; cursor: pointer; text-align: center;">
                    <input type="radio" name="update_type" value="password" onclick="toggleInput('password')" style="display:none;">
                    <div class="type-selector">Ubah Password</div>
                </label>
            </div>

            <div id="group-email" class="input-group">
                <label style="display:block; font-size:12px; color:var(--text-muted); margin-bottom:5px;">Email Baru</label>
                <input type="email" name="new_email" placeholder="Masukkan email baru..." class="modern-input">
            </div>

            <div id="group-password" class="input-group" style="display: none;">
                <label style="display:block; font-size:12px; color:var(--text-muted); margin-bottom:5px;">Password Baru</label>
                <input type="password" name="new_password" placeholder="Masukkan password baru..." class="modern-input">
            </div>

            <div style="padding: 15px; background: rgba(173, 255, 47, 0.05); border-radius: 12px; border: 1px dashed var(--accent-lime); margin-top: 20px; margin-bottom: 20px;">
                <label style="display:block; font-size:11px; color:var(--accent-lime); margin-bottom:8px; font-weight:bold;">VERIFIKASI KEAMANAN</label>
                <div style="display:flex; gap:10px;">
                    <input type="text" name="otp" placeholder="Kode OTP" class="modern-input" style="flex:1;">
                    <button type="button" onclick="requestOTP()" id="btn-otp" class="btn-secondary">Kirim OTP</button>
                </div>
               <small style="color:var(--text-muted); font-size:10px; margin-top:5px; display:block;">
    OTP akan dikirim ke <span style="color: red; font-weight: bold;">EMAIL LAMA</span> Anda.
</small>
            </div>

            <button type="submit" class="btn-primary">SIMPAN PERUBAHAN</button>
        </form>
    </div>
</div>
    </header>

    <div class="container-layout">

        <div class="card-modern sidebar-container hide-scrollbar">
            <div class="sidebar-section">DASHBOARD</div>
            <a href="{{ route('admin.dashboardku') }}"
                class="sidebar-item {{ request()->routeIs('admin.dashboardku') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>


            <div class="sidebar-section">MANAJEMEN DATA</div>
            <a href="{{ route('admin.lihatkategori') }}"
                class="sidebar-item {{ request()->routeIs('admin.lihatkategori') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Kategori
            </a>
            <a href="{{ route('admin.lihatlink') }}"
                class="sidebar-item {{ request()->routeIs('admin.lihatlink') ? 'active' : '' }}">
                <i class="fas fa-link"></i> Link
            </a>


            <a href="{{ route('admin.lihatfolder') }}"
                class="sidebar-item {{ request()->routeIs('admin.lihatfolder') ? 'active' : '' }}">
                <i class="fas fa-folder"></i> Folder
            </a>

            <a href="{{ route('admin.arsiplink') }}"
                class="sidebar-item {{ request()->routeIs('admin.arsiplink') ? 'active' : '' }}">
                <i class="fas fa-archive"></i> Arsip
            </a>


            <div class="sidebar-section">TEKNISI</div>
            <a href="{{ route('admin.lihatteknisi') }}"
                class="sidebar-item {{ request()->routeIs('admin.lihatteknisi') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Data Teknisi
            </a>


           <div style="margin-top:auto; border-top:1px solid var(--border-glass); padding-top:10px;">
    <a href="{{route('user.dashboard')}}" class="sidebar-item">
        <i class="fas fa-tachometer-alt"></i> Halaman Utama
    </a>
</div>

        </div>

        <div class="content-area hide-scrollbar">
            @yield('content')
        </div>

        <div style="display:flex; flex-direction:column; gap:20px;">

            <div class="card-modern">
                <h4 style="margin:0 0 15px 0; font-size:14px; color:var(--accent-lime);">
                    <i class="fas fa-building"></i> INFO KANTOR
                </h4>
                <div style="font-size:13px; color:var(--text-muted); line-height:1.6;">
                    <strong style="color:white; display:block; margin-bottom:5px;">Stasiun Meteorologi Tampa
                        Padang</strong>
                    Badan Meteorologi, Klimatologi, dan Geofisika (BMKG)<br>
                    <small style="color:var(--accent-lime)">Wilayah Mamuju</small>
                </div>
            </div>

            <div class="card-modern" style="flex:1">
                <h4 style="margin:0; text-align:center; font-size:12px; color:var(--accent-lime); letter-spacing:1px;">
                    KALENDER
                </h4>
                <div id="calendar-grid" class="calendar-grid"></div>
            </div>

        </div>

    </div>

    <script>
        // Calendar Logic Sederhana
        document.addEventListener("DOMContentLoaded", function() {
            const now = new Date();
            const grid = document.getElementById("calendar-grid");
            const days = ["M", "S", "S", "R", "K", "J", "S"];

            days.forEach(d => {
                const el = document.createElement("span");
                el.style.fontWeight = "800";
                el.style.color = "var(--accent-lime)";
                el.innerText = d;
                grid.appendChild(el);
            });

            const totalDays = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
            for (let i = 1; i <= totalDays; i++) {
                const day = document.createElement("div");
                day.className = "calendar-day" + (i === now.getDate() ? " cal-today" : "");
                day.innerText = i;
                grid.appendChild(day);
            }
        });
        function openAccountModal() {
    document.getElementById('accountModal').classList.add('active');
}

function closeAccountModal() {
    document.getElementById('accountModal').classList.remove('active');
}

// Menutup modal jika klik di luar area konten
window.onclick = function(event) {
    let modal = document.getElementById('accountModal');
    if (event.target == modal) {
        closeAccountModal();
    }
}

async function requestOTP() {
    const btn = document.getElementById('btn-otp');
    btn.innerText = "Mengirim...";
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('admin.sendOTP') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            alert('Kode OTP telah dikirim ke email Anda!');
            let countdown = 60;
            const timer = setInterval(() => {
                countdown--;
                btn.innerText = `Tunggu ${countdown}s`;
                if (countdown <= 0) {
                    clearInterval(timer);
                    btn.innerText = "Kirim OTP";
                    btn.disabled = false;
                }
            }, 1000);
        }
    } catch (error) {
        alert('Gagal mengirim OTP. Coba lagi.');
        btn.disabled = false;
        btn.innerText = "Kirim OTP";
    }
}
function toggleInput(type) {
    const emailGroup = document.getElementById('group-email');
    const passwordGroup = document.getElementById('group-password');
    
    if (type === 'email') {
        emailGroup.style.display = 'block';
        passwordGroup.style.display = 'none';
        // Reset input password jika user pindah ke email
        passwordGroup.querySelector('input').value = '';
    } else {
        emailGroup.style.display = 'none';
        passwordGroup.style.display = 'block';
        // Reset input email jika user pindah ke password
        emailGroup.querySelector('input').value = '';
    }
}

// Pastikan fungsi open/close modal tetap ada
function openAccountModal() {
    document.getElementById('accountModal').classList.add('active');
}

function closeAccountModal() {
    document.getElementById('accountModal').classList.remove('active');
}
    </script>
</body>

</html>
