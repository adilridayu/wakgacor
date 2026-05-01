<?php
session_start();
require "../includes/db.php";
require "../api/cart/cart_functions.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$is_logged_in = isset($_SESSION['user_id']);
$user_first_name = $_SESSION['user_first_name'] ?? 'User';
$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

$cart_items = getCartItems($pdo, $user_id);
$cart_summary = getCartSummary($pdo, $user_id);

$subtotal = $cart_summary['total_price'] ?? 0;
$tax = 0;
$shipping = 0;
$total = $subtotal + $tax + $shipping;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Wak Gacor Store</title>

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
                @apply inline-flex items-center gap-2 px-8 py-4 bg-primary text-secondary font-bold rounded-full transition-all duration-500 shadow-lg hover:shadow-primary/30 hover:-translate-y-1 hover:bg-white;
            }
            .cart-card {
                @apply bg-white rounded-[30px] p-6 mb-6 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6 transition-all hover:shadow-md;
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
                <a href="../index.php" class="<?= $current_page == 'index.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="products.php" class="<?= $current_page == 'products.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                    <i class="fas fa-box mr-2"></i>Produk
                </a>
                <a href="cart.php" class="<?= $current_page == 'cart.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                    <?php if ($cart_summary['total_quantity'] > 0): ?>
                        <span class="ml-2 px-2.5 py-1 bg-red-500 text-white text-[10px] rounded-full animate-bounce"><?= $cart_summary['total_quantity'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="profile.php" class="<?= $current_page == 'profile.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
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
            <div class="flex flex-col lg:flex-row justify-between items-end gap-10 mb-20">
                <div class="space-y-4">
                    <span class="text-primary font-900 text-sm uppercase tracking-[4px]">Finalisasi Belanja</span>
                    <h1 class="text-5xl md:text-6xl font-900 text-secondary tracking-tight">Tas <span class="text-primary">Belanja</span></h1>
                    <p class="text-gray-400 text-lg max-w-xl leading-loose italic">Pastikan setiap perangkat yang Anda pilih siap untuk meningkatkan produktivitas digital Anda.</p>
                </div>
                <a href="products.php" class="px-8 py-4 bg-gray-50 text-secondary hover:text-primary font-900 transition-all flex items-center gap-3 rounded-2xl border border-gray-100 shadow-sm uppercase tracking-widest text-xs">
                    <i class="fas fa-long-arrow-alt-left"></i> Kembali ke Katalog
                </a>
            </div>

            <?php if (empty($cart_items)): ?>
                <div class="bg-white rounded-[60px] py-32 text-center shadow-2xl border border-gray-100 max-w-3xl mx-auto relative overflow-hidden group">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl transition-all group-hover:scale-150"></div>
                    <div class="w-40 h-40 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-10 text-gray-200 shadow-inner group-hover:scale-110 transition-transform duration-700">
                        <i class="fas fa-shopping-basket text-7xl"></i>
                    </div>
                    <h2 class="text-4xl font-900 text-secondary mb-4">Keranjang Anda Kosong</h2>
                    <p class="text-gray-400 mb-12 max-w-sm mx-auto leading-loose italic text-lg">"Investasi teknologi terbaik Anda dimulai dengan satu klik pertama."</p>
                    <a href="products.php" class="btn-premium !px-12 !py-6 text-sm tracking-widest uppercase">
                        Temukan Perangkat <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="flex flex-col lg:flex-row gap-20">
                    <!-- Cart Items -->
                    <div class="lg:w-2/3 space-y-10">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-card !p-8 md:!p-10 group relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-primary/20 transition-all group-hover:bg-primary group-hover:w-2"></div>
                                <!-- Product Image -->
                                <div class="w-full md:w-44 aspect-square rounded-[30px] overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0 shadow-inner">
                                    <?php if ($item['cover_image']): ?>
                                        <img src="../assets/uploads/<?= htmlspecialchars($item['cover_image']) ?>"
                                            alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                                    <?php else: ?>
                                        <img src="../assets/placeholder-product.png"
                                            alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-full h-full object-cover opacity-50">
                                    <?php endif; ?>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-grow space-y-6 py-2">
                                    <div>
                                        <p class="text-[10px] font-900 text-primary uppercase tracking-[3px] mb-2">
                                            <?= htmlspecialchars($item['subcategory_name'] ?? 'Infrastruktur Digital') ?>
                                        </p>
                                        <h5 class="text-2xl font-900 text-secondary group-hover:text-primary transition-colors"><?= htmlspecialchars($item['product_name']) ?></h5>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-8">
                                        <!-- Quantity Controller -->
                                        <form method="POST" action="../api/cart/cart_handler.php" class="flex items-center bg-gray-50 rounded-2xl px-3 py-2 border border-gray-100 shadow-inner group/qty">
                                            <input type="hidden" name="action" value="update_quantity">
                                            <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                            <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-primary q-btn-minus transition-all hover:scale-125"><i class="fas fa-minus text-xs"></i></button>
                                            <input type="number" name="quantity" class="w-14 bg-transparent text-center font-900 text-secondary outline-none q-input text-xl"
                                                value="<?= $item['quantity'] ?>" min="1" max="99">
                                            <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-primary q-btn-plus transition-all hover:scale-125"><i class="fas fa-plus text-xs"></i></button>
                                            <button type="submit" class="ml-4 bg-secondary text-primary px-5 py-2 rounded-xl text-[10px] font-900 hover:bg-black transition-all uppercase tracking-widest shadow-lg">Simpan</button>
                                        </form>
                                        
                                        <!-- Remove Button -->
                                        <form method="POST" action="../api/cart/cart_handler.php">
                                            <input type="hidden" name="action" value="remove_item">
                                            <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                            <button type="submit" class="text-gray-300 hover:text-red-500 font-900 text-[10px] flex items-center gap-2 transition-all uppercase tracking-widest hover:-translate-y-0.5">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Price Info -->
                                <div class="w-full md:w-52 text-left md:text-right space-y-2 py-2">
                                    <p class="text-[10px] font-900 text-gray-400 uppercase tracking-widest">Total Baris</p>
                                    <p class="text-3xl font-900 text-secondary tracking-tighter">Rp <?= number_format($item['price'] * $item['quantity'] * 15000, 0, ',', '.') ?></p>
                                    <p class="text-xs text-primary font-bold italic tracking-wide">@ Rp <?= number_format($item['price'] * 15000, 0, ',', '.') ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:w-1/3">
                        <div class="bg-secondary rounded-[50px] p-10 md:p-12 text-white shadow-3xl sticky top-32 overflow-hidden relative group">
                            <!-- Background Decoration -->
                            <div class="absolute -top-24 -right-24 w-60 h-60 bg-primary/10 rounded-full blur-[80px] group-hover:bg-primary/20 transition-all duration-1000"></div>
                            
                            <h4 class="text-3xl font-900 mb-12 tracking-tight">Kalkulasi <span class="text-primary">Akhir</span></h4>

                            <div class="space-y-6 mb-12">
                                <div class="flex justify-between items-center text-gray-400">
                                    <span class="font-bold text-xs uppercase tracking-widest">Harga Dasar</span>
                                    <span class="font-900 text-white text-lg tracking-tighter">Rp <?= number_format($subtotal * 15000, 0, ',', '.') ?></span>
                                </div>
                                <div class="flex justify-between items-center text-gray-400">
                                    <span class="font-bold text-xs uppercase tracking-widest">Logistik Prioritas</span>
                                    <span class="text-primary font-900 text-sm italic uppercase tracking-[3px]">Complimentary</span>
                                </div>
                                <div class="flex justify-between items-center text-gray-400">
                                    <span class="font-bold text-xs uppercase tracking-widest">Pajak Pertambahan</span>
                                    <span class="text-white font-900 text-lg tracking-tighter">Rp 0</span>
                                </div>
                            </div>

                            <div class="pt-10 border-t border-white/10 mb-12">
                                <div class="flex flex-col gap-3">
                                    <span class="text-gray-400 font-900 uppercase tracking-[4px] text-[10px]">Total Investasi</span>
                                    <span class="text-5xl font-900 text-primary tracking-tighter">Rp <?= number_format($total * 15000, 0, ',', '.') ?></span>
                                </div>
                            </div>

                            <button onclick="alert('Gateway pembayaran sedang dipersiapkan untuk Wak Gacor Store!')" class="w-full py-6 bg-primary text-secondary font-900 rounded-[25px] transition-all duration-700 hover:bg-white hover:scale-[1.05] active:scale-95 shadow-2xl shadow-primary/20 mb-6 uppercase tracking-[3px] text-sm">
                                Amankan Pesanan <i class="fas fa-lock ml-2 text-xs"></i>
                            </button>
                            
                            <p class="text-center text-[10px] text-gray-500 font-medium leading-relaxed italic">
                                <i class="fas fa-shield-alt mr-2 text-primary"></i> 256-bit SSL Encrypted Secure Checkout
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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

    <script>
        document.querySelectorAll('.q-btn-minus').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.parentElement.querySelector('.q-input');
                if(input.value > 1) input.value = parseInt(input.value) - 1;
            });
        });
        document.querySelectorAll('.q-btn-plus').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.parentElement.querySelector('.q-input');
                if(input.value < 99) input.value = parseInt(input.value) + 1;
            });
        });
    </script>
</body>
</html>
</html>