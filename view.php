<?php

require 'ceklogin.php';
if(isset($_GET['idp'])){
    $idp = $_GET['idp'];
    $ambilnamapelanggan = mysqli_query($c, "select * from pesanan p, pelanggan pl where p.id_pelanggan=pl.id_pelanggan and p.id_pesanan='$idp'");
    $np = mysqli_fetch_array($ambilnamapelanggan);
    $namapel = $np['nama_pelanggan'];
} else {
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - KOPDES MERPUT</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">KOPDES MERAH PUTIH</a>
           
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">MENU KASIR</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-basket"></i></div>
                                Order
                            </a>
                             <a class="nav-link" href="stock.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-poll"></i></div>
                                Stok Barang
                            </a>
                             <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="pelanggan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-cart-plus"></i></div>
                                Kelola Pelanggan
                            </a>
                             <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="far fa-share-square"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        ADMIN KASIR KOPDES MERAH PUTIH
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4"> Data Pesanan </h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KOPDES MERPUT</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card bg-primary text-white p-3 mb-3" style="border-radius: 10px;">
                                        Data Pesanan : <?=$idp;?><br>
                                        Nama Pelanggan : <?=$namapel;?>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                               
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Barang</button>
                                <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                              
                               <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
                                            
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            
                                        </div>

                                        <form method="post" action=""> 
                                        <div class="modal-body">
                                            Pilih Barang :
                                            <select name="id_produk" class="form-control mt-2" required>
                                           <option value="">-- Pilih Barang --</option>
                                           <?php
                                        $getproduk  = mysqli_query ($c,"select * from produk where id_produk not in (select id_produk from detail_pesanan where id_pesanan='$idp')");
                                        

                                        while($pr=mysqli_fetch_array($getproduk)){
                                        $nama_produk = $pr['nama_produk'];
                                        $deskripsi = $pr['deskripsi'];
                                        $harga = $pr['harga'];
                                        $stock = $pr['stock'];
                                        $id_produk = $pr['id_produk'];
                                        
                                        ?>

                                        <option value="<?=$id_produk;?>"> <?=$nama_produk;?> - <?=$deskripsi?> - (Stock : <?=$stock?>) </option>

                                        <?php
                                        };  //
                                        ?>
                                        </select>
                                        <input type="number" name="qty" class="form-control mt-4" placeholder="Jumlah" min="1" required>
                                        <input type="hidden" name="idp" value="<?=$idp;?>">
                                        
                                    </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success" name="addproduk">Submit</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                    
                                    </div>
                                </div>
                            </div>

                                </div>
                                </div>
                            </div>
                            
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Pesanan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk (Deskripsi)</th>
                                            <th>Harga Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Sub-total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                      <tbody>
                                        <?php
                                        $get = mysqli_query($c, "SELECT * FROM detail_pesanan p INNER JOIN produk pl ON p.id_produk = pl.id_produk where id_pesanan='$idp'");
                                        $i = 1;

                                        while($p=mysqli_fetch_array($get)){
                                            $idpr = $p['id_produk'];
                                            $iddp = $p['id_detailpesanan']; // Sesuai database kamu
                                            $qty = $p['qty'];
                                            $harga = $p['harga'];
                                            $nama_produk = $p['nama_produk'];
                                            $desc = $p['deskripsi'];
                                            $subtotal = $qty*$harga; 
                                        ?>
                                        
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$nama_produk;?> (<?=$desc;?>) </td>
                                            <td>Rp <?=number_format($harga);?></td>
                                            <td><?=($qty);?></td>
                                            <td>Rp <?=number_format($subtotal);?></td>
                                            <td>
                                                <!-- Tombol Aksi Lengkap -->
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?=$iddp;?>">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?=$iddp;?>">Hapus</button>
                                            </td>
                                        </tr>
                                            
                                        <!-- Modal Edit Detail Pesanan -->
                                        <div id="edit<?=$iddp;?>" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Ubah Jumlah Pesanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form method="post" action=""> 
                                                        <div class="modal-body">
                                                            <label class="form-label font-weight-bold">Nama Barang</label>
                                                            <input type="text" class="form-control mb-2" value="<?=$nama_produk;?> (<?=$desc;?>)" readonly>
                                                            
                                                            <label class="form-label font-weight-bold">Harga Satuan</label>
                                                            <input type="text" class="form-control mb-2" value="Rp <?=number_format($harga);?>" readonly>

                                                            <label class="form-label font-weight-bold">Jumlah Beli (Qty)</label>
                                                            <input type="number" name="qty" class="form-control mb-2" value="<?=$qty;?>" required min="1">
                                                            
                                                            <!-- Mengirim data parameter ke function.php -->
                                                            <input type="hidden" name="iddp" value="<?=$iddp;?>">
                                                            <input type="hidden" name="idpr" value="<?=$idpr;?>">
                                                            <input type="hidden" name="id_pesanan" value="<?=$idp;?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success" name="editprodukpesanan">Simpan Perubahan</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Delete -->
                                        <div id="delete<?=$iddp;?>" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form method="post" action=""> 
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus barang <strong><?=$nama_produk;?></strong> dari pesanan ini?
                                                            
                                                            <!-- Mengirim data yang benar ke function.php -->
                                                            <input type="hidden" name="idp" value="<?=$iddp;?>">        <!-- id_detailpesanan -->
                                                            <input type="hidden" name="idpr" value="<?=$idpr;?>">       <!-- id_produk -->
                                                            <input type="hidden" name="id_pesanan" value="<?=$idp;?>">  <!-- id_pesanan -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success" name="hapusprodukpesanan">Ya</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tidak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        };  
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
               
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
