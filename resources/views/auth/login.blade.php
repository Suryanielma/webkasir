<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Soto Mba Ratih</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f3ea; 
            font-family: 'Instrument Sans', sans-serif;
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
<body class="min-h-screen w-full relative flex items-center justify-center overflow-x-hidden p-4 sm:p-6 selection:bg-[#c4ca99]">
    
    <div class="absolute top-0 left-0 w-[22vh] sm:w-[30vh] max-w-[280px] z-0 pointer-events-none select-none opacity-50 sm:opacity-100">
        <img src="{{ asset('images/login/soto-kiri-atas.png') }}" class="object-contain w-full h-full" alt="Decor">
    </div>

    <div class="absolute top-1/2 -translate-y-1/2 right-0 w-[32vh] sm:w-[45vh] max-w-[500px] z-0 pointer-events-none select-none opacity-40 sm:opacity-100">
        <img src="{{ asset('images/login/soto-kanan.png') }}" class="object-contain w-full h-full origin-right" alt="Bowl">
    </div>

    <div class="absolute bottom-0 left-0 w-[28vh] sm:w-[40vh] max-w-[450px] z-0 pointer-events-none select-none opacity-40 sm:opacity-100">
        <img src="{{ asset('images/login/soto-kiri-bawah.png') }}" class="object-contain w-full h-full origin-bottom-left" alt="Bowl">
    </div>

    <div class="relative z-10 w-full max-w-md mx-auto bg-[#f4f3ea]/80 sm:bg-transparent backdrop-blur-md sm:backdrop-blur-none p-6 sm:p-0 rounded-3xl shadow-xl shadow-black/5 sm:shadow-none">
        
        <div class="text-center mb-8 md:mb-10">
            <h1 class="text-5xl md:text-7xl font-bold mb-2 tracking-tight text-gray-950">Welcome!</h1>
            <p class="text-xl md:text-3xl text-gray-700 font-medium">Soto Mba Ratih</p>
        </div>

        <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <input type="text" name="username" id="username" value="{{ old('username') }}" 
                       class="form-input w-full px-5 py-3 rounded-full text-base md:text-lg outline-none focus:ring-2 focus:ring-[#c4ca99] focus:border-transparent transition-all text-gray-900 bg-white/70" 
                       placeholder="Username" required>
                @error('username')
                    <span class="text-red-500 text-sm ml-4 block mt-1 font-semibold">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <input type="password" name="password" id="password" 
                       class="form-input w-full px-5 py-3 rounded-full text-base md:text-lg outline-none focus:ring-2 focus:ring-[#c4ca99] focus:border-transparent transition-all text-gray-900 bg-white/70" 
                       placeholder="Kata Sandi" required>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="bg-olive w-full py-3 rounded-full text-base md:text-lg text-black font-bold hover:bg-[#b0b87c] transition shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#c4ca99] focus:ring-offset-[#f4f3ea] cursor-pointer">
                    Masuk
                </button>
            </div>
        </form>
        
    </div>

</body>
</html>