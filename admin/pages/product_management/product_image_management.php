<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../../index.php");
    exit();
}

require "../../../includes/db.php";

$product_id = $_GET["product_id"];

// Get product details
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $product_id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all images for this product
$sql_images = "SELECT products.id as product_id, images.id as image_id, images.image_url, images.filename, images.alt_text 
                   FROM product_images 
                   INNER JOIN products ON product_images.product_id = products.id 
                   INNER JOIN images ON product_images.image_id = images.id 
                   WHERE products.id = :product_id 
                   ORDER BY images.id ASC";
$stmt_images = $pdo->prepare($sql_images);
$stmt_images->bindParam(":product_id", $product_id);
$stmt_images->execute();
$product_images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

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
                        sidebar: '#1A1A1A',
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
            .sidebar-link {
                @apply flex items-center px-6 py-3.5 text-gray-400 font-semibold transition-all duration-300 hover:bg-white/5 hover:text-primary rounded-xl mx-2;
            }
            .sidebar-link-active {
                @apply flex items-center px-6 py-3.5 bg-primary text-secondary font-bold rounded-xl mx-2 shadow-lg shadow-primary/20;
            }
            .card-premium {
                @apply bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden;
            }
            .form-input {
                @apply w-full bg-white border border-gray-100 rounded-2xl px-6 py-4 outline-none focus:ring-4 focus:ring-primary/10 transition-all text-sm shadow-sm;
            }
            .btn-action {
                @apply w-10 h-10 flex items-center justify-center rounded-xl transition-all duration-300;
            }
        }
    </style>
</head>

