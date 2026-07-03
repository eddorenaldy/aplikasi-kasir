<?php
session_start();

//koneksi
$c = mysqli_connect('localhost', 'root','','kasir');

//login

if(isset($_POST['login'])) {
    //variabel
    $username = $_POST['username'];
    $password = $_POST['password'];

        $check = mysqli_query($c,"SELECT * FROM user WHERE username='$username' and password='$password' ");
        $hitung = mysqli_num_rows($check);

        if($hitung > 0) {

            $_SESSION['login'] = 'True';
            header('location:index.php');
        } else {
            echo '
            <script>
            alert("Username atau Password Salah");
            window.location.href="login.php"
            </script>
            ';
        }
    }
    
if(isset($_POST['tambahbarang'])) {

    $namaproduk = $_POST['namaproduk'];
    $deskripsi  = $_POST['deskripsi'];
    $stock      = $_POST['stock'];
    $harga      = $_POST['harga'];

    // PERBAIKAN: Menggunakan kutip dua (") di luar query, dan menyamakan posisi $harga & $stock
    $insert = mysqli_query($c, "INSERT INTO produk (nama_produk, deskripsi, harga, stock) VALUES ('$namaproduk', '$deskripsi', '$harga', '$stock')");

    if($insert) {
        header('location:stock.php');
        exit(); // Bagus untuk ditambahkan setelah header location agar script di bawahnya langsung berhenti
    } else {
        echo '
        <script>
            alert("Gagal Menambah Barang Baru");
            window.location.href="stock.php";
        </script>
        ';
    }
}
    if(isset($_POST['tambahpelanggan'])) {

    $nama_pelanggan = $_POST['nama_pelanggan'];
    $notelp  = $_POST['notelp'];
    $alamat      = $_POST['alamat'];
   
    // PERBAIKAN: Menggunakan kutip dua (") di luar query, dan menyamakan posisi $harga & $stock
    $insert = mysqli_query($c, "INSERT INTO pelanggan (nama_pelanggan, notelp, alamat) VALUES ('$nama_pelanggan', '$notelp', '$alamat')");

    if($insert) {
        header('location:pelanggan.php');
        exit(); // Bagus untuk ditambahkan setelah header location agar script di bawahnya langsung berhenti
    } else {
        echo '
        <script>
            alert("Gagal Menambah Pelanggan Baru");
            window.location.href="pelanggan.php";
        </script>
        ';
    }
}

if(isset($_POST['tambahpesanan'])) {

    $id_pelanggan = $_POST['id_pelanggan'];
    
       
    // PERBAIKAN: Menggunakan kutip dua (") di luar query, dan menyamakan posisi $harga & $stock
    $insert = mysqli_query($c, "INSERT INTO pesanan (id_pelanggan) VALUES ('$id_pelanggan')");

    if($insert) {
        header('location:index.php');
        exit(); // Bagus untuk ditambahkan setelah header location agar script di bawahnya langsung berhenti
    } else {
        echo '
        <script>
            alert("Gagal Menambah Pesanan Baru");
            window.location.href="index.php";
        </script>
        ';
    }
}

if(isset($_POST['addproduk'])) {

    $id_produk = $_POST['id_produk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty'];
    
       
   //hitung stock sekarang ada berapa
$hitung1 = mysqli_query($c,"select * from produk where id_produk='$id_produk'");
$hitung2 = mysqli_fetch_array($hitung1);
$stocksekarang = $hitung2['stock']; //stock barang saat ini

if($stocksekarang>=$qty){
    //stocknya cukup
    $insert = mysqli_query($c,"insert into detail_pesanan (id_pesanan,id_produk,qty) values ('$idp','$id_produk','$qty')");
    
    // logic pengurangan stock barang
    $selisih = $stocksekarang - $qty;
    $update = mysqli_query($c,"update produk set stock='$selisih' where id_produk='$id_produk'");
    
    if($insert && $update){
        header('location:view.php?idp='.$idp);
    } else {
        echo '
        <script>
            alert("Gagal menambah pesanan baru");
            window.location.href="view.php?idp='.$idp.'";
        </script>
        ';
    }
} else {
    //stock ga cukup
    echo '
    <script>
        alert("Stock barang tidak cukup");
        window.location.href="view.php?idp='.$idp.'";
    </script>
    ';
}
}

