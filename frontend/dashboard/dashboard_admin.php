<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEG - Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex flex-shrink-0">
            <div>
                <div class="h-16 flex items-center justify-center bg-slate-950 px-6 border-b border-slate-800">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-amber-400 text-2xl"></i>
                        <span class="text-lg font-bold tracking-wider">SIMPEG ADMIN</span>
                    </div>
                </div>

                <nav class="mt-4 px-3 space-y-1">
                    <a href="index.php?page=dashboard_admin" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
                        <i class="fa-solid fa-chart-pie mr-3 w-5 text-center"></i> Beranda Admin
                    </a>
                    
                    <div class="space-y-1">
                        <span class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider block mt-4 mb-1">Manajemen SDM</span>
                        <a href="index.php?page=daftar_pegawai" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-400 hover:bg-slate-800 hover:text-white transition-all">
                            <i class="fa-solid fa-users mr-3 w-5 text-center"></i> Kelola Data Pegawai
                        </a>
                    </div>
                </nav>
            </div>

            <div class="p-3 border-t border-slate-800">
                <a href="index.php?page=logout" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i> Keluar Sistem
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10 shadow-sm">
                <h1 class="text-xl font-bold text-gray-800">Panel Administrator Portal 🔐</h1>
                <div class="text-sm font-medium text-gray-500"><?php echo date('d M Y'); ?></div>
            </header>

            <main class="p-6">
                <?php
                $current_page = $_GET['page'] ?? 'dashboard_admin';
                
                switch ($current_page) {
                    case 'daftar_pegawai':
                        include __DIR__ . '/../pegawai/Daftar_Pegawai.php';
                        break;
                    case 'tambah_pegawai':
                        include __DIR__ . '/../pegawai/Tambah_Pegawai.php';
                        break;
                    case 'detail_pegawai':
                        include __DIR__ . '/../pegawai/Detail_Pegawai.php';
                        break;
                    
                    case 'dashboard_admin':
                    default:
                        // Taruh file ringkasan codingan dashboard admin (Total Pegawai, Hadir Hari Ini, dsb) di sini
                        include __DIR__ . '/konten_ringkasan_admin.php'; 
                        break;
                }
                ?>
            </main>

        </div>
    </div>

</body>
</html>