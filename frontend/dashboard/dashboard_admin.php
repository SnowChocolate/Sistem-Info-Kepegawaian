<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ADMIN - Dashboard Admin</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" 
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>


<body class="bg-slate-100 font-sans text-gray-800">


<div class="flex h-screen overflow-hidden">



<!-- SIDEBAR -->

<aside class="w-72 bg-gradient-to-b from-slate-950 via-slate-900 to-indigo-950 text-white flex flex-col justify-between shadow-xl hidden md:flex">


<div>


<!-- LOGO -->

<div class="h-20 flex items-center px-6 border-b border-white/10">


<div class="flex items-center gap-3">


<div class="bg-indigo-600 w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg">

<i class="fa-solid fa-shield-halved text-2xl"></i>

</div>



<div>

<h1 class="font-black text-lg">
SIMPEG
</h1>


<p class="text-xs text-slate-400">
ADMIN PANEL
</p>


</div>


</div>


</div>





<nav class="mt-6 px-4 space-y-2">



<a href="index.php?page=dashboard_admin"
class="flex items-center gap-3 px-4 py-3 rounded-2xl 
text-slate-300 hover:bg-indigo-600 hover:text-white transition">

<i class="fa-solid fa-chart-pie w-5"></i>

Dashboard

</a>





<p class="text-xs uppercase text-slate-500 font-bold px-4 pt-5">

Manajemen SDM

</p>



<a href="index.php?page=daftar_pegawai"
class="flex items-center gap-3 px-4 py-3 rounded-2xl 
text-slate-300 hover:bg-blue-600 hover:text-white transition">


<i class="fa-solid fa-users w-5"></i>

Data Pegawai


</a>






<p class="text-xs uppercase text-slate-500 font-bold px-4 pt-5">

Operasional

</p>




<a href="index.php?page=rekap_absensi"
class="flex items-center gap-3 px-4 py-3 rounded-2xl 
text-slate-300 hover:bg-purple-600 hover:text-white transition">


<i class="fa-solid fa-calendar-check w-5"></i>

Absensi


</a>





<a href="index.php?page=gaji"
class="flex items-center gap-3 px-4 py-3 rounded-2xl 
text-slate-300 hover:bg-emerald-600 hover:text-white transition">


<i class="fa-solid fa-money-bill-wave w-5"></i>

Payroll / Gaji


</a>





<a href="index.php?page=persetujuan_cuti"
class="flex items-center gap-3 px-4 py-3 rounded-2xl 
text-slate-300 hover:bg-orange-500 hover:text-white transition">


<i class="fa-solid fa-plane-departure w-5"></i>

Cuti Pegawai


</a>



</nav>


</div>





<div class="p-4 border-t border-white/10">


<a href="index.php?page=logout"
class="flex items-center gap-3 px-4 py-3 rounded-2xl
text-red-400 hover:bg-red-500/20 transition">


<i class="fa-solid fa-right-from-bracket"></i>

Keluar


</a>


</div>



</aside>






<!-- CONTENT -->


<div class="flex-1 flex flex-col overflow-y-auto">



<header class="h-20 bg-white border-b flex items-center justify-between px-8 shadow-sm">


<div>

<h1 class="text-2xl font-black text-slate-800">

Panel Administrator 🔐

</h1>


<p class="text-xs text-gray-400">

Sistem Informasi Kepegawaian

</p>


</div>



<div class="bg-slate-100 px-4 py-2 rounded-xl font-bold text-sm">


<i class="fa-solid fa-calendar text-indigo-600 mr-2"></i>

<?=date('d M Y');?>


</div>



</header>





<main class="p-8">



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




case 'edit_pegawai':

include __DIR__ . '/../pegawai/Edit_Pegawai.php';

break;





case 'rekap_absensi':

include __DIR__ . '/../absensi/rekap_absensi.php';

break;




case 'form_absensi':

include __DIR__ . '/../absensi/form_absensi.php';

break;





case 'gaji':

include __DIR__ . '/../gaji/Data_Gaji_Admin.php';

break;




case 'slip_gaji':

include __DIR__ . '/../gaji/Slip_Gaji.php';

break;




case 'proses_bayar_gaji':

include __DIR__ . '/../gaji/Proses_Bayar_Gaji.php';

break;





case 'persetujuan_cuti':

include __DIR__ . '/../cuti/Persetujuan_Cuti.php';

break;




case 'form_cuti':

include __DIR__ . '/../cuti/Form_Cuti.php';

break;





default:

?>



<!-- DASHBOARD HOME -->


<div class="space-y-8">



<div class="bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 
rounded-3xl p-8 text-white shadow-xl">


<h2 class="text-3xl font-black">

Selamat Datang Admin 👋

</h2>


<p class="text-indigo-200 mt-2">

Kelola sistem informasi kepegawaian dengan mudah.

</p>


</div>





<div class="grid md:grid-cols-4 gap-6">



<a href="index.php?page=daftar_pegawai"
class="bg-white rounded-3xl p-6 shadow hover:shadow-xl transition">


<i class="fa-solid fa-users text-blue-600 text-3xl"></i>


<h3 class="font-black text-xl mt-4">

Pegawai

</h3>


<p class="text-blue-600 text-sm font-bold mt-2">

Kelola Data →

</p>


</a>





<a href="index.php?page=gaji"
class="bg-white rounded-3xl p-6 shadow hover:shadow-xl transition">


<i class="fa-solid fa-money-bill-wave text-green-600 text-3xl"></i>


<h3 class="font-black text-xl mt-4">

Payroll

</h3>


<p class="text-green-600 text-sm font-bold mt-2">

Kelola Gaji →

</p>


</a>





<a href="index.php?page=rekap_absensi"
class="bg-white rounded-3xl p-6 shadow hover:shadow-xl transition">


<i class="fa-solid fa-calendar-check text-purple-600 text-3xl"></i>


<h3 class="font-black text-xl mt-4">

Absensi

</h3>


<p class="text-purple-600 text-sm font-bold mt-2">

Lihat Kehadiran →

</p>


</a>





<a href="index.php?page=persetujuan_cuti"
class="bg-white rounded-3xl p-6 shadow hover:shadow-xl transition">


<i class="fa-solid fa-plane text-orange-600 text-3xl"></i>


<h3 class="font-black text-xl mt-4">

Cuti

</h3>


<p class="text-orange-600 text-sm font-bold mt-2">

Persetujuan →

</p>


</a>



</div>




</div>


<?php

break;

}


?>



</main>


</div>



</div>


</body>

</html>