// Hapus Total Nota Pesanan (Balikkan Stok & Clear Records)
if(isset($_POST['hapusnotapesanan'])){
    $id_pesanan = $_POST['id_pesanan'];

    // 1. Ambil semua item yang terdaftar di dalam id_pesanan ini untuk dikembalikan stoknya
    $ambil_detail = mysqli_query($c, "select * from detail_pesanan where id_pesanan='$id_pesanan'");

    while($rows = mysqli_fetch_array($ambil_detail)){
        $id_produk = $rows['id_produk'];
        $qty_beli  = $rows['qty'];

        // Cek stok produk saat ini
        $cek_stok   = mysqli_query($c, "select stock from produk where id_produk='$id_produk'");
        $data_stok  = mysqli_fetch_array($cek_stok);
        $stok_now   = $data_stok['stock'];

        // Hitung stok baru (stok sekarang + dikembalikan karena batal beli)
        $stok_baru  = $stok_now + $qty_beli;

        // Update stok kembali ke tabel produk masing-masing
        mysqli_query($c, "update produk set stock='$stok_baru' where id_produk='$id_produk'");
    }

    // 2. Hapus data dari tabel detail_pesanan terlebih dahulu (Child Table)
    $hapus_detail = mysqli_query($c, "delete from detail_pesanan where id_pesanan='$id_pesanan'");

    // 3. Hapus data utama dari tabel pesanan (Parent Table)
    $hapus_pesanan = mysqli_query($c, "delete from pesanan where id_pesanan='$id_pesanan'");

    if($hapus_detail && $hapus_pesanan){
        header('location:index.php');
    } else {
        echo '
        <script>
            alert("Gagal menghapus transaksi pesanan");
            window.location.href="index.php";
        </script>
        ';
    }
}

//Menambah barang masuk sekaligus mengupdate stok di gudang
if(isset($_POST['barangmasuk'])){
    $id_produk = $_POST['id_produk']; // mengambil id produk dari modal
    $qty = $_POST['qty']; // mengambil jumlah barang masuk

    // 1. Ambil jumlah stok saat ini yang ada di tabel produk
    $cari_produk = mysqli_query($c, "SELECT * FROM produk WHERE id_produk='$id_produk'");
    $data_produk = mysqli_fetch_array($cari_produk);
    $stok_saat_ini = $data_produk['stock'];

    // 2. Hitung jumlah stok baru (Stok Lama + Barang Masuk)
    $stok_baru = $stok_saat_ini + $qty;

    // 3. Masukkan riwayat ke tabel masuk   
    $insertb = mysqli_query($c, "INSERT INTO masuk (id_produk, qty) VALUES ('$id_produk', '$qty')");

    // 4. Update total stok baru ke tabel produk
    $update_stok_produk = mysqli_query($c, "UPDATE produk SET stock='$stok_baru' WHERE id_produk='$id_produk'");

    // Cek jika kedua proses di atas berhasil dijalankan
    if($insertb && $update_stok_produk){
        header('location:masuk.php');
    } else {
        echo '
        <script>
            alert("Gagal menambahkan barang masuk");
            window.location.href="masuk.php";
        </script>
        ';
    }
}

