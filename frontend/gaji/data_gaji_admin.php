<?php

require_once __DIR__ . '/../../backend/config/koneksi.php';

$db = new Database();
$conn = $db->conn;


// ==============================
// PROSES BAYAR GAJI
// ==============================
if(isset($_GET['bayar'])){

    $id_gaji = intval($_GET['bayar']);

    mysqli_query($conn,"
        UPDATE gaji
        SET status_bayar='dibayar'
        WHERE id='$id_gaji'
    ");

    echo "<script>
    alert('Gaji berhasil dibayar');
    window.location='index.php?page=gaji';
    </script>";

}


// ==============================
// PROSES TAMBAH GAJI
// ==============================
if(isset($_POST['simpan'])){

    $id_user = $_POST['id_user'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $tunjangan = $_POST['tunjangan'];
    $potongan = $_POST['potongan'];

    $total_gaji = $gaji_pokok + $tunjangan - $potongan;


    mysqli_query($conn,"
        INSERT INTO gaji
        (
        id_user,
        bulan,
        tahun,
        gaji_pokok,
        tunjangan,
        potongan,
        total_gaji,
        status_bayar
        )
        VALUES
        (
        '$id_user',
        '$bulan',
        '$tahun',
        '$gaji_pokok',
        '$tunjangan',
        '$potongan',
        '$total_gaji',
        'belum'
        )
    ");


    echo "<script>
    alert('Data gaji berhasil ditambahkan');
    window.location='index.php?page=gaji';
    </script>";

}


// ==============================
// AMBIL DATA PEGAWAI
// ==============================

$pegawai = mysqli_query($conn,"
    SELECT * FROM pegawai
");


// ==============================
// AMBIL DATA GAJI
// ==============================

$data_gaji = mysqli_query($conn,"
SELECT 
    gaji.*,
    pegawai.nama,
    pegawai.jabatan

FROM gaji

JOIN pegawai
ON gaji.id_user = pegawai.id_user

ORDER BY gaji.tahun DESC, gaji.bulan DESC
");


$bulan=[
1=>"Januari",
2=>"Februari",
3=>"Maret",
4=>"April",
5=>"Mei",
6=>"Juni",
7=>"Juli",
8=>"Agustus",
9=>"September",
10=>"Oktober",
11=>"November",
12=>"Desember"
];

?>


<div class="space-y-6">


<!-- FORM TAMBAH GAJI -->

<div class="bg-white rounded-xl shadow p-6">

<h2 class="text-lg font-bold mb-4">
<i class="fa-solid fa-plus text-green-600"></i>
Tambah Data Gaji
</h2>


<form method="POST"
class="grid grid-cols-1 md:grid-cols-3 gap-4">


<select name="id_user"
class="border rounded-lg p-2"
required>

<option value="">
Pilih Pegawai
</option>

<?php while($p=mysqli_fetch_assoc($pegawai)){ ?>

<option value="<?= $p['id_user']; ?>">

<?= $p['nama']; ?>

(<?= $p['jabatan']; ?>)

</option>

<?php } ?>

</select>


<input type="number"
name="bulan"
placeholder="Bulan"
class="border rounded-lg p-2"
required>


<input type="number"
name="tahun"
placeholder="Tahun"
class="border rounded-lg p-2"
required>


<input type="number"
name="gaji_pokok"
placeholder="Gaji Pokok"
class="border rounded-lg p-2"
required>


<input type="number"
name="tunjangan"
placeholder="Tunjangan"
class="border rounded-lg p-2">


<input type="number"
name="potongan"
placeholder="Potongan"
class="border rounded-lg p-2">


<button name="simpan"
class="bg-indigo-600 text-white rounded-lg p-2">

Simpan Gaji

</button>


</form>

</div>



<!-- TABEL GAJI -->


<div class="bg-white rounded-xl shadow p-6">


<h2 class="text-xl font-bold mb-5">

<i class="fa-solid fa-money-bill-wave text-green-600"></i>

Data Payroll Pegawai

</h2>



<table class="w-full text-sm">

<thead>

<tr class="bg-slate-100">

<th class="p-3">Nama</th>
<th class="p-3">Jabatan</th>
<th class="p-3">Periode</th>
<th class="p-3">Total</th>
<th class="p-3">Status</th>
<th class="p-3">Aksi</th>

</tr>

</thead>


<tbody>


<?php while($g=mysqli_fetch_assoc($data_gaji)){ ?>


<tr class="border-b">


<td class="p-3">
<?= $g['nama']; ?>
</td>


<td class="p-3">
<?= $g['jabatan']; ?>
</td>


<td class="p-3">
<?= $bulan[$g['bulan']]; ?>
<?= $g['tahun']; ?>
</td>


<td class="p-3 font-bold">

Rp <?=number_format($g['total_gaji'],0,",",".");?>

</td>


<td class="p-3">

<?php if($g['status_bayar']=="dibayar"){ ?>

<span class="text-green-600 font-bold">
✔ Sudah Dibayar
</span>

<?php }else{ ?>

<span class="text-yellow-600 font-bold">
⏳ Belum Dibayar
</span>

<?php } ?>

</td>


<td class="p-3">


<?php if($g['status_bayar']=="belum"){ ?>


<a href="index.php?page=gaji&bayar=<?= $g['id']; ?>"
class="bg-green-600 text-white px-3 py-2 rounded-lg">

Bayar

</a>


<?php }else{ ?>


<span class="text-gray-400">
Selesai
</span>


<?php } ?>


</td>


</tr>


<?php } ?>


</tbody>

</table>


</div>


</div>