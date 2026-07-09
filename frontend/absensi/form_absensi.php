<div class="mb-8">
    <div class="flex items-center space-x-3.5 mb-2">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
            <i class="fa-solid fa-clock-user text-lg"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Presensi Kehadiran</h1>
            <p class="text-sm font-medium text-slate-400 mt-0.5">Catat jam masuk dan jam pulang kerja Anda secara akurat dan real-time.</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200/60 shadow-xl shadow-slate-100/40 p-6 md:p-8 max-w-md mx-auto text-center relative overflow-hidden border-t-4 border-indigo-600">
    
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full blur-2xl opacity-70 pointer-events-none"></div>
    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-50 rounded-full blur-2xl opacity-70 pointer-events-none"></div>

    <div class="relative space-y-6">
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 shadow-inner">
            <span class="inline-flex items-center text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-full uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5 animate-ping"></span> Waktu Sekarang
            </span>
            <h2 id="liveClock" class="text-4xl font-black text-slate-800 tracking-tight font-mono"><?= date('H:i:s'); ?> <span class="text-xs font-bold text-slate-400 font-sans">WIB</span></h2>
            <p class="text-xs font-bold text-slate-400 mt-1.5 flex items-center justify-center">
                <i class="fa-solid fa-calendar-day mr-1.5 text-slate-400"></i><?= date('d F Y'); ?>
            </p>
        </div>

        <div class="text-left bg-blue-50/60 border border-blue-100 p-4 rounded-xl flex items-start space-x-3 text-xs text-blue-700 font-medium leading-relaxed">
            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-sm flex-shrink-0"></i>
            <span>Pastikan Anda berada di area kerja yang diizinkan sebelum menekan tombol absen masuk maupun absen pulang.</span>
        </div>

        <form action="index.php?page=proses_absen" method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                
                <button type="submit" name="jenis" value="masuk" class="group relative py-4 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md shadow-emerald-600/10 hover:shadow-lg hover:shadow-emerald-600/20 active:scale-95 transition-all flex flex-col items-center justify-center overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/30 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fa-solid fa-right-to-bracket text-base text-emerald-100"></i>
                    </div>
                    <span class="text-xs tracking-wide uppercase">Absen Masuk</span>
                </button>
                
                <button type="submit" name="jenis" value="pulang" class="group relative py-4 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-md shadow-rose-600/10 hover:shadow-lg hover:shadow-rose-600/20 active:scale-95 transition-all flex flex-col items-center justify-center overflow-hidden">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <div class="w-10 h-10 rounded-lg bg-rose-500/30 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fa-solid fa-right-from-bracket text-base text-rose-100"></i>
                    </div>
                    <span class="text-xs tracking-wide uppercase">Absen Pulang</span>
                </button>
                
            </div>
        </form>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('liveClock').innerHTML = `${hours}:${minutes}:${seconds} <span class="text-xs font-bold text-slate-400 font-sans">WIB</span>`;
    }
    // Update jam setiap 1 detik
    setInterval(updateClock, 1000);
</script>