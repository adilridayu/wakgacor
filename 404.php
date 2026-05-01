<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - Wak Gacor Store</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D4AF37',
                        secondary: '#1A1A1A',
                        dark: '#121212',
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style type="text/tailwindcss">
        @layer utilities {
            .text-outline-gold {
                -webkit-text-stroke: 1px rgba(212, 175, 55, 0.2);
            }
        }
    </style>
</head>

<body class="font-outfit bg-secondary min-h-screen flex items-center justify-center relative overflow-hidden text-white">
    <!-- Background Elements -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary/10 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-primary/5 rounded-full blur-[100px] animate-pulse delay-700"></div>

    <div class="relative z-10 text-center px-6 max-w-2xl">
        <!-- Error Code Background -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 select-none">
            <h2 class="text-[20rem] md:text-[25rem] font-900 leading-none opacity-5 tracking-tighter text-outline-gold">404</h2>
        </div>

        <div class="space-y-8 animate-fade-in-up">
            <div class="relative inline-block group">
                <div class="absolute -inset-4 bg-primary/20 rounded-full blur-xl group-hover:bg-primary/30 transition-all animate-pulse"></div>
                <img src="assets/images/wakgacor.jpeg" alt="Logo" class="relative w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-primary shadow-2xl mx-auto transition-transform hover:scale-105 duration-500">
            </div>

            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-800 tracking-tight uppercase">
                    Oops! <span class="text-primary italic">Tersesat?</span>
                </h1>
                <p class="text-gray-400 text-lg md:text-xl max-w-md mx-auto leading-relaxed">
                    Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan. Mari kembali ke jalur belanja yang benar.
                </p>
            </div>

            <div class="pt-6">
                <a href="index.php" class="inline-flex items-center gap-3 px-10 py-5 bg-primary text-secondary font-800 rounded-full transition-all duration-500 hover:bg-white hover:scale-[1.05] hover:shadow-2xl hover:shadow-primary/30 shadow-xl shadow-primary/20 group">
                    <i class="fas fa-home text-lg"></i> KEMBALI KE BERANDA
                </a>
            </div>
            
            <div class="pt-12">
                <p class="text-[10px] text-gray-600 font-bold uppercase tracking-[4px]">Wak Gacor Store &bull; System Error Handler</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }
    </style>
</body>
</html>

</html>
