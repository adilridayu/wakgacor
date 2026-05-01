<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../../index.php");
    exit();
}

require "../../../includes/db.php";


$sql_subcategories = "SELECT * FROM subcategories";
$stmt_subcategories = $pdo->prepare($sql_subcategories);
$stmt_subcategories->execute();
$subcategories = $stmt_subcategories->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Wak Gacor Store</title>
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
                <p class="px-8 text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-4">Main Menu</p>
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
                <h1 class="text-3xl font-800 text-secondary tracking-tight">Manajemen <span class="text-primary">Produk</span></h1>
                <p class="text-gray-400 mt-1">Kelola inventaris barang dan detail produk Anda.</p>
            </div>
            
            <a href="create_product.php" class="px-6 py-3.5 bg-primary text-secondary font-800 rounded-2xl transition-all duration-500 hover:bg-white hover:scale-[1.05] shadow-xl shadow-primary/20 flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> TAMBAH PRODUK
            </a>
        </header>

        <!-- Product Table -->
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">ID</th>
                            <th class="px-6 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Info Produk</th>
                            <th class="px-6 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Harga (IDR)</th>
                            <th class="px-6 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Kategori</th>
                            <th class="px-6 py-6 text-center text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $sql = "SELECT
                                products.id,
                                products.name,
                                products.description,
                                products.price,
                                products.cover_image,
                                subcategories.name as subcategory,
                                categories.name as category
                                FROM product_subcategories
                                INNER JOIN products ON product_subcategories.product_id = products.id
                                INNER JOIN subcategories ON product_subcategories.subcategory_id = subcategories.id
                                INNER JOIN category_subcategories ON subcategories.id = category_subcategories.subcategory_id
                                INNER JOIN categories ON category_subcategories.category_id = categories.id";

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($products as $product) {
                            $display_price = number_format($product["price"] * 15000, 0, ',', '.');
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <span class="text-xs font-800 text-gray-400">#<?= $product["id"] ?></span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl overflow-hidden border border-gray-100 flex-shrink-0 shadow-sm">
                                            <img src="../../../assets/uploads/<?= $product["cover_image"] ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-secondary text-sm group-hover:text-primary transition-colors"><?= htmlspecialchars($product["name"]) ?></p>
                                            <p class="text-[10px] text-gray-400 mt-0.5"><?= htmlspecialchars(substr($product["description"], 0, 40)) ?>...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <p class="text-sm font-800 text-secondary">Rp <?= $display_price ?></p>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex text-[10px] font-800 text-primary uppercase tracking-wider"><?= htmlspecialchars($product["category"]) ?></span>
                                        <span class="inline-flex text-[9px] font-bold text-gray-400 uppercase tracking-widest"><?= htmlspecialchars($product["subcategory"]) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="product_image_management.php?product_id=<?= $product["id"] ?>" class="btn-action bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white" title="Galeri">
                                            <i class="fas fa-images text-xs"></i>
                                        </a>
                                        <a href="#" class="btn-action bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form method="POST" action="../../../api/admin/products.php" class="inline" onsubmit="return confirm('Hapus produk ini?');">
                                            <input type="hidden" name="id" value="<?= $product["id"] ?>"> 
                                            <input type="hidden" name="action" value="delete">   
                                            <button type="submit" class="btn-action bg-red-50 text-red-500 hover:bg-red-500 hover:text-white">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button> 
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>

</html>