<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Kasir Lapak</title>
    <style>
        body { font-family: 'Public Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        
        /* Header styling matching the provided banner image */
        .header { 
            background: #03510c; 
            /* background: linear-gradient(to right, #03510c, #f1c40f, #03510c); */
            padding: 0px 0px; 
            text-align: center; 
        }
        .header img { max-height: 90px; width: auto; margin-bottom: 0px; }
        .brand-name { 
            color: #ffffff; 
            font-size: 32px; 
            font-weight: 800; 
            margin: 0; 
            letter-spacing: -1px;
            font-family: 'Public Sans', sans-serif;
        }

        .content { padding: 45px 40px; background-color: #ffffff; }
        .footer { background-color: #f9f9f9; padding: 25px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eeeeee; }
        
        h1 { margin-top: 0; color: #1a1a1a; font-size: 26px; font-weight: 700; }
        p { margin-bottom: 25px; font-size: 16px; color: #4a4a4a; line-height: 1.8; }
        
        .button { 
            display: inline-block; 
            padding: 16px 40px; 
            background: #03510c;
            /* background: linear-gradient(to right, #c5a028, #e1b12c); */
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 50px; 
            font-weight: 700; 
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(197, 160, 40, 0.4);
        }
        .highlight { color: #059616; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logo-kasirlapak-white.png') }}" alt="Kasir Lapak Logo">
            <div class="brand-name">Kasir Lapak</div>
        </div>
        <div class="content">
            <h1>Hai {{ $name }}!</h1>
            <p>Anda telah melakukan permintaan RESET PASSWORD. Saat ini password telah direset oleh sistem kami. </p>
            <p>Silakan gunakan password berikut ini :</p>
            <h2>{{ $password }}</h2>
            <p>Untuk keamanan, kami merekomendasikan Anda untuk mengganti password Anda secara berkala.</p>
        </div>
        <div class="footer">
            &copy; 2026 Kasir Lapak - Aplikasi Kasir Modern UMKM.<br>
            Semarang - Indonesia
        </div>
    </div>
</body>
</html>
