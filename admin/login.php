<?php
session_start();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk Admin - Wak Gacor Store</title>
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
</head>

<body class="font-outfit bg-secondary min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Animated Background Orbs -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-primary/10 rounded-full blur-[100px] animate-pulse delay-700"></div>

    <main class="w-full max-w-md px-6 relative z-10">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[40px] p-10 shadow-2xl">
            <div class="text-center mb-10">
                <div class="inline-block relative">
                    <img src="../assets/images/wakgacor.jpeg" alt="Logo" class="w-24 h-24 rounded-full border-4 border-primary shadow-2xl mb-6 mx-auto transition-transform hover:scale-105 duration-500">
                    <div class="absolute -bottom-2 -right-2 bg-primary text-secondary w-8 h-8 rounded-full flex items-center justify-center border-4 border-secondary">
                        <i class="fas fa-shield-alt text-xs"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-800 text-white tracking-tight">Admin <span class="text-primary">Panel</span></h1>
                <p class="text-gray-400 mt-2 text-sm">Otentikasi diperlukan untuk akses sistem.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Username atau password salah.</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="../api/admin/admin_auth.php" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Username</label>
                    <div class="relative group">
                        <input type="text" name="username" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-12 py-4 text-white outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all"
                            placeholder="Masukkan username" required>
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-primary transition-colors"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative group">
                        <input type="password" name="password" 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-12 py-4 text-white outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition-all"
                            placeholder="Masukkan kata sandi" required>
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-primary transition-colors"></i>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-primary text-secondary font-800 rounded-2xl transition-all duration-500 hover:bg-white hover:scale-[1.02] active:scale-95 shadow-xl shadow-primary/20">
                    MASUK SEKARANG <i class="fas fa-sign-in-alt ml-2"></i>
                </button>
            </form>

            <div class="mt-10 text-center">
                <a href="../index.php" class="text-gray-500 hover:text-primary text-xs font-bold transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda Toko
                </a>
            </div>
        </div>
        
        <p class="text-center text-[10px] text-gray-600 mt-8 uppercase tracking-[3px] font-bold">&copy; 2025 Wak Gacor Store System</p>
    </main>
</body>

</html>