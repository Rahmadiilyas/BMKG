<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Teknisi BMKG</title>
    <link rel="icon" type="image/png" href="img/logo.png">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* =========================================
           GLOBAL & DESKTOP (TIDAK DIUBAH)
        ========================================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: #080c14;
            overflow: hidden;
            position: relative;
        }

        /* Canvas tetap fixed agar background hujan tidak ikut scroll */
        #weatherCanvas {
            position: fixed; 
            /* elemen menempel walau discrool */
            inset: 0;
            z-index: 3;
            pointer-events: none;
        }

  .hero {
    position: relative;
    width: 100%;
    height: 100vh;
    background: url("{{ asset('img/peta1.svg') }}") no-repeat center center; 
    background-size: cover;
    /* gambar background diperbesar atau diperkecil supaya menutupi seluruh area elemen. */
    
    /* HAPUS ATAU KOMENTAR BARIS DI BAWAH INI */
    /* image-rendering: -webkit-optimize-contrast; */
    /* image-rendering: crisp-edges; */
    
    display: flex;
    justify-content: center;
    align-items: center;
    transition: padding 0.3s ease;
    background-attachment: fixed; 

}
.overlay {
    position: absolute;
    inset: 0;
    /* Ubah 0.2 menjadi 0.5 dan 0.9 menjadi 1.0 untuk kegelapan maksimal */
    background: radial-gradient(circle, rgba(5, 7, 10, 0.5) 0%, rgba(5, 7, 10, 1) 100%);
    opacity: 0.9; /* Naikkan dari 0.8 ke 0.9 atau 1.0 */
    z-index: 1;
}
        /* NAVIGASI DESKTOP */
        .login-nav {
            position: absolute;
            top: 10px;
            /* UPDATED: DIGANTI JADI 10px AGAR LEBIH NAIK */
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 12px;
            align-items: center;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .hero.menu-active .login-nav {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px);
            pointer-events: none;
        }