//hapus produk pesanan
if(isset($_POST['hapusprodukpesanan'])){
    $idp = $_POST['idp']; // Menangkap id_detailpesanan
    $idpr = $_POST['idpr']; // Menangkap id_produk
    $idorder = $_POST['id_pesanan']; // Menangkap id_pesanan

    //Cek qty sekarang
    $cek1 = mysqli_query($c,"select * from detail_pesanan where id_detailpesanan='$idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    //Cek stock sekarang
    $cek3 = mysqli_query($c,"select * from produk where id_produk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stocksekarang = $cek4['stock'];

    //Hitung stok yang dikembalikan (Stok lama + Qty pesanan yang dihapus)
    $hitung = $stocksekarang + $qtysekarang;

    //Update stok produk dan hapus item dari detail pesanan
    $update = mysqli_query($c,"update produk set stock='$hitung' where id_produk='$idpr'");
    $hapus = mysqli_query($c,"delete from detail_pesanan where id_produk='$idpr' and id_detailpesanan='$idp'");

    if($update && $hapus){
        header('location:view.php?idp='.$idorder);
    } else {
        echo '
        <script>
            alert("Gagal menghapus barang");
            window.location.href="view.php?idp='.$idorder.'";
        </script>
        ';
    }
}

// Edit Detail Pesanan (Sinkronisasi Stok Logika Kasir)
if(isset($_POST['editprodukpesanan'])){
    $iddp = $_POST['iddp'];        // id_detailpesanan
    $idpr = $_POST['idpr'];        // id_produk
    $id_pesanan = $_POST['id_pesanan']; // id_pesanan untuk redirect target balik
    $qtybaru = $_POST['qty'];      // Jumlah input baru dari form

    // 1. Cari tahu qty lama di detail pesanan sebelum diubah
    $cari_qty_lama = mysqli_query($c, "select * from detail_pesanan where id_detailpesanan='$iddp'");
    $data_lama     = mysqli_fetch_array($cari_qty_lama);
    $qtylama       = $data_lama['qty'];

    // 2. Ambil stok utama barang saat ini dari tabel produk
    $cari_stok_produk = mysqli_query($c, "select * from produk where id_produk='$idpr'");
    $data_produk      = mysqli_fetch_array($cari_stok_produk);
    $stokaktual       = $data_produk['stock'];

    if($qtybaru > $qtylama){
        // Jika pembeli menambah pesanan, stok barang di toko harus dikurangi lagi
        $selisih = $qtybaru - $qtylama;
        $stokbaru = $stokaktual - $selisih;
    } else {
        // Jika pembeli mengurangi pesanan, sisa barang dikembalikan ke stok toko
        $selisih = $qtylama - $qtybaru;
        $stokbaru = $stokaktual + $selisih;
    }

    // Validasi pencegahan agar stok tidak minus saat diedit berlebihan
    if($stokbaru < 0){
        echo '
        <script>
            alert("Gagal mengubah data! Stok barang di gudang tidak mencukupi.");
            window.location.href="view.php?idp='.$id_pesanan.'";
        </script>
        ';
    } else {
        // 3. Eksekusi update perubahan qty pesanan dan pemotongan/penambahan stok utama produk
        $update_detail = mysqli_query($c, "update detail_pesanan set qty='$qtybaru' where id_detailpesanan='$iddp'");
        $update_produk = mysqli_query($c, "update produk set stock='$stokbaru' where id_produk='$idpr'");

        if($update_detail && $update_produk){
            header('location:view.php?idp='.$id_pesanan);
        } else {
            echo '<script>alert("Gagal merubah kuantitas pesanan."); window.location.href="view.php?idp='.$id_pesanan.'";</script>';
        }
    }
}

// Edit barang / produk
if(isset($_POST['editbarang'])){
    $idpr = $_POST['idpr']; // Menangkap id produk dari input hidden
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];

    // Melakukan query update berdasarkan id_produk
    $query = mysqli_query($c, "update produk set nama_produk='$namaproduk', deskripsi='$deskripsi', stock='$stock', harga='$harga' where id_produk='$idpr'");

    if($query){
        // Ganti 'index.php' sesuai dengan nama file tempat kamu melihat stock barang saat ini
        header('location:stock.php'); 
    } else {
        echo '
        <script>
            alert("Gagal mengubah data barang");
            window.location.href="stock.php";
        </script>
        ';
    }
}

// Hapus produk / barang
if(isset($_POST['hapusbarang'])){
    $idpr = $_POST['idpr']; // Menangkap id_produk dari input hidden

    // Eksekusi hapus barang berdasarkan id_produk
    $query = mysqli_query($c, "delete from produk where id_produk='$idpr'");

    if($query){
        // Sesuaikan 'stock.php' dengan nama file halaman stok barang kamu jika berbeda
        header('location:stock.php'); 
    } else {
        echo '
        <script>
            alert("Gagal menghapus barang dari sistem.");
            window.location.href="stock.php";
        </script>
        ';
    }
}

// Edit Data Pelanggan
if(isset($_POST['editpelanggan'])){
    $idpl = $_POST['idpl']; // Menangkap id pelanggan
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    $query = mysqli_query($c, "update pelanggan set nama_pelanggan='$namapelanggan', notelp='$notelp', alamat='$alamat' where id_pelanggan='$idpl'");

    if($query){
        header('location:pelanggan.php'); 
    } else {
        echo '
        <script>
            alert("Gagal mengubah data pelanggan");
            window.location.href="pelanggan.php";
        </script>
        ';
    }
}

// Hapus Data Pelanggan
if(isset($_POST['hapuspelanggan'])){
    $idpl = $_POST['idpl']; // Menangkap id pelanggan

    $query = mysqli_query($c, "delete from pelanggan where id_pelanggan='$idpl'");

    if($query){
        header('location:pelanggan.php'); 
    } else {
        echo '
        <script>
            alert("Gagal menghapus pelanggan");
            window.location.href="pelanggan.php";
        </script>
        ';
    }
}

// Edit Barang Masuk (Sinkronisasi Stok)
if(isset($_POST['editbarangmasuk'])){
    $idm = $_POST['idm'];
    $idpr = $_POST['idpr'];
    $qtybaru = $_POST['qty']; // Angka baru dari form input

    // 1. Cari tahu berapa qty lama yang tercatat di database sebelum di-edit
    $cari_qty_lama = mysqli_query($c, "select * from masuk where id_masuk='$idm'");
    $data_lama     = mysqli_fetch_array($cari_qty_lama);
    $qtylama       = $data_lama['qty'];

    // 2. Cari tahu stok aktual saat ini di tabel produk
    $cari_stok_produk = mysqli_query($c, "select * from produk where id_produk='$idpr'");
    $data_produk      = mysqli_fetch_array($cari_stok_produk);
    $stokaktual       = $data_produk['stock'];

    if($qtybaru > $qtylama){
        // Jika qty baru lebih besar, berarti ada tambahan barang masuk lagi
        $selisih = $qtybaru - $qtylama;
        $stokbaru = $stokaktual + $selisih;
    } else {
        // Jika qty baru lebih kecil, berarti input sebelumnya kelebihan, kurangi stok utama
        $selisih = $qtylama - $qtybaru;
        $stokbaru = $stokaktual - $selisih;
    }

    // 3. Eksekusi update ke tabel masuk dan tabel produk secara bersamaan
    $update_masuk  = mysqli_query($c, "update masuk set qty='$qtybaru' where id_masuk='$idm'");
    $update_produk = mysqli_query($c, "update produk set stock='$stokbaru' where id_produk='$idpr'");

    if($update_masuk && $update_produk){
        header('location:masuk.php');
    } else {
        echo '<script>alert("Gagal merubah data."); window.location.href="masuk.php";</script>';
    }
}

// Hapus Barang Masuk (Kembalikan/Kurangi Stok)
if(isset($_POST['hapusbarangmasuk'])){
    $idm = $_POST['idm'];
    $idpr = $_POST['idpr'];

    // 1. Ambil info qty riwayat masuk yang mau dihapus
    $cari_qty_lama = mysqli_query($c, "select * from masuk where id_masuk='$idm'");
    $data_lama     = mysqli_fetch_array($cari_qty_lama);
    $qtylama       = $data_lama['qty'];

    // 2. Ambil stok aktual saat ini di tabel produk
    $cari_stok_produk = mysqli_query($c, "select * from produk where id_produk='$idpr'");
    $data_produk      = mysqli_fetch_array($cari_stok_produk);
    $stokaktual       = $data_produk['stock'];

    // Hitung stok baru (Stok dikurangi karena transaksi masuknya dibatalkan/dihapus)
    $stokbaru = $stokaktual - $qtylama;

    // 3. Eksekusi hapus data dari riwayat masuk dan potong stok utamanya
    $hapus_masuk   = mysqli_query($c, "delete from masuk where id_masuk='$idm'");
    $update_produk = mysqli_query($c, "update produk set stock='$stokbaru' where id_produk='$idpr'");

    if($hapus_masuk && $update_produk){
        header('location:masuk.php');
    } else {
        echo '<script>alert("Gagal menghapus riwayat masuk."); window.location.href="masuk.php";</script>';
    }
}


?>