<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$user_first_name = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : '';
// Get current page to set active navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Wak Gacor Store</title>

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
        @layer components {
            .nav-link-custom {
                @apply flex items-center px-4 py-2 rounded-xl transition-all duration-300 font-semibold text-secondary hover:bg-secondary hover:text-primary;
            }
            .nav-link-active {
                @apply flex items-center px-4 py-2 rounded-xl bg-secondary text-primary font-bold;
            }
            .btn-premium {
                @apply inline-flex items-center gap-2 px-8 py-4 bg-primary text-secondary font-bold rounded-2xl transition-all duration-500 shadow-lg hover:shadow-primary/30 hover:-translate-y-1 hover:bg-white;
            }
        }
    </style>
</head>

<body class="font-outfit bg-[#F8F9FA] text-secondary min-h-screen flex flex-col">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100/50">
        <nav class="container mx-auto px-6 md:px-12 lg:px-20 py-6 flex items-center justify-between">
            <div class="flex items-center gap-4 group">
                <img src="../assets/images/wakgacor.jpeg" alt="Logo Wak Gacor" class="w-14 h-14 rounded-full border-2 border-primary shadow-lg transition-transform group-hover:scale-110">
                <h2 class="text-3xl font-800 tracking-tight">Wak Gacor <span class="text-primary">Store</span></h2>
            </div>

            <div class="hidden lg:flex items-center gap-4">
                <a href="../index.php" class="nav-link-custom">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="products.php" class="nav-link-custom">
                    <i class="fas fa-box mr-2"></i>Produk
                </a>
                <a href="login.php" class="nav-link-active">
                    <i class="fas fa-user mr-2"></i>Masuk
                </a>
            </div>
            
            <button class="lg:hidden p-3 text-secondary hover:text-primary transition-colors bg-gray-50 rounded-2xl">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-32 px-6">
        <div class="w-full max-w-xl bg-white rounded-[60px] p-12 md:p-20 shadow-2xl border border-gray-100 relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-[100px] -z-10 group-hover:bg-primary/10 transition-all duration-1000"></div>
            
            <div class="text-center mb-16 space-y-4">
                <div class="w-24 h-24 bg-primary/10 rounded-[30px] flex items-center justify-center mx-auto mb-8 text-primary shadow-inner border border-primary/20">
                    <i class="fas fa-fingerprint text-4xl"></i>
                </div>
                <span class="text-primary font-900 text-[10px] uppercase tracking-[4px]">Otentikasi Aman</span>
                <h1 class="text-4xl md:text-5xl font-900 text-secondary tracking-tighter">Gerbang <span class="text-primary">Akses</span></h1>
                <p class="text-gray-400 text-lg italic font-light leading-relaxed">"Satu langkah menuju kurasi teknologi masa depan."</p>
            </div>

            <form action="../api/user/user_auth.php" method="POST" class="space-y-10">
                <input type="hidden" value="login" name="action">

                <div class="space-y-4">
                    <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Identitas Digital</label>
                    <div class="relative group">
                        <input type="email" name="email" 
                            class="w-full bg-gray-50 border border-gray-100 rounded-[25px] px-14 py-5 outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all font-medium text-lg text-secondary"
                            placeholder="nama@perusahaan.com" required>
                        <i class="fas fa-at absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-all group-focus-within:scale-110"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Kunci Rahasia</label>
                    <div class="relative group">
                        <input type="password" name="password" 
                            class="w-full bg-gray-50 border border-gray-100 rounded-[25px] px-14 py-5 outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all font-medium text-lg text-secondary"
                            placeholder="••••••••" required>
                        <i class="fas fa-shield-alt absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-all group-focus-within:scale-110"></i>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-6 bg-secondary text-primary font-900 rounded-[25px] transition-all duration-700 hover:bg-black hover:scale-[1.03] active:scale-95 shadow-2xl uppercase tracking-[3px] text-sm">
                        Buka Akses <i class="fas fa-chevron-right ml-2 text-[10px]"></i>
                    </button>
                </div>

                <div class="pt-10 text-center border-t border-gray-50">
                    <p class="text-gray-400 font-medium">
                        Baru di Wak Gacor? 
                        <a href="signup.php" class="text-secondary font-900 hover:text-primary transition-all ml-2 underline underline-offset-8 decoration-primary/30 hover:decoration-primary">Bangun Akun Sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-32 pb-16 border-t border-white/5 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[150px] -z-10"></div>
        <div class="container mx-auto px-6 md:px-12 lg:px-20 text-center">
            <div class="flex flex-col items-center gap-10 mb-20">
                <img src="../assets/images/wakgacor.jpeg" alt="Logo" class="w-20 h-20 rounded-full border-2 border-primary shadow-2xl">
                <div class="space-y-4">
                    <h5 class="text-4xl font-900 tracking-tighter">Wak Gacor <span class="text-primary">Store</span></h5>
                    <p class="text-gray-400 max-w-xl mx-auto text-lg leading-loose">
                        Platform kurasi teknologi paling prestisius di Indonesia. Bergabunglah dengan komunitas inovator masa depan.
                    </p>
                </div>
                <div class="flex gap-6">
                    <a href="https://wa.me/6285874088612?text=halo%20min,%20saya%20mau%20tanya%20%22product%22%20apakah%20tersedia?" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 shadow-xl" title="WhatsApp">
                        <i class="fab fa-whatsapp text-2xl"></i>
                    </a>
                    <a href="https://www.instagram.com/wakgacorstore/" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 shadow-xl" title="Instagram">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="https://t.me/6285874088612" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 shadow-xl" title="Telegram">
                        <i class="fab fa-telegram-plane text-2xl"></i>
                    </a>
                </div>
            </div>
            
            <div class="flex flex-wrap justify-center gap-12 mb-20">
                <a href="../index.php" class="text-gray-400 hover:text-primary transition-all font-900 uppercase tracking-[3px] text-xs">Beranda</a>
                <a href="products.php" class="text-gray-400 hover:text-primary transition-all font-900 uppercase tracking-[3px] text-xs">Katalog</a>
                <a href="contact.php" class="text-gray-400 hover:text-primary transition-all font-900 uppercase tracking-[3px] text-xs">Kontak</a>
                <a href="login.php" class="text-gray-400 hover:text-primary transition-all font-900 uppercase tracking-[3px] text-xs">Login</a>
            </div>

            <div class="pt-16 border-t border-white/5">
                <p class="text-gray-500 text-sm font-medium tracking-wide">
                    &copy; 2025 Wak Gacor Store. Excellence in Every Pixel.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>