<body class="font-outfit bg-[#F8F9FA] text-secondary flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-sidebar text-white flex flex-col fixed h-full z-50">
        <div class="p-8 flex items-center gap-3 border-b border-white/5">
            <img src="../../../assets/images/wakgacor.jpeg" alt="Logo" class="w-10 h-10 rounded-full border border-primary">
            <h2 class="text-xl font-800 tracking-tight">Wak Gacor <span class="text-primary">Admin</span></h2>
        </div>

        <nav class="flex-grow py-8 space-y-8 overflow-y-auto">
            <div>
                <p class="px-8 text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-4">Menu Utama</p>
                <div class="space-y-1">
                    <a href="../../index.php" class="sidebar-link">
                        <i class="fas fa-home w-6"></i> Beranda
                    </a>
                    <a href="../user_management/users.php" class="sidebar-link">
                        <i class="fas fa-users w-6"></i> Pengguna
                    </a>
                    <a href="../admin_management/admins.php" class="sidebar-link">
                        <i class="fas fa-user-shield w-6"></i> Admin
                    </a>
                </div>
            </div>

            <div>
                <p class="px-8 text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-4">Katalog</p>
                <div class="space-y-1">
                    <a href="products.php" class="sidebar-link-active">
                        <i class="fas fa-box w-6"></i> Produk
                    </a>
                    <a href="../category_management/categories.php" class="sidebar-link">
                        <i class="fas fa-tags w-6"></i> Kategori
                    </a>
                    <a href="../subcategory_management/subcategories.php" class="sidebar-link">
                        <i class="fas fa-layer-group w-6"></i> Subkategori
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-6 border-t border-white/5">
            <a href="../../logout.php" class="flex items-center gap-3 px-6 py-3 bg-red-500/10 text-red-500 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all">
                <i class="fas fa-sign-out-alt"></i> Keluar Panel
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow ml-72 p-10">
        <!-- Top Bar -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-800 text-secondary tracking-tight">Kelola <span class="text-primary">Media Produk</span></h1>
                <p class="text-gray-400 mt-1"><?= htmlspecialchars($product["name"]) ?></p>
            </div>
            
            <a href="products.php" class="px-6 py-3.5 bg-white text-gray-400 font-bold rounded-2xl transition-all duration-300 hover:bg-secondary hover:text-white shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> KEMBALI
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Cover Image Section -->
            <div class="lg:col-span-1 space-y-8">
                <div class="card-premium p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-image text-primary"></i>
                        <h5 class="font-800 uppercase text-xs tracking-wider text-gray-500">Foto Utama (Cover)</h5>
                    </div>
                    
                    <div class="space-y-6">
                        <?php if ($product["cover_image"]): ?>
                            <div class="relative rounded-[30px] overflow-hidden border border-gray-100 shadow-lg">
                                <img src="../../../assets/uploads/<?= htmlspecialchars($product["cover_image"]) ?>" class="w-full aspect-square object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                                    <p class="text-[10px] text-white font-mono opacity-80 truncate"><?= htmlspecialchars($product["cover_image"]) ?></p>
                                </div>
                            </div>
                            
                            <form method="POST" action="../../../api/admin/product_images.php" enctype="multipart/form-data" class="space-y-4 pt-2">
                                <input type="hidden" name="action" value="update_cover">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <div>
                                    <label class="text-[10px] font-800 text-gray-400 uppercase tracking-widest mb-2 block ml-1">Ganti Cover</label>
                                    <input type="file" name="cover_image" accept="image/*" class="form-input file:hidden text-xs py-3" required>
                                </div>
                                <button type="submit" class="w-full py-4 bg-primary text-secondary font-800 rounded-2xl hover:bg-secondary hover:text-white transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                                    <i class="fas fa-sync-alt text-xs"></i> PERBARUI COVER
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="bg-amber-50 text-amber-600 p-4 rounded-2xl border border-amber-100 flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p class="text-xs font-bold">Belum ada foto utama.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Gallery Images Section -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-premium p-10">
                    <div class="flex justify-between items-center mb-10">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-images text-primary"></i>
                            <h5 class="font-800 uppercase text-xs tracking-wider text-gray-500">Galeri Foto (<?= count($product_images) ?>)</h5>
                        </div>
                    </div>

                    <?php if (count($product_images) > 0): ?>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-10">
                            <?php foreach ($product_images as $index => $image): ?>
                                <div class="group relative rounded-[30px] overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500">
                                    <img src="../../../assets/uploads/<?= htmlspecialchars($image["image_url"]) ?>" class="w-full aspect-[4/5] object-cover group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                        <form method="POST" action="../../../api/admin/product_images.php" onsubmit="return confirm('Hapus foto ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                            <input type="hidden" name="image_id" value="<?= $image["image_id"] ?>">
                                            <button type="submit" class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center hover:scale-[1.15] transition-all shadow-xl">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                                        <p class="text-[9px] text-white font-mono opacity-70 truncate"><?= htmlspecialchars($image["filename"]) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-blue-50 text-blue-600 p-8 rounded-[30px] border border-blue-100 text-center mb-10">
                            <i class="fas fa-info-circle text-2xl mb-3 block"></i>
                            <p class="text-sm font-bold tracking-tight">Belum ada galeri foto untuk produk ini.</p>
                            <p class="text-xs opacity-70 mt-1">Gunakan formulir di bawah untuk menambahkan foto.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Add New Images Section -->
                    <div class="border-t border-gray-50 pt-10">
                        <h6 class="text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-6 flex items-center gap-2">
                            <span class="w-8 h-px bg-gray-100"></span> TAMBAH FOTO BARU
                        </h6>
                        <form method="POST" action="../../../api/admin/product_images.php" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $product_id ?>">

                            <div class="md:col-span-3">
                                <input required type="file" name="product_images[]" multiple accept="image/*" class="form-input file:hidden text-xs py-4" id="newImages">
                                <p class="text-[10px] text-gray-400 mt-3 flex items-center gap-2 ml-2">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    Pilih beberapa file (Ctrl/Cmd). Maks 500KB/file. Format: JPG, PNG.
                                </p>
                            </div>
                            <div class="md:col-span-1">
                                <button type="submit" class="w-full h-[54px] bg-secondary text-white font-800 rounded-2xl hover:bg-primary hover:text-secondary transition-all shadow-xl flex items-center justify-center gap-2">
                                    <i class="fas fa-cloud-upload-alt"></i> UNGGAH
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>

</html>