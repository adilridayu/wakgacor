<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../../index.php");
    exit();
}

require "../../../includes/db.php";

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
            .btn-action {
                @apply w-10 h-10 flex items-center justify-center rounded-xl transition-all duration-300;
            }
            .input-field-inline {
                @apply bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 outline-none focus:border-primary/50 focus:ring-2 focus:ring-primary/10 transition-all text-xs;
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
                    <a href="admins.php" class="sidebar-link-active">
                        <i class="fas fa-user-shield w-6"></i> Admin
                    </a>
                </div>
            </div>

            <div>
                <p class="px-8 text-[10px] font-800 text-gray-500 uppercase tracking-[2px] mb-4">Katalog</p>
                <div class="space-y-1">
                    <a href="../product_management/products.php" class="sidebar-link">
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
                <h1 class="text-3xl font-800 text-secondary tracking-tight">Manajemen <span class="text-primary">Administrator</span></h1>
                <p class="text-gray-400 mt-1">Kelola akun yang memiliki akses ke panel kontrol sistem.</p>
            </div>
            
            <div class="flex gap-4">
                <form class="flex gap-2">
                    <div class="relative">
                        <input class="bg-white border border-gray-100 rounded-2xl px-12 py-3.5 outline-none focus:ring-4 focus:ring-primary/10 transition-all text-sm w-64 shadow-sm" type="search" placeholder="Cari admin..." name="search">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </form>
                <a href="create_admin.php" class="w-12 h-12 bg-primary text-secondary rounded-2xl flex items-center justify-center shadow-xl shadow-primary/20 hover:scale-[1.05] transition-all">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </header>

        <!-- Admins Table -->
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">ID</th>
                            <th class="px-6 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Username</th>
                            <th class="px-6 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Dibuat</th>
                            <th class="px-6 py-6 text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Terakhir Diperbarui</th>
                            <th class="px-6 py-6 text-center text-[10px] font-800 text-gray-400 uppercase tracking-[2px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php
                        $sql = "SELECT * FROM admins";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($admins as $admin) {
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <span class="text-xs font-800 text-gray-400">#<?= $admin["id"] ?></span>
                                </td>
                                <td class="px-6 py-6">
                                    <form class="flex items-center gap-2" method="POST" action="../../../api/admin/admins.php">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                        <input class="input-field-inline w-40" type="text" name="username" value="<?= htmlspecialchars($admin['username'], ENT_QUOTES) ?>">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-500 rounded-lg hover:bg-emerald-500 hover:text-white transition-all"><i class="fas fa-save text-[10px]"></i></button>
                                    </form>
                                </td>
                                <td class="px-6 py-6">
                                    <p class="text-xs font-semibold text-gray-500"><?= date('d M Y', strtotime($admin["created_at"])) ?></p>
                                </td>
                                <td class="px-6 py-6">
                                    <p class="text-xs font-semibold text-gray-400"><?= date('d M Y', strtotime($admin["updated_at"])) ?></p>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center justify-center">
                                        <form method="POST" action="../../../api/admin/admins.php" onsubmit="return confirm('Hapus admin ini?');">
                                            <input type="hidden" name="id" value="<?= $admin["id"] ?>">
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