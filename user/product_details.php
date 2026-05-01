<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$user_first_name = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : '';
// Get current page to set active navigation
$current_page = basename($_SERVER['PHP_SELF']);

require "../includes/db.php";
require "../api/cart/cart_functions.php";

// Initialize cart variables
$cart_items = [];
$cart_summary = ['total_quantity' => 0, 'total_price' => 0];

// Only get cart data if user is logged in
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $cart_items = getCartItems($pdo, $user_id);
    $cart_summary = getCartSummary($pdo, $user_id);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Get product details
$product_sql = "SELECT 
    products.id,
    products.name,
    products.description,
    products.price,
    products.cover_image,
    subcategories.name as subcategory_name,
    categories.name as category_name
FROM products
INNER JOIN product_subcategories ps ON products.id = ps.product_id
INNER JOIN subcategories ON ps.subcategory_id = subcategories.id
INNER JOIN category_subcategories cs ON subcategories.id = cs.subcategory_id
INNER JOIN categories ON cs.category_id = categories.id
WHERE products.id = :product_id";

$stmt = $pdo->prepare($product_sql);
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: products.php");
    exit();
}

// Get gallery images
$images_sql = "SELECT 
    images.id as image_id,
    images.image_url,
    images.filename,
    images.alt_text
FROM product_images
INNER JOIN images ON product_images.image_id = images.id
WHERE product_images.product_id = :product_id
ORDER BY images.id ASC";