.hero::after {
    content: '';
    position: absolute;
    inset: 0;
    box-shadow: inset 0 0 150px rgba(0,0,0,0.9);
    z-index: 2;
    pointer-events: none;
}
        .btn-modern {
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 24px;
            border-radius: 50px;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            white-space: nowrap;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-neon-green {
            border-color: rgba(184, 255, 77, 0.4);
        }

        .btn-neon-green i {
            color: #b8ff4d;
            transition: 0.3s;
        }

        .btn-neon-green:hover {
            background: #b8ff4d;
            color: #080c14;
            border-color: #b8ff4d;
            box-shadow: 0 0 25px rgba(184, 255, 77, 0.6);
            transform: translateY(-3px);
        }

        .btn-neon-green:hover i {
            color: #080c14;
        }

        .btn-neon-red {
            border-color: rgba(255, 77, 77, 0.4);
        }

        .btn-neon-red i {
            color: #ff4d4d;
            transition: 0.3s;
        }

        .btn-neon-red:hover {
            background: #ff4d4d;
            color: #fff;
            border-color: #ff4d4d;
            box-shadow: 0 0 25px rgba(255, 77, 77, 0.6);
            transform: translateY(-3px);
        }

        .btn-neon-red:hover i {
            color: #fff;
        }

        /* MODAL */
        .profile-popup {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(15px);
            opacity: 0;
            visibility: hidden;
            transition: 0.4s ease;
            padding: 20px;
            /* Tambahan padding agar tidak mentok di HP */
        }

        .profile-popup.active {
            opacity: 1;
            visibility: visible;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(184, 255, 77, 0.2);
            padding: 40px;
            border-radius: 25px;
            width: 100%;
            max-width: 400px;
            position: relative;
            transform: translateY(30px);
            transition: 0.5s ease;
        }

        .profile-popup.active .profile-card {
            transform: translateY(0);
        }

        .btn-close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.5;
            z-index: 1001;
            /* Pastikan tombol close bisa diklik */
        }

        .btn-close-modal:hover {
            opacity: 1;
            color: #b8ff4d;
        }

        .profile-card h2 {
            color: #fff;
            margin-bottom: 25px;
            font-weight: 700;
            text-align: left;
        }

        .profile-card h2 span {
            color: #b8ff4d;
        }

        .profile-card {
            text-align: center;
        }

        #p-gambar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #b8ff4d;
            margin-bottom: 15px;
        }

        .p-detail {
            text-align: left;
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 15px;
            margin-top: 15px;
        }

        .p-detail label {
            display: block;
            color: #b8ff4d;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .p-detail p {
            color: #fff;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 5px;
        }

        /* HERO & LAYERS */
        .dark-layer {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            transition: 0.4s ease;
            z-index: 4;
            pointer-events: none;
        }

      .hero.menu-active .dark-layer {
    background: rgba(8, 12, 20, 0.85);
    backdrop-filter: blur(8px); /* Efek blur kaca saat menu terbuka */
    -webkit-backdrop-filter: blur(8px);
}

     .content-wrapper { 
    position: relative; 
    z-index: 10; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    text-align: center; 
    width: 100%; 
    
    /* UBAH INI: Dari 1200px jadi 90% atau angka lebih besar */
    max-width: 90%; 
    /* Atau kalau mau tetap batas pixel, naikkan jadi 1600px */
    /* max-width: 1600px; */ 
    
    padding: 20px; 
}
        .hero.menu-active .title-section,
        .hero.menu-active .badge-classic,
        .hero.menu-active .tech-header,
        .hero.menu-active .technician-grid {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: 0.3s ease;
        }

        .title-section,
        .badge-classic,
        .tech-header,
        .technician-grid {
            transition: 0.4s ease;
        }

        .title-section h1 {
            font-size: clamp(22px, 3.5vw, 34px);
            color: #ffffff;
            font-weight: 700;
            line-height: 1.2;
        }

        .title-section h1 span {
            color: #b8ff4d;
            text-shadow: 0 0 15px rgba(184, 255, 77, 0.3);
        }

        .badge-classic {
            margin-top: 15px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            padding: 8px 22px;
            border-radius: 25px;
            display: inline-block;
            font-size: 13px;
            color: #ffffff;
        }

        /* MENU LINGKARAN */
        .circular-menu-wrapper {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 60px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .circular-menu-wrapper.active {
            transform: scale(1.4);
        }

        .logo-trigger {
            position: relative;
            z-index: 100;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .logo-trigger:active {
            transform: scale(0.9);
        }

        .logo-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
            background: rgba(184, 255, 77, 0.12);
            filter: blur(40px);
            z-index: -1;
        }

        .floating-logo {
            width: 120px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.5));
            animation: float 4s ease-in-out infinite;
        }

        .menu-items {
            position: absolute;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .item {
            position: absolute;
            width: 62px;
            height: 62px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(184, 255, 77, 0.3);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transform: scale(0);
            z-index: 50;
            pointer-events: none;
            transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .item:hover {
            background: rgba(184, 255, 77, 0.2);
            border-color: #b8ff4d;
            box-shadow: 0 0 20px rgba(184, 255, 77, 0.4);
            z-index: 60;
        }

        .item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .circular-menu-wrapper.active .item {
            opacity: 1;
            pointer-events: auto;
        }

        /* FOOTER & GRID */
        .tech-header {
            margin: 20px 0 20px 0;
            position: relative;
        }

        .animate-text {
            color: #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 700;
            overflow: hidden;
            white-space: nowrap;
            margin: 0 auto;
            width: fit-content;
            animation: revealText 6s infinite;
        }

        .line-animated {
            width: 0;
            height: 2px;
            background: #b8ff4d;
            margin: 10px auto;
            animation: growLine 6s infinite;
        }

        @keyframes revealText {
            0% {
                opacity: 0;
                width: 0;
            }

            10% {
                opacity: 1;
                width: 100%;
            }

            80% {
                opacity: 1;
                width: 100%;
            }

            100% {
                opacity: 0;
                width: 0;
            }
        }

        @keyframes growLine {
            0% {
                width: 0;
            }

            15% {
                width: 60px;
            }

            80% {
                width: 60px;
            }

            100% {
                width: 0;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .technician-grid {
            display: flex;
            gap: 15px;
            justify-content: center;
            width: 100%;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .tech-card-small {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 18px;
            width: 180px;
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .tech-card-small:hover {
            transform: translateY(-8px);
            border-color: #b8ff4d;
        }

        .tech-avatar-mini {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px;
            border: 2px solid rgba(184, 255, 77, 0.3);
        }

        .tech-card-small h3 {
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            height: 30px;
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            width: 100%;
        }

        .btn-mini {
            background: rgba(127, 182, 201, 0.1);
            border: 1px solid rgba(127, 182, 201, 0.2);
            color: #fff;
            padding: 6px 15px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        .btn-mini:hover {
            background: #b8ff4d;
            color: #000;
        }

        .title-section {
            margin-top: 40px;
        }

        .tech-header {
            margin-top: -45px;
        }

        .technician-grid {
            margin-top: 10px;
        }

        .circular-menu-wrapper {
            margin-top: 50px;
        }


        /* =========================================
           RESPONSIVE STYLE (UPDATED)
        ========================================= */

        /* TABLET & IPAD (Landscape & Portrait) */
        @media (max-width: 1024px) {
            body {
                overflow-y: auto;
                /* Aktifkan scroll di perangkat sentuh */
            }

            .hero {
                height: auto;
                min-height: 100vh;
                padding: 100px 0 80px 0;
                /* Tambah padding atas/bawah */
                align-items: flex-start;
            }

            .circular-menu-wrapper {
                margin: 50px 0;
            }
        }

        /* MOBILE PHONE */
        /* =========================================
           KHUSUS TAMPILAN HP (REDMI 13 & LAINNYA)
        ========================================= */
        @media (max-width: 768px) {

            /* 1. PERBAIKAN NAVIGASI (UPDATED: Dinaikkan Lagi) */
            .login-nav {
                position: relative;
                top: auto;
                right: auto;
                left: auto;
                width: 100%;
                justify-content: center;
                margin-bottom: 20px;
                /* UPDATED: Diubah dari -65px ke -85px agar lebih naik */
                margin-top: -85px;
            }

            .btn-modern {
                padding: 8px 16px;
                font-size: 10px;
                width: auto;
            }

            /* 2. CONTAINER UTAMA */
            .content-wrapper {
                padding: 10px 15px;
                /* Padding dikurangi biar lega */
                justify-content: flex-start;
            }

            .title-section {
                margin-top: 0px;
            }

            .title-section h1 {
                font-size: 1.4rem;
                line-height: 1.4;
            }

            /* 3. MENU LINGKARAN (Dikecilkan dikit biar gak nabrak) */
            .circular-menu-wrapper {
                margin: 30px 0;
                transform: scale(0.9);
                /* Skala default di HP agak kecil */
            }

            .circular-menu-wrapper.active {
                transform: scale(1.0);
                /* Saat aktif normal */
            }

            /* 4. PERBAIKAN JUDUL TEKNISI (RESPONSIF & RAPI) */
            .tech-header {
                width: 100%;
                margin-top: -10px;
                margin-bottom: 20px;
                padding: 0 10px;
            }

            .animate-text {
                white-space: normal;
                /* Izinkan teks turun ke bawah */
                width: 100%;
                font-size: 12px;
                /* Font disesuaikan */
                line-height: 1.6;
                text-align: center;

                /* Matikan animasi "lebar" desktop, ganti animasi simple fade */
                animation: simpleFade 3s infinite;
                opacity: 1;
            }

            .line-animated {
                width: 50px;
                margin: 5px auto;
                animation: none;
                /* Matikan animasi garis biar statis saja di HP */
                background: #b8ff4d;
                opacity: 0.7;
            }

            /* 5. PERBAIKAN GRID TEKNISI (WAJIB 2 KOLOM) */
            .technician-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                /* Rumus PASTI 2 kolom sama rata */
                gap: 10px;
                /* Jarak antar kartu */
                width: 100%;
                max-width: 100%;
                padding-bottom: 60px;
                /* Jarak bawah biar bisa discroll mentok */
            }

            .tech-card-small {
                width: 100% !important;
                /* PAKSA kartu mengikuti lebar kolom grid */
                min-width: 0;
                /* Mencegah overflow */
                margin: 0;
                /* Reset margin */
            }

            .tech-card-small h3 {
                font-size: 11px;
                /* Font nama teknisi disesuaikan */
                height: 35px;
                /* Tinggi fix biar rapi kalau nama panjang */
                align-items: flex-start;
                /* Nama mulai dari atas */
                padding-top: 5px;
            }
        }

        /* Animasi Text Simpel khusus HP (Kedip Halus) */
        @keyframes simpleFade {

            0%,
            100% {
                opacity: 0.8;
            }

            50% {
                opacity: 1;
                text-shadow: 0 0 10px rgba(184, 255, 77, 0.5);
            }
        }

        /* LAYAR SANGAT KECIL */
        @media (max-width: 380px) {
            .circular-menu-wrapper {
                transform: scale(0.7) !important;
                margin: 20px 0;
            }

            .circular-menu-wrapper.active {
                transform: scale(0.85) !important;
            }

            .technician-grid {
                gap: 8px;
            }

            .title-section h1 {
                font-size: 1.2rem;
            }
        }

        @keyframes mobileFadeText {
            0% {
                opacity: 0;
                transform: translateY(5px);
            }

            15% {
                opacity: 1;
                transform: translateY(0);
            }

            85% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(-5px);
            }
        }

        @keyframes mobileFadeLine {

            0%,
            100% {
                width: 0;
                opacity: 0;
            }

            15%,
            85% {
                width: 40px;
                opacity: 1;
            }
        }

        /* =========================================
   KHUSUS LAYAR BESAR (PC / MONITOR 1920px+)
========================================= */
@media (min-width: 1400px) {
    .content-wrapper {
        max-width: 1800px; 
    }

    .title-section h1 {
        font-size: 3.5rem; /* Judul lebih besar di PC */
        margin-bottom: 20px;
    }

    .badge-classic {
        font-size: 16px;
        padding: 10px 30px;
    }

    /* Memperbesar Menu Lingkaran */
    .circular-menu-wrapper {
        transform: scale(1.3); 
        margin: 100px 0;
    }
    
    .circular-menu-wrapper.active {
        transform: scale(1.8); /* Skala saat terbuka lebih besar di PC */
    }

    /* Memperbesar Kartu Teknisi di PC agar tidak kekecilan */
    .technician-grid {
        gap: 30px;
        margin-top: 40px;
    }

    .tech-card-small {
        width: 220px; /* Lebar kartu ditambah dari 180px */
        padding: 25px;
    }

    .tech-avatar-mini {
        width: 60px; /* Foto teknisi lebih besar */
        height: 60px;
    }

    .tech-card-small h3 {
        font-size: 14px;
        margin-bottom: 15px;
    }
}
    </style>
</head>

<body>

    <canvas id="weatherCanvas"></canvas>

    <div class="profile-popup" id="profileModal">
        <div class="profile-card">
            <button class="btn-close-modal" id="closeProfile">&times;</button>
            <img id="p-gambar" src="" alt="Teknisi">
            <h2 id="p-nama">Data <span>Teknisi</span></h2>
            <div class="p-detail">
                <label>Email Teknisi</label>
                <p id="p-email">-</p>
                <label>Nomor WhatsApp</label>
                <p id="p-hp">-</p>
            </div>
        </div>
    </div>

    <div class="hero">
        <div class="overlay"></div>
        <div class="dark-layer"></div>

        <div class="content-wrapper">
            <nav class="login-nav">
                @auth
                    <a href="{{ route('admin.dashboardku') }}" class="btn-modern btn-neon-green">
                        <i class="fa-solid fa-database"></i>
                        Kelola Data
                    </a>

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-modern btn-neon-red">
                            <i class="fa-solid fa-power-off"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('admin.dashboardku') }}" class="btn-modern btn-neon-green">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Kelola Data
                    </a>
                @endauth
            </nav>

            <div class="title-section">
                <h1>Selamat Datang Di Platform <span>Digital Teknisi</span><br>
                    Stasiun Meteorologi <span>Tampa Padang Mamuju</span></h1>
                <div class="badge-classic">Badan Meteorologi, Klimatologi, dan Geofisika</div>
            </div>

            <div class="circular-menu-wrapper" id="mainMenu">
                <div class="logo-trigger" id="logoBtn">
                    <div class="logo-glow"></div>
                    <img src="{{ asset('img/logo1.png') }}" alt="Logo BMKG" class="floating-logo">
                </div>

                <div class="menu-items" id="dynamicMenu">
                    @foreach ($categories as $cat)
                        @php $link = $cat->links->first(); @endphp

                        @if ($link)
                            <a href="{{ $link->url }}" target="_blank" class="item dynamic-item"
                                title="{{ $link->judul_link }}">
                            @else
                                <div class="item dynamic-item" title="Belum ada link">
                        @endif

                        <img src="{{ asset('uploads/kategori/' . $cat->gambar) }}" alt="{{ $cat->nama_kategori }}"
                            onerror="this.src='{{ asset('img/default-menu.png') }}'">

                        @if ($link)
                            </a>
                        @else
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <div class="tech-header">
            <p class="animate-text">Teknisi Stasiun Meteorologi Tampa Padang Mamuju</p>
            <div class="line-animated"></div>
        </div>

        <div class="technician-grid">
            @foreach ($teknisi as $t)
                <div class="tech-card-small">
                    <div class="tech-avatar-mini">
                        <img src="{{ asset('uploads/teknisi/' . $t->gambar) }}"
                            style="width:100%;height:100%;object-fit:cover;"
                            onerror="this.src='{{ asset('img/default-user.png') }}'">
                    </div>
                    <h3>{{ $t->nama_teknisi }}</h3>
                    <button class="btn-mini"
                        onclick="openProfile('{{ $t->nama_teknisi }}', '{{ asset('uploads/teknisi/' . $t->gambar) }}', '{{ $t->email }}', '{{ $t->no_hp }}')">
                        Profil
                    </button>
                </div>
            @endforeach
        </div>
    </div>
    </div>

    <script>
        /* =============================
       PROFIL MODAL
    ============================= */
        const profileModal = document.getElementById('profileModal');

        function openProfile(nama, gambar, email, hp) {
            document.getElementById('p-nama').innerHTML = nama.split(' ')[0] + " <span>Teknisi</span>";
            document.getElementById('p-gambar').src = gambar;
            document.getElementById('p-email').innerText = email || '-';
            document.getElementById('p-hp').innerText = hp || '-';
            profileModal.classList.add('active');
        }
        document.getElementById('closeProfile').addEventListener('click', () => {
            profileModal.classList.remove('active');
        });
        window.addEventListener('click', (e) => {
            if (e.target === profileModal) profileModal.classList.remove('active');
        });

        /* =============================
           CIRCULAR MENU LOGIC & RESPONSIVE RADIUS
        ============================= */
        const logoBtn = document.getElementById('logoBtn');
        const mainMenu = document.getElementById('mainMenu');
        const hero = document.querySelector('.hero');

        logoBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            mainMenu.classList.toggle('active');
            hero.classList.toggle('menu-active');

            // Trigger update posisi saat menu dibuka agar radius sesuai ukuran layar saat itu
            if (mainMenu.classList.contains('active')) {
                updateMenuPositions();
            }
        });

        document.addEventListener('click', () => {
            mainMenu.classList.remove('active');
            hero.classList.remove('menu-active');
        });

        /* =============================
           DYNAMIC POSITION & STAGGERED ANIMATION
        ============================= */
        const items = document.querySelectorAll(".dynamic-item");

        function updateMenuPositions() {
            const total = items.length;
            // LOGIKA RESPONSIF: Jika layar kecil (<768px), radius dikecilkan jadi 95px, jika besar 145px
            const isMobile = window.innerWidth < 768;
            const radius = isMobile ? 95 : 145;

            items.forEach((item, index) => {
                const angle = (360 / total) * index;
                item.dataset.angle = angle;

                // Simpan posisi target di variabel CSS atau langsung kalkulasi
                // Kita kalkulasi ulang transform hanya jika menu sedang aktif
                if (mainMenu.classList.contains("active")) {
                    item.style.transitionDelay = `${index * 0.05}s`; // Delay sedikit dipercepat
                    item.style.transform =
                        `rotate(${angle}deg) translateY(-${radius}px) rotate(-${angle}deg) scale(1)`;
                    item.style.opacity = 1;
                    item.style.pointerEvents = "auto";
                }
            });
        }

        // Reset menu saat menutup
        const observer = new MutationObserver(() => {
            if (!mainMenu.classList.contains("active")) {
                items.forEach((item) => {
                    item.style.transitionDelay = '0s';
                    item.style.transform = "scale(0)";
                    item.style.opacity = 0;
                    item.style.pointerEvents = "none";
                });
            } else {
                updateMenuPositions();
            }
        });

        observer.observe(mainMenu, {
            attributes: true
        });

        // Update posisi jika layar diputar (resize)
        window.addEventListener('resize', () => {
            if (mainMenu.classList.contains('active')) {
                updateMenuPositions();
            }
        });

        /* =============================
           RAIN EFFECT (OPTIMIZED)
        ============================= */
        const canvas = document.getElementById('weatherCanvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        let rainParticles = [];
        class RainDrop {
            constructor() {
                this.reset();
            }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * -canvas.height;
                this.length = Math.random() * 20 + 10; //panjang garis 10 sampai 30 piksel.
                this.speed = Math.random() * 8 + 10; //kecepatan jatuh hujan 
                this.opacity = Math.random() * 0.2 + 0.1;//trasparansi
            }
            //menggambar tetesan hujan
            draw() {
                ctx.beginPath();
                ctx.strokeStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.lineWidth = 1;
                ctx.moveTo(this.x, this.y);
                ctx.lineTo(this.x, this.y + this.length);
                ctx.stroke();
            }
            //menjalankan pergerakan hujan.
            update() {
                this.y += this.speed;
                if (this.y > canvas.height) this.reset();
                this.draw();
            }
        }

        // Jumlah partikel dikurangi sedikit untuk HP agar ringan
        const particleCount = window.innerWidth < 768 ? 60 : 110;

        function init() {
            rainParticles = [];
            for (let i = 0; i < particleCount; i++) rainParticles.push(new RainDrop());
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            rainParticles.forEach(p => p.update());
            requestAnimationFrame(animate);
        }
        init();
        animate();
    </script>
</body>

</html>
