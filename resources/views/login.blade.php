<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital Teknisi BMKG</title>
     <link rel="icon" type="image/png" href="img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        
        body { 
            background: #080c14; 
            overflow: hidden; 
            position: relative; 
            height: 100vh;
        }

        /* EFEK HUJAN CANVAS */
        #weatherCanvas { position: absolute; inset: 0; z-index: 1; pointer-events: none; }

        /* BACKGROUND UTAMA */
        .hero {
            position: relative; width: 100%; height: 100%;
            background: url("{{ asset('img/peta.png') }}") no-repeat center center;
            background-size: cover; 
            display: flex; justify-content: center; align-items: center;
        }

        .overlay {
            position: absolute; inset: 0;
            background: radial-gradient(circle, rgba(10, 15, 25, 0.6) 0%, rgba(5, 7, 10, 0.95) 100%);
            z-index: 0;
        }

        /* CARD LOGIN */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(184, 255, 77, 0.2);
            padding: 40px 30px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            text-align: center;
            animation: floatUp 0.8s ease-out;
        }

        @keyframes floatUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* LOGO & TITLE */
        .logo-bmkg {
            width: 80px;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 10px rgba(184, 255, 77, 0.3));
        }

        .login-card h2 { 
            color: #fff; 
            font-size: 24px; 
            font-weight: 700; 
            margin-bottom: 5px; 
        }
        
        .login-card h2 span { color: #b8ff4d; }
        
        .login-card p {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            margin-bottom: 30px;
        }

        /* FORM INPUT */
        .form-group { margin-bottom: 20px; text-align: left; }
        
        .form-group label { 
            color: rgba(255,255,255,0.7); 
            font-size: 11px; 
            font-weight: 600;
            display: block; 
            margin-bottom: 8px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group input {
            width: 100%; 
            background: rgba(0,0,0,0.3); 
            border: 1px solid rgba(255,255,255,0.1);
            padding: 14px 15px; 
            border-radius: 12px; 
            color: #fff; 
            outline: none; 
            transition: 0.3s;
            font-size: 14px;
        }

        .form-group input:focus { 
            border-color: #b8ff4d; 
            background: rgba(0,0,0,0.5); 
            box-shadow: 0 0 15px rgba(184, 255, 77, 0.1);
        }

        /* BUTTON */
        .btn-submit {
            width: 100%; 
            background: #b8ff4d; 
            color: #080c14; 
            border: none;
            padding: 14px; 
            border-radius: 12px; 
            font-weight: 700; 
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px; 
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover { 
            background: #fff;
            transform: translateY(-2px); 
            box-shadow: 0 10px 20px rgba(184, 255, 77, 0.3); 
        }

        /* ALERT ERROR */
        .alert-error {
            background: rgba(255, 77, 77, 0.15);
            border: 1px solid rgba(255, 77, 77, 0.3);
            color: #ffcccc;
            font-size: 12px;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
        }
    </style>
</head>

<body>

    <canvas id="weatherCanvas"></canvas>

    <div class="hero">
        <div class="overlay"></div>

        <div class="login-container">
            <div class="login-card">
                <img src="{{ asset('img/logo1.png') }}" alt="BMKG" class="logo-bmkg">
                
                <h2>Digital <span>Teknisi</span></h2>
                <p>Stasiun Meteorologi Tampa Padang</p>

                @if($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.proses') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="teknisi@bmkg.go.id" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-submit">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        /* =============================
           RAIN EFFECT (Sama dengan Dashboard)
        ============================= */
        const canvas = document.getElementById('weatherCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let rainParticles = [];
        class RainDrop {
            constructor() { this.reset(); }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * -canvas.height;
                this.length = Math.random() * 20 + 10;
                this.speed = Math.random() * 8 + 10; 
                this.opacity = Math.random() * 0.2 + 0.1;
            }
            draw() {
                ctx.beginPath();
                ctx.strokeStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.lineWidth = 1;
                ctx.moveTo(this.x, this.y);
                ctx.lineTo(this.x, this.y + this.length);
                ctx.stroke();
            }
            update() {
                this.y += this.speed;
                if (this.y > canvas.height) this.reset();
                this.draw();
            }
        }
        function init() {
            rainParticles = [];
            for (let i = 0; i < 110; i++) rainParticles.push(new RainDrop());
        }
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            rainParticles.forEach(p => p.update());
            requestAnimationFrame(animate);
        }
        init(); animate();

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>