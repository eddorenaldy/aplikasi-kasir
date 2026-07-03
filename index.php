<?php
require 'ceklogin.php';
$h1 = mysqli_query($c,"select * from pesanan");
$h2 = mysqli_num_rows($h1);

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
                        <h1 class="mt-4"> Data Pesanan</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KOPDES MERPUT</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah Pesanan : <?=$h2;?></div>
                                  
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                               
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">Pesanan Baru</button>

                               <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Pesanan Baru</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <form method="post" action=""> 
                                        <div class="modal-body">
                                            <div class="alert alert-warning" role="alert">
                                                 <strong>Catatan:</strong> Jika Data Pelanggan yang dituju tidak ada , 
                                                         silakan update terlebih dahulu pada menu <strong>Kelola Pelanggan</strong>. 
                                             </div>
                                            Pilih Pelanggan :
                                            <select name="id_pelanggan" class="form-control mt-2" required>
                                                <option value="">-- Pilih Pelanggan --</option>

                                           <?php
                                        $getpelanggan  = mysqli_query ($c,"select * from pelanggan");
                                        

                                        while($pl=mysqli_fetch_array($getpelanggan)){
                                        $id_pelanggan = $pl['id_pelanggan'];
                                        $nama_pelanggan = $pl['nama_pelanggan'];
                                        $alamat = $pl['alamat'];
                                        
                                        ?>

                                        <option value="<?=$id_pelanggan;?>"> <?=$nama_pelanggan;?> - <?=$alamat?> </option>

                                        <?php
                                        };  //
                                        ?>
                                        </select>
                                        
                                    </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success" name="tambahpesanan">Submit</button>
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
                                            <th>Id Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                      <tbody>
                                        <?php
                                        $get = mysqli_query($c, "SELECT * FROM pesanan p INNER JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan");
                                        
                                        while($p=mysqli_fetch_array($get)){
                                        $id_pesanan = $p['id_pesanan'];
                                        $tanggal = $p['tanggal'];
                                        $nama_pelanggan = $p['nama_pelanggan'];
                                        $alamat = $p['alamat'];
                                        
                                        $hitungjumlah = mysqli_query($c, "select * from detail_pesanan where id_pesanan='$id_pesanan'");
                                        $jumlah = mysqli_num_rows($hitungjumlah);
                                        ?>
                                        
                                        <tr>
                                            <td><?=$id_pesanan?></td>
                                            <td><?=$tanggal?></td>
                                            <td><?=$nama_pelanggan?> - <?=$alamat?></td>
                                            <td><?=$jumlah?></td>
                                            <td>
                                                <!-- Tombol Tampilkan dan Delete Bersandingan -->
                                                <a href="view.php?idp=<?=$id_pesanan;?>" class="btn btn-primary btn-sm" target="_blank">Tampilkan</a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteNota<?=$id_pesanan;?>">Delete</button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal Konfirmasi Delete Nota Pesanan -->
                                        <div id="deleteNota<?=$id_pesanan;?>" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Transaksi Pesanan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form method="post" action=""> 
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus seluruh pesanan **Nota #<?=$id_pesanan;?>** atas nama **<?=$nama_pelanggan;?>**?
                                                            <br><small class="text-danger">*Tindakan ini akan membatalkan pesanan dan otomatis mengembalikan semua stok item terkait ke gudang.</small>
                                                            
                                                            <!-- Input hidden ID Pesanan -->
                                                            <input type="hidden" name="id_pesanan" value="<?=$id_pesanan;?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger" name="hapusnotapesanan">Hapus Permanen</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
