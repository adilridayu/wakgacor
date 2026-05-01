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
            .form-input {
                @apply w-full bg-white border border-gray-100 rounded-2xl px-6 py-4 outline-none focus:ring-4 focus:ring-primary/10 transition-all text-sm shadow-sm placeholder:text-gray-300;
            }
            .form-label {
                @apply block text-xs font-800 text-gray-500 uppercase tracking-wider mb-2 ml-1;
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
                    <a href="users.php" class="sidebar-link-active">
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
                <h1 class="text-3xl font-800 text-secondary tracking-tight">Tambah <span class="text-primary">Pengguna Baru</span></h1>
                <p class="text-gray-400 mt-1">Daftarkan akun pelanggan baru secara manual ke dalam sistem.</p>
            </div>
            
            <a href="users.php" class="px-6 py-3.5 bg-white text-gray-400 font-bold rounded-2xl transition-all duration-300 hover:bg-secondary hover:text-white shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> KEMBALI
            </a>
        </header>

        <!-- Form Card -->
        <div class="max-w-4xl bg-white rounded-[40px] shadow-sm border border-gray-100 p-10">
            <form action="../../../api/admin/users.php" method="POST" class="space-y-8">
                <input type="hidden" value="create" name="action">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="form-label" for="first_name">Nama Depan</label>
                        <input class="form-input" id="first_name" type="text" placeholder="Contoh: Budi" name="first_name" required>
                    </div>
                    <div>
                        <label class="form-label" for="last_name">Nama Belakang</label>
                        <input class="form-input" id="last_name" type="text" placeholder="Contoh: Santoso" name="last_name" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="form-label" for="email">Alamat Email</label>
                        <input class="form-input" id="email" type="email" placeholder="budi@example.com" name="email" required>
                    </div>
                    <div>
                        <label class="form-label" for="phone_number">Nomor Telepon</label>
                        <input class="form-input" id="phone_number" type="tel" placeholder="0812xxxxxxx" name="phone_number" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-50 pt-8">
                    <div>
                        <label class="form-label" for="dob">Tanggal Lahir</label>
                        <input class="form-input" id="dob" type="date" name="dob" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-50 pt-8">
                    <div>
                        <label class="form-label" for="password">Kata Sandi</label>
                        <div class="relative group">
                            <input class="form-input" id="password" type="password" placeholder="Minimal 8 karakter" name="password" required minlength="8">
                            <i class="fas fa-lock absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="confirm_password">Konfirmasi Kata Sandi</label>
                        <div class="relative group">
                            <input class="form-input" id="confirm_password" type="password" placeholder="Ulangi kata sandi" name="confirm_password" required>
                            <i class="fas fa-shield-check absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-hover:text-primary transition-colors"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="px-10 py-4 bg-primary text-secondary font-800 rounded-2xl transition-all duration-500 hover:bg-secondary hover:text-white hover:scale-[1.02] shadow-xl shadow-primary/20 flex items-center gap-3">
                        <i class="fas fa-user-plus text-lg"></i> DAFTARKAN PENGGUNA
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

</html>