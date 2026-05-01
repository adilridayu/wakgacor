<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Wak Gacor Store</title>
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
            .stat-card {
                @apply bg-white p-6 rounded-[30px] shadow-sm border border-gray-100 flex items-center justify-between transition-all hover:shadow-xl hover:-translate-y-1;
            }
        }
    </style>
</head>

<body class="font-outfit bg-[#F8F9FA] text-secondary flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-sidebar text-white flex flex-col fixed h-full z-50">
        <div class="p-8 flex items-center gap-3 border-b border-white/5">
            <img src="../assets/images/wakgacor.jpeg" alt="Logo" class="w-10 h-10 rounded-full border border-primary">
            <h2 class="text-xl font-800 tracking-tight">Wak Gacor <span class="text-primary">Admin</span></h2>
        </div>

        <nav class="flex-grow py-8 space-y-8 overflow-y-auto">
            <div>
                <p class="px-8 text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-4">Main Menu</p>
                <div class="space-y-1">
                    <a href="index.php" class="sidebar-link-active">
                        <i class="fas fa-home w-6"></i> Beranda
                    </a>
                    <a href="pages/user_management/users.php" class="sidebar-link">
                        <i class="fas fa-users w-6"></i> Pengguna
                    </a>
                    <a href="pages/admin_management/admins.php" class="sidebar-link">
                        <i class="fas fa-user-shield w-6"></i> Admin
                    </a>
                </div>
            </div>

            <div>
                <p class="px-8 text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-4">Katalog</p>
                <div class="space-y-1">
                    <a href="pages/product_management/products.php" class="sidebar-link">
                        <i class="fas fa-box w-6"></i> Produk
                    </a>
                    <a href="pages/category_management/categories.php" class="sidebar-link">
                        <i class="fas fa-tags w-6"></i> Kategori
                    </a>
                    <a href="pages/subcategory_management/subcategories.php" class="sidebar-link">
                        <i class="fas fa-layer-group w-6"></i> Subkategori
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-6 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 px-6 py-3 bg-red-500/10 text-red-500 rounded-xl font-bold hover:bg-red-500 hover:text-white transition-all">
                <i class="fas fa-sign-out-alt"></i> Keluar Panel
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow ml-72 p-10">
        <!-- Top Bar -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-800 text-secondary tracking-tight">Dashboard Overview</h1>
                <p class="text-gray-400 mt-1">Selamat datang kembali di panel kontrol utama.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold text-secondary">Administrator</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Super Admin Access</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center border border-gray-100 shadow-sm">
                    <i class="fas fa-user-shield text-primary"></i>
                </div>
            </div>
        </header>

        <!-- Welcome Banner -->
        <div class="bg-secondary rounded-[40px] p-10 text-white shadow-2xl relative overflow-hidden mb-10 group">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-700"></div>
            <div class="relative z-10">
                <h2 class="text-4xl font-800 mb-3 tracking-tight">Efisien, Cepat, <span class="text-primary">& Handal.</span></h2>
                <p class="text-gray-400 max-w-xl leading-relaxed">
                    Pantau kinerja Wak Gacor Store secara real-time. Kelola produk, kategori, dan akses pengguna dengan antarmuka premium yang intuitif.
                </p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="stat-card">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px]">Total Produk</p>
                    <h3 class="text-3xl font-800 text-secondary">Manajemen</h3>
                    <p class="text-xs text-primary font-bold mt-2">Inventaris Aktif</p>
                </div>
                <div class="w-16 h-16 bg-primary/10 rounded-[20px] flex items-center justify-center text-primary">
                    <i class="fas fa-box text-2xl"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px]">Akses Pengguna</p>
                    <h3 class="text-3xl font-800 text-secondary">Pelanggan</h3>
                    <p class="text-xs text-blue-500 font-bold mt-2">User Database</p>
                </div>
                <div class="w-16 h-16 bg-blue-50 rounded-[20px] flex items-center justify-center text-blue-500">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px]">Grup Katalog</p>
                    <h3 class="text-3xl font-800 text-secondary">Kategori</h3>
                    <p class="text-xs text-emerald-500 font-bold mt-2">Organisasi Produk</p>
                </div>
                <div class="w-16 h-16 bg-emerald-50 rounded-[20px] flex items-center justify-center text-emerald-500">
                    <i class="fas fa-tags text-2xl"></i>
                </div>
            </div>
        </div>
    </main>
</body>

</html>

</html>