<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data from session - with better fallback handling
$user_first_name = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : 'User';
$user_full_name = isset($_SESSION['user_full_name']) ? $_SESSION['user_full_name'] : $user_first_name;
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$user_id = $_SESSION['user_id'];

require "../includes/db.php";
require "../api/cart/cart_functions.php";
$cart_items = getCartItems($pdo, $user_id);
$cart_summary = getCartSummary($pdo, $user_id);


// Debug: If full name is empty, construct it from first name
if (empty($user_full_name)) {
    $user_full_name = $user_first_name;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Wak Gacor Store</title>

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
            .action-card {
                @apply bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 flex flex-col items-center text-center transition-all duration-500 hover:shadow-xl hover:-translate-y-2 group;
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
                <a href="cart.php" class="nav-link-custom">
                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                    <?php if ($cart_summary['total_quantity'] > 0): ?>
                        <span class="ml-2 px-2.5 py-1 bg-red-500 text-white text-[10px] rounded-full animate-bounce"><?= $cart_summary['total_quantity'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="profile.php" class="nav-link-active">
                    <i class="fas fa-user-circle mr-2"></i>Profil
                </a>
                <a href="logout.php" class="nav-link-custom text-red-500 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                </a>
            </div>
            
            <button class="lg:hidden p-3 text-secondary hover:text-primary transition-colors bg-gray-50 rounded-2xl">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="container mx-auto px-6 md:px-12 lg:px-20 py-24">
            <!-- Profile Banner -->
            <div class="bg-secondary rounded-[60px] p-12 md:p-24 text-white shadow-3xl relative overflow-hidden mb-20 group">
                <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] group-hover:bg-primary/20 transition-all duration-1000"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-16">
                    <div class="w-40 h-40 md:w-56 md:h-56 rounded-[50px] bg-white/5 backdrop-blur-2xl border-2 border-primary/30 flex items-center justify-center relative shadow-3xl group-hover:scale-105 transition-transform duration-700">
                        <i class="fas fa-user text-8xl text-primary/80 group-hover:text-primary transition-colors"></i>
                        <div class="absolute -bottom-4 -right-4 w-14 h-14 bg-primary text-secondary rounded-[20px] flex items-center justify-center border-4 border-secondary shadow-2xl">
                            <i class="fas fa-shield-check text-xl"></i>
                        </div>
                    </div>
                    <div class="text-center md:text-left space-y-6 max-w-2xl">
                        <span class="text-primary font-900 text-sm uppercase tracking-[4px]">Member Eksklusif</span>
                        <h1 class="text-5xl md:text-7xl font-900 tracking-tighter leading-tight">Halo, <span class="text-primary"><?php echo htmlspecialchars($user_first_name); ?>!</span></h1>
                        <p class="text-gray-400 text-xl leading-loose italic font-light">
                            "Kendalikan ekosistem digital Anda dari satu pusat komando. Privasi dan performa adalah prioritas kami untuk Anda."
                        </p>
                        <div class="flex flex-wrap justify-center md:justify-start gap-6 pt-6">
                            <div class="px-6 py-3 bg-white/5 rounded-2xl border border-white/10 text-xs font-900 uppercase tracking-widest flex items-center gap-3 backdrop-blur-sm">
                                <i class="fas fa-envelope text-primary"></i> <?php echo htmlspecialchars($user_email); ?>
                            </div>
                            <div class="px-6 py-3 bg-white/5 rounded-2xl border border-white/10 text-xs font-900 uppercase tracking-widest flex items-center gap-3 backdrop-blur-sm">
                                <i class="fas fa-fingerprint text-primary"></i> ID #<?php echo str_pad($user_id, 6, '0', STR_PAD_LEFT); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-24">
                <!-- Account Info -->
                <div class="action-card !p-12 !rounded-[50px] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="w-20 h-20 bg-blue-50 rounded-[25px] flex items-center justify-center text-blue-500 mb-8 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 group-hover:rotate-12 shadow-sm">
                        <i class="fas fa-id-card-alt text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-900 text-secondary mb-4 tracking-tight">Data Personal</h4>
                    <p class="text-gray-400 text-lg mb-10 leading-relaxed font-medium italic">Amankan identitas digital dan konfigurasi alamat Anda.</p>
                    <button class="w-full py-4 border-2 border-gray-100 rounded-2xl font-900 text-gray-400 hover:border-primary hover:text-primary transition-all uppercase tracking-widest text-[10px]">Modifikasi Profil</button>
                </div>

                <!-- Cart -->
                <div class="action-card !p-12 !rounded-[50px] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="w-20 h-20 bg-amber-50 rounded-[25px] flex items-center justify-center text-amber-500 mb-8 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 group-hover:rotate-12 shadow-sm">
                        <i class="fas fa-shopping-bag text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-900 text-secondary mb-4 tracking-tight">Keranjang</h4>
                    <p class="text-gray-400 text-lg mb-10 leading-relaxed font-medium italic">Terdapat <?= $cart_summary['total_quantity'] ?> perangkat dalam daftar tunggu Anda.</p>
                    <a href="cart.php" class="w-full py-4 bg-secondary text-primary rounded-2xl font-900 hover:bg-black transition-all text-center uppercase tracking-widest text-[10px]">Tinjau Keranjang</a>
                </div>

                <!-- Orders -->
                <div class="action-card !p-12 !rounded-[50px] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="w-20 h-20 bg-emerald-50 rounded-[25px] flex items-center justify-center text-emerald-500 mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 group-hover:rotate-12 shadow-sm">
                        <i class="fas fa-box-open text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-900 text-secondary mb-4 tracking-tight">Logistik</h4>
                    <p class="text-gray-400 text-lg mb-10 leading-relaxed font-medium italic">Pantau koordinat pengiriman perangkat Anda secara real-time.</p>
                    <button class="w-full py-4 border-2 border-gray-100 rounded-2xl font-900 text-gray-400 hover:border-primary hover:text-primary transition-all uppercase tracking-widest text-[10px]">Lacak Jalur</button>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white rounded-[60px] p-12 md:p-24 shadow-2xl border border-gray-100 relative overflow-hidden">
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-[100px] -z-10"></div>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-8 mb-20 border-b border-gray-50 pb-12">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-[25px] flex items-center justify-center text-primary shadow-inner border border-primary/20">
                            <i class="fas fa-history text-2xl"></i>
                        </div>
                        <h3 class="text-4xl font-900 text-secondary tracking-tight">Arsip <span class="text-primary">Transaksi</span></h3>
                    </div>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Real-time Tracking</p>
                </div>

                <div class="flex flex-col items-center py-20 text-center space-y-10 group">
                    <div class="w-48 h-48 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 shadow-inner group-hover:scale-110 transition-transform duration-1000 relative">
                        <i class="fas fa-box-open text-7xl"></i>
                        <div class="absolute inset-0 bg-primary/5 rounded-full animate-ping"></div>
                    </div>
                    <div class="space-y-4 max-w-md">
                        <h4 class="text-3xl font-900 text-secondary">Belum Ada Riwayat</h4>
                        <p class="text-gray-400 text-xl leading-loose italic font-light">"Setiap inovasi besar dimulai dari satu langkah kecil. Mulailah perjalanan digital Anda sekarang."</p>
                    </div>
                    <a href="products.php" class="btn-premium !px-12 !py-6 text-sm tracking-widest uppercase">
                        Jelajahi Produk <i class="fas fa-shopping-bag text-sm ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-32 pb-16 border-t border-white/5 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[150px] -z-10"></div>
        <div class="container mx-auto px-6 md:px-12 lg:px-20 text-center">
            <div class="flex flex-col items-center gap-10 mb-20">
                <img src="../assets/images/wakgacor.jpeg" alt="Logo" class="w-20 h-20 rounded-full border-2 border-primary shadow-2xl">
                <div class="space-y-4">
                    <h5 class="text-4xl font-900 tracking-tighter">Wak Gacor <span class="text-primary">Store</span></h5>
                    <p class="text-gray-400 max-w-xl mx-auto text-lg leading-loose">
                        Partner strategis Anda dalam ekosistem digital. Kami melayani dengan integritas dan semangat inovasi yang tak henti.
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