<aside class="w-full md:w-64 bg-slate-900 text-slate-100 flex flex-col p-5 border-r border-slate-800">
            <div class="flex items-center gap-3 mb-8 px-2">
                <i class="fa-solid fa-id-card-alt text-2xl text-blue-500"></i>
                <span class="text-xl font-bold tracking-wider">SIMPEG</span>
            </div>
            
            <nav class="flex-1 space-y-1">
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-slate-400"></i> Dashboard
                </a>
                <a href="/pegawai" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-users w-5 text-center text-slate-400"></i> Data Pegawai
                </a>
                <a href="/absensi" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-clock w-5 text-center text-slate-400"></i> Absensi
                </a>
                <a href="/cuti" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-calendar-minus w-5 text-center text-slate-400"></i> Pengajuan Cuti
                </a>
                <a href="/gaji" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-money-check-dollar w-5 text-center text-slate-400"></i> Gaji & Slip
                </a>
                <a href="/user" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fa-solid fa-user-gear w-5 text-center text-slate-400"></i> Manajemen User
                </a>
            </nav>
            
            <div class="pt-4 border-t border-slate-800 mt-auto">
                <a href="/logout" onclick="return confirm('Apakah anda ingin keluar?')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors justify-center">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </a>
            </div>
        </aside>

        <main class="flex-1 p-6 md:p-8">