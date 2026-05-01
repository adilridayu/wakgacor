<?php

session_start();
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$user_first_name = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : '';
// Get current page to set active navigation
$current_page = basename($_SERVER['PHP_SELF']);


require "../includes/db.php";
require "../api/cart/cart_functions.php";

$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
$user_first_name = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : '';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $cart_items = getCartItems($pdo, $user_id);
    $cart_summary = getCartSummary($pdo, $user_id);
    $subtotal = $cart_summary['total_price'] ? $cart_summary['total_price'] : 0;
}


// Calculate tax and shipping

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory_filter = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Build the main query
$sql = "SELECT 
    products.id,
    products.name,
    products.description,
    products.price,
    products.cover_image,
    subcategories.name as subcategory_name,
    subcategories.id as subcategory_id,
    categories.name as category_name,
    categories.id as category_id,
    COUNT(pi.image_id) as gallery_count
FROM products
INNER JOIN product_subcategories ps ON products.id = ps.product_id
INNER JOIN subcategories ON ps.subcategory_id = subcategories.id
INNER JOIN category_subcategories cs ON subcategories.id = cs.subcategory_id
INNER JOIN categories ON cs.category_id = categories.id
LEFT JOIN product_images pi ON products.id = pi.product_id
";

// Add WHERE conditions
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(products.name LIKE :search OR products.description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if (!empty($category_filter)) {
    $conditions[] = "categories.id = :category_id";
    $params[':category_id'] = $category_filter;
}

if (!empty($subcategory_filter)) {
    $conditions[] = "subcategories.id = :subcategory_id";
    $params[':subcategory_id'] = $subcategory_filter;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY products.id";

// Add sorting
switch ($sort_by) {
    case 'name_asc':
        $sql .= " ORDER BY products.name ASC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY products.name DESC";
        break;
    case 'price_asc':
        $sql .= " ORDER BY products.price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY products.price DESC";
        break;
    default:
        $sql .= " ORDER BY products.name ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all categories for filter
$categories_sql = "SELECT DISTINCT categories.id, categories.name FROM categories 
                   INNER JOIN category_subcategories cs ON categories.id = cs.category_id
                   INNER JOIN subcategories s ON cs.subcategory_id = s.id
                   INNER JOIN product_subcategories ps ON s.id = ps.subcategory_id
                   ORDER BY categories.name ASC";
$categories_stmt = $pdo->prepare($categories_sql);
$categories_stmt->execute();
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get subcategories for filter
$subcategories_sql = "SELECT DISTINCT subcategories.id, subcategories.name, categories.name as category_name 
                      FROM subcategories 
                      INNER JOIN category_subcategories cs ON subcategories.id = cs.subcategory_id
                      INNER JOIN categories ON cs.category_id = categories.id
                      INNER JOIN product_subcategories ps ON subcategories.id = ps.subcategory_id
                      ORDER BY categories.name ASC, subcategories.name ASC";
$subcategories_stmt = $pdo->prepare($subcategories_sql);
$subcategories_stmt->execute();
$subcategories = $subcategories_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Wak Gacor Store</title>

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
                @apply inline-flex items-center gap-2 px-6 py-3 bg-primary text-secondary font-bold rounded-full transition-all duration-500 shadow-lg hover:shadow-primary/30 hover:-translate-y-1 hover:bg-white;
            }
            .filter-card {
                @apply bg-white p-6 rounded-[30px] shadow-sm border border-gray-100 sticky top-28;
            }
            .product-card {
                @apply bg-white rounded-[30px] overflow-hidden border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 group;
            }
        }
    </style>
</head>

<body class="font-outfit bg-[#F8F9FA] text-secondary min-h-screen flex flex-column">
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

                <?php if ($is_logged_in): ?>
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
                <?php else: ?>
                    <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'nav-link-active' : 'nav-link-custom' ?>">
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
            <div class="mb-20 text-center md:text-left space-y-4">
                <span class="text-primary font-900 text-sm uppercase tracking-[4px]">Katalog Teknologi</span>
                <h1 class="text-5xl md:text-6xl font-900 text-secondary tracking-tight">Temukan <span class="text-primary">Gawai Impian</span></h1>
                <p class="text-gray-400 text-lg max-w-2xl leading-loose">Jelajahi koleksi perangkat premium kami yang dirancang untuk mendukung performa dan gaya hidup digital Anda.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-16">
                <!-- Sidebar Filters -->
                <aside class="lg:w-1/4">
                    <div class="filter-card p-8">
                        <div class="flex items-center gap-3 mb-10">
                            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-sliders-h text-primary"></i>
                            </div>
                            <h4 class="text-2xl font-800">Personalisasi</h4>
                        </div>

                        <form method="GET" action="products.php" id="filterForm" class="space-y-10">
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort_by) ?>">

                            <!-- Search Filter -->
                            <div class="space-y-4">
                                <label class="text-xs font-900 text-gray-400 uppercase tracking-widest ml-1">Cari Perangkat</label>
                                <div class="relative group">
                                    <input type="text" name="search" 
                                        class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all"
                                        value="<?= htmlspecialchars($search) ?>"
                                        placeholder="PC, Laptop, dll...">
                                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors"></i>
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="space-y-4">
                                <label class="text-xs font-900 text-gray-400 uppercase tracking-widest ml-1">Kategori Utama</label>
                                <div class="relative">
                                    <select name="category" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all appearance-none cursor-pointer font-bold text-secondary">
                                        <option value="">Semua Kategori</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= $category_filter == $category['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Subcategory Filter -->
                            <div class="space-y-4">
                                <label class="text-xs font-900 text-gray-400 uppercase tracking-widest ml-1">Subkategori</label>
                                <div class="relative">
                                    <select name="subcategory" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all appearance-none cursor-pointer font-bold text-secondary text-sm">
                                        <option value="">Semua Subkategori</option>
                                        <?php foreach ($subcategories as $subcategory): ?>
                                            <option value="<?= $subcategory['id'] ?>" <?= $subcategory_filter == $subcategory['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($subcategory['category_name']) ?> > <?= htmlspecialchars($subcategory['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 bg-secondary text-primary font-900 rounded-2xl hover:bg-black transition-all shadow-xl shadow-secondary/10 flex items-center justify-center gap-2 tracking-widest uppercase text-xs">
                                Terapkan Filter <i class="fas fa-chevron-right text-[10px]"></i>
                            </button>

                            <?php if ($search || $category_filter || $subcategory_filter): ?>
                                <a href="products.php" class="block w-full py-2 text-center text-red-500 font-bold hover:text-red-600 transition-colors text-xs uppercase tracking-widest">
                                    <i class="fas fa-undo-alt mr-1"></i> Reset Filter
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </aside>

                <!-- Main Products Content -->
                <div class="lg:w-3/4">
                    <!-- Sort Bar -->
                    <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 mb-12 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center text-secondary shadow-lg">
                                <i class="fas fa-th-large text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-900 text-gray-400 uppercase tracking-[2px]">Hasil Katalog</p>
                                <p class="text-2xl font-900 text-secondary"><?= count($products) ?> Perangkat</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100">
                            <label class="text-[10px] font-900 text-gray-400 uppercase tracking-[2px]">Urutkan:</label>
                            <select class="bg-transparent font-bold text-secondary outline-none cursor-pointer text-sm" onchange="changeSort(this.value)">
                                <option value="name_asc" <?= $sort_by == 'name_asc' ? 'selected' : '' ?>>Nama (A-Z)</option>
                                <option value="name_desc" <?= $sort_by == 'name_desc' ? 'selected' : '' ?>>Nama (Z-A)</option>
                                <option value="price_asc" <?= $sort_by == 'price_asc' ? 'selected' : '' ?>>Harga (Terendah)</option>
                                <option value="price_desc" <?= $sort_by == 'price_desc' ? 'selected' : '' ?>>Harga (Tertinggi)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <?php if (empty($products)): ?>
                        <div class="bg-white rounded-[50px] py-32 text-center shadow-sm border border-gray-100">
                            <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200">
                                <i class="fas fa-box-open text-6xl"></i>
                            </div>
                            <h3 class="text-3xl font-900 text-secondary mb-4">Maaf, Tidak Ada Hasil</h3>
                            <p class="text-gray-400 max-w-sm mx-auto leading-loose">Coba sesuaikan filter Anda atau cari dengan kata kunci lain.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-10">
                            <?php foreach ($products as $product): ?>
                                <div class="product-card group">
                                    <!-- Image Container -->
                                    <div class="relative aspect-square overflow-hidden bg-gray-50">
                                        <?php if ($product['cover_image']): ?>
                                            <img src="../assets/uploads/<?= htmlspecialchars($product['cover_image']) ?>"
                                                alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                                        <?php else: ?>
                                            <img src="../assets/placeholder-product.png"
                                                alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover" />
                                        <?php endif; ?>
                                        
                                        <!-- Badges -->
                                        <div class="absolute top-6 left-6">
                                            <?php if ($product['gallery_count'] > 0): ?>
                                                <span class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl text-[10px] font-900 text-secondary shadow-lg flex items-center gap-2 uppercase tracking-widest">
                                                    <i class="fas fa-images text-primary"></i> <?= $product['gallery_count'] ?> Galeri
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Quick View Overlay -->
                                        <div class="absolute inset-0 bg-secondary/60 backdrop-blur-md opacity-0 group-hover:opacity-100 transition-all duration-700 flex items-center justify-center">
                                            <button onclick="window.location.href='product_details.php?id=<?= $product['id'] ?>'" class="px-8 py-4 bg-primary text-secondary font-900 rounded-2xl transition-all hover:bg-white hover:scale-110 shadow-2xl uppercase text-xs tracking-widest">
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Info Container -->
                                    <div class="p-8">
                                        <div class="mb-6">
                                            <p class="text-[10px] font-900 text-primary uppercase tracking-[3px] mb-2">
                                                <?= htmlspecialchars($product['subcategory_name']) ?>
                                            </p>
                                            <h5 class="text-xl font-900 text-secondary truncate transition-colors group-hover:text-primary" title="<?= htmlspecialchars($product['name']) ?>">
                                                <?= htmlspecialchars($product['name']) ?>
                                            </h5>
                                        </div>
                                        
                                        <div class="flex items-end justify-between mb-8">
                                            <div class="space-y-1">
                                                <p class="text-[10px] text-gray-400 font-900 uppercase tracking-widest">Investasi</p>
                                                <p class="text-2xl font-900 text-secondary tracking-tighter">Rp <?= number_format($product['price'] * 15000, 0, ',', '.') ?></p>
                                            </div>
                                        </div>

                                        <button onclick="addToCart(<?= $product['id'] ?>)" class="w-full py-4 bg-gray-50 text-secondary font-900 rounded-2xl transition-all duration-500 hover:bg-secondary hover:text-primary hover:scale-[1.02] shadow-sm flex items-center justify-center gap-3 border border-gray-100">
                                            <i class="fas fa-shopping-bag text-sm"></i> Tambah Keranjang
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-32 pb-16 border-t border-white/5">
        <div class="container mx-auto px-6 md:px-12 lg:px-20 text-center">
            <div class="flex flex-col items-center gap-10 mb-20">
                <img src="../assets/images/wakgacor.jpeg" alt="Logo" class="w-20 h-20 rounded-full border-2 border-primary shadow-2xl">
                <div class="space-y-4">
                    <h5 class="text-4xl font-900 tracking-tighter">Wak Gacor <span class="text-primary">Store</span></h5>
                    <p class="text-gray-400 max-w-xl mx-auto text-lg leading-loose">
                        Partner terpercaya untuk kebutuhan infrastruktur digital Anda. Menghadirkan teknologi terbaik dengan layanan sepenuh hati.
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
        function changeSort(sortValue) {
            const url = new URL(window.location);
            url.searchParams.set('sort', sortValue);
            window.location.href = url.toString();
        }

        function addToCart(productId, quantity = 1) {
            const formData = new FormData();
            formData.append('ajax', '1');
            formData.append('action', 'add_to_cart');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('../api/cart/cart_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showCartNotification('Produk berhasil ditambahkan ke keranjang!');
                        // Optional: update cart badge in header
                        location.reload(); // Quick way to update counts
                    } else {
                        alert('Gagal menambahkan ke keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan ke keranjang');
                });
        }

        function showCartNotification(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-24 right-6 z-[9999] bg-secondary text-primary px-8 py-4 rounded-[20px] shadow-2xl border border-primary/20 flex items-center gap-3 animate-fade-in-up';
            toast.innerHTML = `
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-secondary">
                    <i class="fas fa-check"></i>
                </div>
                <p class="font-bold">${message}</p>
                <button onclick="this.parentElement.remove()" class="ml-4 text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        document.querySelector('select[name="category"]').addEventListener('change', () => document.getElementById('filterForm').submit());
        document.querySelector('select[name="subcategory"]').addEventListener('change', () => document.getElementById('filterForm').submit());
    </script>
</body>

</html>