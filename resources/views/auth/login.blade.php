<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Soto Mba Ratih</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f3ea; 
            font-family: 'Playfair Display', serif;
        }
        .bg-olive {
            background-color: #c4ca99;
        }
        .form-input {
            background-color: transparent;
            border: 1px solid #7a7a7a;
        }
    </style>
</head>
<body class="h-screen w-screen relative flex items-center justify-center overflow-hidden">
    
    <!-- Decorative Images -->
    <!-- Top Left -->
    <div class="absolute -top-4 left-0 w-[30vh] max-w-[300px] hidden md:block">
        <img src="{{ asset('images/login/soto-kiri-atas.png') }}" class="object-contain w-full h-full" alt="Decor">
    </div>

    <!-- Center Right -->
    <div class="absolute top-1/2 -translate-y-1/2 -right-20 lg:-right-10 w-[50vh] max-w-[600px] hidden md:block">
        <img src="{{ asset('images/login/soto-kanan.png') }}" class="object-contain w-full h-full" alt="Bowl">
    </div>

    <!-- Bottom Left -->
    <div class="absolute -bottom-4 left-0 w-[40vh] max-w-[500px] hidden md:block">
        <img src="{{ asset('images/login/soto-kiri-bawah.png') }}" class="object-contain w-full h-full" alt="Bowl">
    </div>

    <!-- Login Form Container -->
    <div class="relative z-10 w-full max-w-md px-6 py-8 md:px-0">
        <div class="text-center mb-10">
            <h1 class="text-6xl md:text-7xl font-bold mb-2 tracking-tight">Welcome!</h1>
            <p class="text-2xl md:text-3xl">Soto Mba Ratih</p>
        </div>

        <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <input type="text" name="username" id="username" value="{{ old('username') }}" 
                       class="form-input w-full px-5 py-3 rounded-full text-lg outline-none focus:ring-2 focus:ring-[#c4ca99] focus:border-transparent transition-all" 
                       placeholder="Username" required>
                @error('username')
                    <span class="text-red-500 text-sm ml-4">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <input type="password" name="password" id="password" 
                       class="form-input w-full px-5 py-3 rounded-full text-lg outline-none focus:ring-2 focus:ring-[#c4ca99] focus:border-transparent transition-all" 
                       placeholder="Kata Sandi" required>
            </div>

            <div class="pt-4">
                <button type="submit" 
                        class="bg-olive w-full py-3 rounded-full text-lg text-black font-semibold hover:bg-[#b0b87c] transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-[#c4ca99] focus:ring-offset-[#f4f3ea]">
                    Masuk
                </button>
            </div>
        </form>
    </div>

</body>
</html>