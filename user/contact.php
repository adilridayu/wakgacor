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
    <title>Hubungi Kami - Wak Gacor Store</title>

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
            .input-field {
                @apply w-full bg-gray-50 border border-gray-100 rounded-2xl px-12 py-4 outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all;
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
                <a href="contact.php" class="nav-link-active">
                    <i class="fas fa-envelope mr-2"></i>Kontak
                </a>
                <?php if ($is_logged_in): ?>
                    <a href="profile.php" class="nav-link-custom">
                        <i class="fas fa-user-circle mr-2"></i>Profil
                    </a>
                <?php else: ?>
                    <a href="login.php" class="nav-link-custom">
                        <i class="fas fa-user mr-2"></i>Masuk
                    </a>
                <?php endif; ?>
            </div>
            
            <button class="lg:hidden p-3 text-secondary hover:text-primary transition-colors bg-gray-50 rounded-2xl">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="container mx-auto px-6 md:px-12 lg:px-20 py-24">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-24 space-y-6">
                    <div class="w-24 h-24 bg-primary/10 rounded-[30px] flex items-center justify-center mx-auto mb-10 text-primary shadow-inner border border-primary/20 animate-bounce">
                        <i class="fas fa-paper-plane text-3xl"></i>
                    </div>
                    <span class="text-primary font-900 text-sm uppercase tracking-[4px]">Pusat Bantuan</span>
                    <h1 class="text-5xl md:text-7xl font-900 text-secondary tracking-tight">Hubungi <span class="text-primary">Kami</span></h1>
                    <p class="text-gray-400 text-xl max-w-2xl mx-auto leading-loose italic font-light">
                        "Ada pertanyaan atau butuh panduan teknologi? Tim ahli kami siap mendampingi perjalanan digital Anda."
                    </p>
                </div>

                <div class="bg-white rounded-[60px] p-12 md:p-24 shadow-2xl border border-gray-100 relative overflow-hidden">
                    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-[100px] -z-10"></div>
                    
                    <form action="../api/contact/contact_handler.php" method="POST" class="space-y-12">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Nama Depan</label>
                                <div class="relative group">
                                    <input type="text" name="first_name" class="input-field !pl-14" placeholder="Ketik nama depan..." required>
                                    <i class="fas fa-user absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-all group-focus-within:scale-110"></i>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Nama Belakang</label>
                                <div class="relative group">
                                    <input type="text" name="last_name" class="input-field !pl-14" placeholder="Ketik nama belakang..." required>
                                    <i class="fas fa-signature absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-all group-focus-within:scale-110"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Alamat Email</label>
                                <div class="relative group">
                                    <input type="email" name="email" class="input-field !pl-14" placeholder="nama@perusahaan.com" required>
                                    <i class="fas fa-envelope absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-all group-focus-within:scale-110"></i>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Nomor Telepon</label>
                                <div class="relative group">
                                    <input type="tel" name="phone" class="input-field !pl-14" placeholder="0812-xxxx-xxxx">
                                    <i class="fas fa-phone-alt absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-all group-focus-within:scale-110"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[3px] ml-2">Pesan Anda</label>
                            <div class="relative group">
                                <textarea name="message" rows="6" 
                                    class="w-full bg-gray-50 border border-gray-100 rounded-[40px] px-8 py-8 outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all font-medium text-secondary text-lg" 
                                    placeholder="Ceritakan apa yang bisa kami bantu secara detail..." required></textarea>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-10 pt-10 border-t border-gray-50">
                            <div class="flex items-center gap-4 text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-primary border border-gray-100">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <p class="text-sm font-medium leading-relaxed">
                                    Respons cepat terjamin.<br>
                                    <span class="text-secondary font-bold">Rata-rata 2 jam kerja.</span>
                                </p>
                            </div>
                            <button type="submit" class="btn-premium !px-12 !py-6 w-full md:w-auto justify-center uppercase tracking-widest text-sm font-900">
                                Kirim Sekarang <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </form>
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