$images_stmt = $pdo->prepare($images_sql);
$images_stmt->bindParam(':product_id', $product_id);
$images_stmt->execute();
$images = $images_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
            .thumbnail-img {
                @apply w-20 h-20 rounded-2xl object-cover cursor-pointer border-2 border-transparent hover:border-primary transition-all flex-shrink-0;
            }
            .thumbnail-img.active {
                @apply border-primary shadow-lg shadow-primary/20;
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

                <?php if ($is_logged_in): ?>
                    <a href="cart.php" class="nav-link-custom">
                        <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                        <?php if ($cart_summary['total_quantity'] > 0): ?>
                            <span class="ml-2 px-2.5 py-1 bg-red-500 text-white text-[10px] rounded-full animate-bounce"><?= $cart_summary['total_quantity'] ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="profile.php" class="nav-link-custom">
                        <i class="fas fa-user-circle mr-2"></i>Profil
                    </a>
                    <a href="logout.php" class="nav-link-custom text-red-500 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                    </a>
                <?php else: ?>
                    <a href="contact.php" class="nav-link-custom">
                        <i class="fas fa-envelope mr-2"></i>Kontak
                    </a>
                    <a href="login.php" class="btn-premium !py-3 !px-8">
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
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-4 text-[10px] font-900 uppercase tracking-[3px] text-gray-400 mb-16 overflow-x-auto whitespace-nowrap pb-4">
                <a href="../index.php" class="hover:text-primary transition-colors">Beranda</a>
                <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                <a href="products.php" class="hover:text-primary transition-colors">Katalog</a>
                <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
                <span class="text-secondary"><?= htmlspecialchars($product['name']) ?></span>
            </nav>

            <div class="bg-white rounded-[60px] p-12 md:p-24 shadow-2xl border border-gray-100 relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute -top-24 -right-24 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] -z-10"></div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-start">
                    <!-- Product Images -->
                    <div class="space-y-10 sticky top-32">
                        <div class="aspect-square rounded-[50px] overflow-hidden bg-gray-50 border border-gray-100 shadow-2xl group relative">
                            <?php if ($product['cover_image']): ?>
                                <img id="mainImage" src="../assets/uploads/<?= htmlspecialchars($product['cover_image']) ?>"
                                    alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            <?php else: ?>
                                <img id="mainImage" src="../assets/placeholder-product.png"
                                    alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover opacity-50">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>

                        <!-- Gallery Thumbnails -->
                        <?php if (!empty($images) || $product['cover_image']): ?>
                            <div class="flex gap-6 overflow-x-auto py-2 scrollbar-hide px-2">
                                <?php if ($product['cover_image']): ?>
                                    <img src="../assets/uploads/<?= htmlspecialchars($product['cover_image']) ?>"
                                        class="thumbnail-img active !w-24 !h-24 rounded-[24px]" onclick="changeImage(this.src, this)">
                                <?php endif; ?>
                                <?php foreach ($images as $img): ?>
                                    <img src="../assets/uploads/<?= htmlspecialchars($img['image_url']) ?>"
                                        class="thumbnail-img !w-24 !h-24 rounded-[24px]" onclick="changeImage(this.src, this)">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-12">
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <span class="px-6 py-2 bg-primary/10 text-primary text-[10px] font-900 rounded-full uppercase tracking-[3px] border border-primary/20 shadow-sm">
                                    <?= htmlspecialchars($product['category_name']) ?>
                                </span>
                                <span class="text-xs font-900 text-gray-300 uppercase tracking-widest italic">
                                    <?= htmlspecialchars($product['subcategory_name']) ?>
                                </span>
                            </div>
                            <h1 class="text-5xl md:text-6xl font-900 text-secondary tracking-tight leading-[1.1]">
                                <?= htmlspecialchars($product['name']) ?>
                            </h1>
                        </div>

                        <div class="space-y-2">
                            <p class="text-[10px] font-900 text-gray-400 uppercase tracking-[4px] ml-1">Investasi Digital</p>
                            <p class="text-6xl font-900 text-secondary tracking-tighter">
                                <span class="text-2xl font-800 text-primary -mr-1">Rp</span> <?= number_format($product['price'] * 15000, 0, ',', '.') ?>
                            </p>
                        </div>

                        <div class="space-y-6 border-t border-gray-100 pt-12">
                            <h5 class="text-xs font-900 text-gray-400 uppercase tracking-[4px] flex items-center gap-4">
                                <span class="w-12 h-px bg-primary/20"></span> Spesifikasi & Deskripsi
                            </h5>
                            <div class="text-gray-500 leading-loose text-xl font-light italic max-w-xl">
                                "<?= nl2br(htmlspecialchars($product['description'])) ?>"
                            </div>
                        </div>

                        <div class="pt-12 space-y-10">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-8">
                                <div class="flex items-center bg-gray-50 rounded-3xl px-4 py-3 border border-gray-100 shadow-inner min-w-[160px] justify-between">
                                    <button class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-primary transition-all hover:scale-125" onclick="updateQty(-1)">
                                        <i class="fas fa-minus text-sm"></i>
                                    </button>
                                    <input type="number" id="quantity" class="w-16 bg-transparent text-center font-900 text-secondary outline-none text-2xl" value="1" min="1" max="99" readonly>
                                    <button class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-primary transition-all hover:scale-125" onclick="updateQty(1)">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                </div>
                                <button class="flex-grow py-6 bg-secondary text-primary font-900 rounded-[30px] transition-all duration-700 hover:bg-black hover:scale-[1.05] shadow-2xl flex items-center justify-center gap-4 active:scale-95 group tracking-[2px] uppercase text-sm" onclick="addToCartWithQty(<?= $product['id'] ?>)">
                                    <i class="fas fa-shopping-bag text-xl group-hover:rotate-12 transition-transform"></i>
                                    Amankan Perangkat
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="p-6 rounded-[30px] bg-gray-50 border border-gray-100 text-center space-y-3 group hover:bg-white hover:border-primary transition-all duration-700 hover:shadow-xl">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto shadow-sm group-hover:bg-primary transition-colors">
                                        <i class="fas fa-shield-check text-primary group-hover:text-secondary text-xl"></i>
                                    </div>
                                    <p class="text-[10px] font-900 text-gray-400 uppercase tracking-widest">Garansi Resmi</p>
                                </div>
                                <div class="p-6 rounded-[30px] bg-gray-50 border border-gray-100 text-center space-y-3 group hover:bg-white hover:border-primary transition-all duration-700 hover:shadow-xl">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto shadow-sm group-hover:bg-primary transition-colors">
                                        <i class="fas fa-truck-fast text-primary group-hover:text-secondary text-xl"></i>
                                    </div>
                                    <p class="text-[10px] font-900 text-gray-400 uppercase tracking-widest">Express Ship</p>
                                </div>
                                <div class="p-6 rounded-[30px] bg-gray-50 border border-gray-100 text-center space-y-3 group hover:bg-white hover:border-primary transition-all duration-700 hover:shadow-xl">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto shadow-sm group-hover:bg-primary transition-colors">
                                        <i class="fas fa-gem text-primary group-hover:text-secondary text-xl"></i>
                                    </div>
                                    <p class="text-[10px] font-900 text-gray-400 uppercase tracking-widest">100% Authentic</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-32 pb-16 border-t border-white/5 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-[150px] -z-10"></div>
        <div class="container mx-auto px-6 md:px-12 lg:px-20 text-center">
            <div class="flex flex-col items-center gap-10 mb-20">
                <img src="../assets/images/wakgacor.jpeg" alt="Logo" class="w-20 h-20 rounded-full border-2 border-primary shadow-2xl">
                <div class="space-y-4">
                    <h5 class="text-4xl font-900 tracking-tighter">Wak Gacor <span class="text-primary">Store</span></h5>
                    <p class="text-gray-400 max-w-xl mx-auto text-lg leading-loose">
                        Menghadirkan kurasi teknologi terbaik untuk masa depan digital Anda. Kami bangga menjadi bagian dari setiap inovasi yang Anda bangun.
                    </p>
                </div>
                <div class="flex gap-6">
                    <a href="https://wa.me/6285874088612?text=halo%20min,%20saya%20mau%20tanya%20%22<?= urlencode($product['name']) ?>%22%20apakah%20tersedia?" target="_blank" class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center transition-all hover:bg-primary hover:text-secondary hover:-translate-y-2 shadow-xl" title="WhatsApp">
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
                <a href="contact.php" class="text-gray-400 hover:text-primary transition-all font-900 uppercase tracking-[3px] text-xs">Hubungi</a>
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
        function changeImage(src, thumb) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail-img').forEach(img => img.classList.remove('active'));
            thumb.classList.add('active');
        }

        function updateQty(delta) {
            const input = document.getElementById('quantity');
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            if (val > 99) val = 99;
            input.value = val;
        }

        function addToCartWithQty(productId) {
            const qty = document.getElementById('quantity').value;
            const formData = new FormData();
            formData.append('action', 'add_to_cart');
            formData.append('product_id', productId);
            formData.append('quantity', qty);

            const btn = document.querySelector('button[onclick*="addToCartWithQty"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>MEMPROSES...';
            btn.disabled = true;

            fetch('../api/cart/cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    window.location.href = 'cart.php?success=item_added';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                alert('Gagal menambahkan ke keranjang. Silakan coba lagi.');
            });
        }
    </script>
</body>
</html>

