<?php
session_start();

require "includes/db.php";
require "api/cart/cart_functions.php";

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$user_first_name = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : '';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $cart_items = getCartItems($pdo, $user_id);
    $cart_summary = getCartSummary($pdo, $user_id);
}

// Get current page to set active navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wak Gacor Store - Destinasi Teknologi Anda</title>

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
                    },
                    animation: {
                        'fade-in-left': 'fadeInLeft 1s ease-out',
                        'fade-in-up': 'fadeInUp 1s ease-out',
                        'zoom-in': 'zoomIn 1s ease-out',
                        'pulse-gold': 'pulseGold 2s infinite',
                    },
                    keyframes: {
                        fadeInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        zoomIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        pulseGold: {
                            '0%, 100%': { boxShadow: '0 0 0 0 rgba(212, 175, 55, 0.4)' },
                            '70%': { boxShadow: '0 0 0 15px rgba(212, 175, 55, 0)' },
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS (minimal for specific needs) -->
    <style type="text/tailwindcss">
        @layer components {
            .nav-link-custom {
                @apply flex items-center px-4 py-2 rounded-xl transition-all duration-300 font-semibold text-secondary hover:bg-secondary hover:text-primary;
            }
            .nav-link-active {
                @apply flex items-center px-4 py-2 rounded-xl bg-secondary text-primary font-bold;
            }
            .btn-premium {
                @apply inline-flex items-center gap-2 px-8 py-4 bg-primary text-secondary font-bold rounded-full transition-all duration-500 shadow-lg hover:shadow-primary/30 hover:-translate-y-1 hover:bg-white;
            }
        }
    </style>
</head>

<body class="font-outfit bg-[#F8F9FA] text-secondary">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100/50">
        <nav class="container mx-auto px-6 md:px-12 lg:px-20 py-6 flex items-center justify-between">
            <!-- Logo and Brand -->
            <div class="flex items-center gap-4 group cursor-pointer">
                <img src="assets/images/wakgacor.jpeg" alt="Logo Wak Gacor" class="w-14 h-14 rounded-full border-2 border-primary shadow-lg transition-transform group-hover:scale-110">
                <h2 class="text-3xl font-800 tracking-tight">Wak Gacor <span class="text-primary">Store</span></h2>
            </div>

            <!-- Navigation Menu (Desktop) -->
            <div class="hidden lg:flex items-center gap-4">
                <a href="index.php" class="<?= $current_page == 'index.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="user/products.php" class="<?= $current_page == 'products.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                    <i class="fas fa-box mr-2"></i>Produk
                </a>

                <?php if ($is_logged_in): ?>
                    <a href="user/cart.php" class="<?= $current_page == 'cart.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                        <?php if ($cart_summary['total_quantity'] > 0): ?>
                            <span class="ml-2 px-2.5 py-1 bg-red-500 text-white text-[10px] rounded-full animate-bounce"><?= $cart_summary['total_quantity'] ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="user/profile.php" class="<?= $current_page == 'profile.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                        <i class="fas fa-user-circle mr-2"></i>Profil
                    </a>
                    <a href="user/logout.php" class="nav-link-custom text-red-500 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </a>
                <?php else: ?>
                    <a href="user/contact.php" class="<?= $current_page == 'contact.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                        <i class="fas fa-envelope mr-2"></i>Kontak
                    </a>
                    <a href="user/login.php" class="btn-premium !py-3 !px-8">
                        <i class="fas fa-user mr-2"></i>Masuk
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden p-3 text-secondary hover:text-primary transition-colors bg-gray-50 rounded-2xl">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-32 md:py-48 lg:py-60">
            <div class="container mx-auto px-6 md:px-12 lg:px-20">
                <div class="flex flex-col lg:flex-row items-center gap-20 lg:gap-32">
                    <div class="lg:w-1/2 space-y-12">
                        <div class="space-y-6">
                            <h1 class="text-6xl lg:text-8xl font-900 leading-[1.1] tracking-tight animate-fade-in-left">
                                Level Up Your<br>
                                <span class="text-primary">Digital Life</span>
                            </h1>
                            <p class="text-xl text-gray-500 leading-loose max-w-xl animate-fade-in-left [animation-delay:200ms]">
                                Temukan ekosistem teknologi terbaik di Wak Gacor Store. Kami mengkurasi perangkat premium yang mendefinisikan masa depan produktivitas dan hiburan Anda.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-6 animate-fade-in-up [animation-delay:400ms]">
                            <a href="user/products.php" class="btn-premium !px-12 !py-5 text-lg">
                                <i class="fas fa-shopping-cart"></i> Jelajahi Koleksi
                            </a>
                            <a href="user/contact.php" class="px-10 py-5 border-2 border-secondary rounded-full font-bold transition-all hover:bg-secondary hover:text-white flex items-center gap-2">
                                Hubungi Kami <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                    <div class="lg:w-1/2 flex justify-center animate-zoom-in [animation-delay:200ms]">
                        <div class="relative group">
                            <div class="absolute -inset-10 bg-primary/20 rounded-[60px] blur-3xl group-hover:bg-primary/30 transition-all duration-700"></div>
                            <img src="assets/images/wakgacor.jpeg" alt="Wak Gacor" class="relative w-80 h-80 lg:w-[550px] lg:h-[550px] rounded-[60px] object-cover border-[12px] border-white shadow-2xl transition-transform duration-1000 group-hover:scale-105 group-hover:rotate-2">
                            <div class="absolute -bottom-10 -right-10 bg-secondary p-8 rounded-[40px] shadow-2xl border-4 border-primary text-center animate-bounce">
                                <p class="text-primary font-900 text-2xl tracking-tighter">100% PREMIUM</p>
                                <p class="text-white text-xs font-bold opacity-60 uppercase tracking-widest">Garansi Terjamin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Categories -->
        <section class="py-32 md:py-48 bg-white rounded-[100px] shadow-inner relative z-10">
            <div class="container mx-auto px-6 md:px-12 lg:px-20">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-24 gap-8">
                    <div class="space-y-4 max-w-2xl text-center md:text-left">
                        <span class="text-primary font-900 text-sm uppercase tracking-[4px]">Koleksi Kami</span>
                        <h2 class="text-5xl md:text-6xl font-900 text-secondary tracking-tight">Kategori Terpopuler</h2>
                        <p class="text-gray-400 text-lg leading-relaxed">Ekspresikan diri Anda melalui teknologi. Pilih kategori yang sesuai dengan gaya hidup digital Anda.</p>
                    </div>
                    <a href="user/products.php" class="text-secondary font-bold hover:text-primary transition-all flex items-center gap-2 group whitespace-nowrap">
                        Lihat Semua Produk <i class="fas fa-long-arrow-alt-right group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                    <!-- Computers -->
                    <a href="user/products.php" class="group">
                        <div class="h-full bg-gray-50 p-10 rounded-[50px] border-2 border-transparent transition-all duration-700 hover:bg-secondary hover:border-primary hover:-translate-y-4 group-hover:shadow-3xl">
                            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mb-10 shadow-lg group-hover:bg-primary transition-all group-hover:scale-110 group-hover:rotate-6">
                                <i class="fas fa-desktop text-4xl text-primary group-hover:text-secondary"></i>
                            </div>
                            <h5 class="text-2xl font-800 mb-4 group-hover:text-white transition-colors">Komputer</h5>
                            <p class="text-gray-500 leading-loose group-hover:text-gray-400 transition-colors">Perangkat tangguh untuk performa tanpa batas.</p>
                        </div>
                    </a>

                    <!-- Laptops -->
                    <a href="user/products.php" class="group">
                        <div class="h-full bg-gray-50 p-10 rounded-[50px] border-2 border-transparent transition-all duration-700 hover:bg-secondary hover:border-primary hover:-translate-y-4 group-hover:shadow-3xl">
                            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mb-10 shadow-lg group-hover:bg-primary transition-all group-hover:scale-110 group-hover:rotate-6">
                                <i class="fas fa-laptop text-4xl text-primary group-hover:text-secondary"></i>
                            </div>
                            <h5 class="text-2xl font-800 mb-4 group-hover:text-white transition-colors">Laptop</h5>
                            <p class="text-gray-500 leading-loose group-hover:text-gray-400 transition-colors">Mobilitas tinggi dengan estetika yang elegan.</p>
                        </div>
                    </a>

                    <!-- Phones -->
                    <a href="user/products.php" class="group">
                        <div class="h-full bg-gray-50 p-10 rounded-[50px] border-2 border-transparent transition-all duration-700 hover:bg-secondary hover:border-primary hover:-translate-y-4 group-hover:shadow-3xl">
                            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mb-10 shadow-lg group-hover:bg-primary transition-all group-hover:scale-110 group-hover:rotate-6">
                                <i class="fas fa-mobile-alt text-4xl text-primary group-hover:text-secondary"></i>
                            </div>
                            <h5 class="text-2xl font-800 mb-4 group-hover:text-white transition-colors">Smartphone</h5>
                            <p class="text-gray-500 leading-loose group-hover:text-gray-400 transition-colors">Konektivitas tanpa henti di genggaman Anda.</p>
                        </div>
                    </a>

                    <!-- Accessories -->
                    <a href="user/products.php" class="group">
                        <div class="h-full bg-gray-50 p-10 rounded-[50px] border-2 border-transparent transition-all duration-700 hover:bg-secondary hover:border-primary hover:-translate-y-4 group-hover:shadow-3xl">
                            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mb-10 shadow-lg group-hover:bg-primary transition-all group-hover:scale-110 group-hover:rotate-6">
                                <i class="fas fa-headphones text-4xl text-primary group-hover:text-secondary"></i>
                            </div>
                            <h5 class="text-2xl font-800 mb-4 group-hover:text-white transition-colors">Aksesori</h5>
                            <p class="text-gray-500 leading-loose group-hover:text-gray-400 transition-colors">Pelengkap sempurna untuk setup digital Anda.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Store Perks -->
        <section class="py-32 md:py-48 bg-secondary -mt-24 pt-48">
            <div class="container mx-auto px-6 md:px-12 lg:px-20">
                <div class="text-center mb-24 space-y-6">
                    <span class="text-primary font-900 text-sm uppercase tracking-[4px]">Kenapa Kami?</span>
                    <h2 class="text-5xl md:text-6xl font-900 text-white tracking-tight">Standar Baru Belanja Gadget</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto text-lg leading-loose">Lebih dari sekadar toko, kami adalah partner terpercaya untuk setiap langkah perjalanan digital Anda.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-20">
                    <!-- Authentic Products -->
                    <div class="text-center group">
                        <div class="w-24 h-24 bg-primary/10 rounded-[30px] flex items-center justify-center mx-auto mb-8 border border-primary/20 transition-all duration-500 group-hover:bg-primary group-hover:rotate-12 group-hover:scale-110">
                            <i class="fas fa-shield-alt text-4xl text-primary group-hover:text-secondary transition-colors"></i>
                        </div>
                        <h5 class="text-2xl font-800 text-white mb-4">100% Authentic</h5>
                        <p class="text-gray-500 leading-relaxed max-w-[250px] mx-auto">Kurasi produk asli dengan jaminan keaslian mutlak.</p>
                    </div>

                    <!-- Fast Shipping -->
                    <div class="text-center group">
                        <div class="w-24 h-24 bg-primary/10 rounded-[30px] flex items-center justify-center mx-auto mb-8 border border-primary/20 transition-all duration-500 group-hover:bg-primary group-hover:-rotate-12 group-hover:scale-110">
                            <i class="fas fa-shipping-fast text-4xl text-primary group-hover:text-secondary transition-colors"></i>
                        </div>
                        <h5 class="text-2xl font-800 text-white mb-4">Express Logistik</h5>
                        <p class="text-gray-500 leading-relaxed max-w-[250px] mx-auto">Pengiriman prioritas yang aman sampai di depan pintu Anda.</p>
                    </div>

                    <!-- Customer Support -->
                    <div class="text-center group">
                        <div class="w-24 h-24 bg-primary/10 rounded-[30px] flex items-center justify-center mx-auto mb-8 border border-primary/20 transition-all duration-500 group-hover:bg-primary group-hover:rotate-12 group-hover:scale-110">
                            <i class="fas fa-headset text-4xl text-primary group-hover:text-secondary transition-colors"></i>
                        </div>
                        <h5 class="text-2xl font-800 text-white mb-4">Pakar Teknologi</h5>
                        <p class="text-gray-500 leading-relaxed max-w-[250px] mx-auto">Konsultasi gratis dengan tim ahli kami kapan saja.</p>
                    </div>

                    <!-- Money Back -->
                    <div class="text-center group">
                        <div class="w-24 h-24 bg-primary/10 rounded-[30px] flex items-center justify-center mx-auto mb-8 border border-primary/20 transition-all duration-500 group-hover:bg-primary group-hover:-rotate-12 group-hover:scale-110">
                            <i class="fas fa-money-bill-wave text-4xl text-primary group-hover:text-secondary transition-colors"></i>
                        </div>
                        <h5 class="text-2xl font-800 text-white mb-4">Proteksi Pembelian</h5>
                        <p class="text-gray-500 leading-relaxed max-w-[250px] mx-auto">Keamanan transaksi dan jaminan pengembalian dana.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-32 pb-16 border-t border-white/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-[150px] -z-10"></div>
        <div class="container mx-auto px-6 md:px-12 lg:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-24 mb-24">
                <!-- Brand Section -->
                <div class="col-span-1 lg:col-span-2 space-y-10">
                    <div class="flex items-center gap-5">
                        <img src="assets/images/wakgacor.jpeg" alt="Logo Wak Gacor" class="w-16 h-16 rounded-full border-2 border-primary shadow-xl">
                        <h5 class="text-4xl font-900 tracking-tighter">Wak Gacor <span class="text-primary">Store</span></h5>
                    </div>
                    <p class="text-gray-400 max-w-xl leading-loose text-lg">
                        Mendefinisikan ulang pengalaman belanja teknologi di Indonesia. Kami menghadirkan kurasi produk tercanggih dengan standar layanan yang melampaui ekspektasi.
                    </p>
                    <div class="flex gap-6">
                        <a href="https://wa.me/6285874088612?text=halo%20min,%20saya%20mau%20tanya%20%22product%22%20apakah%20tersedia?" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 group shadow-lg" title="WhatsApp">
                            <i class="fab fa-whatsapp text-2xl"></i>
                        </a>
                        <a href="https://www.instagram.com/wakgacorstore/" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 group shadow-lg" title="Instagram">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="https://t.me/6285874088612" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 group shadow-lg" title="Telegram">
                            <i class="fab fa-telegram-plane text-2xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="space-y-10">
                    <h6 class="text-sm uppercase tracking-[4px] text-primary font-900">Eksplorasi</h6>
                    <ul class="space-y-6">
                        <li><a href="index.php" class="text-gray-400 hover:text-primary transition-all flex items-center gap-3 group text-lg"><span class="w-2 h-2 bg-primary/20 rounded-full group-hover:scale-150 transition-transform"></span> Beranda</a></li>
                        <li><a href="user/products.php" class="text-gray-400 hover:text-primary transition-all flex items-center gap-3 group text-lg"><span class="w-2 h-2 bg-primary/20 rounded-full group-hover:scale-150 transition-transform"></span> Produk</a></li>
                        <li><a href="user/contact.php" class="text-gray-400 hover:text-primary transition-all flex items-center gap-3 group text-lg"><span class="w-2 h-2 bg-primary/20 rounded-full group-hover:scale-150 transition-transform"></span> Kontak</a></li>
                        <li><a href="user/login.php" class="text-gray-400 hover:text-primary transition-all flex items-center gap-3 group text-lg"><span class="w-2 h-2 bg-primary/20 rounded-full group-hover:scale-150 transition-transform"></span> Akun Saya</a></li>
                    </ul>
                </div>

                <!-- HQ -->
                <div class="space-y-10">
                    <h6 class="text-sm uppercase tracking-[4px] text-primary font-900">Markas Besar</h6>
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-primary flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <p class="text-gray-400 leading-relaxed">
                                Jl. Gacor Raya No. 123<br>
                                Jakarta Selatan, Indonesia
                            </p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center text-primary flex-shrink-0">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <p class="text-gray-400 leading-relaxed">
                                +62 21 1234 5678<br>
                                info@wakgacor.store
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-16 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-10">
                <p class="text-gray-500 text-sm font-medium">
                    &copy; 2025 Wak Gacor Store. All Rights Reserved. Crafted for Excellence.
                </p>
                <div class="flex gap-10 text-sm font-bold uppercase tracking-widest text-gray-500">
                    <a href="#" class="hover:text-primary transition-colors">Privacy</a>
                    <a href="#" class="hover:text-primary transition-colors">Terms</a>
                    <a href="#" class="hover:text-primary transition-colors">FAQ</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>