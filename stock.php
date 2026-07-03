<?php
require 'ceklogin.php';
$h1 = mysqli_query($c,"select * from produk");
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
        <title>Stock - KOPDES MERPUT</title>
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
                        <h1 class="mt-4"> Stok Barang</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KOPDES MERPUT</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-2">
                                    <div class="card-body">Jumlah Barang : <?=$h2;?></div>
                                  
                                </div>
                            </div>

                            <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                               
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Barang Baru</button>

                               <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Barang Baru</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <form method="post" action=""> 
                                        <div class="modal-body">
                                            <input type="text" name="namaproduk" class="form-control" placeholder="Nama Produk" required>
                                            <input type="text" name="deskripsi" class="form-control mt-2" placeholder="Deskripsi" required>
                                            
                                            <input type="number" name="stock" class="form-control mt-2" placeholder="Stock Awal" required>
                                            <input type="number" name="harga" class="form-control mt-2" placeholder="Harga Barang" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success" name="tambahbarang">Submit</button>
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
                                Data Barang
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Stock</th>
                                            <th>Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                      <tbody>
                                        <?php
                                        $get  = mysqli_query($c,"select * from produk");
                                        $i = 1;

                                        while($p=mysqli_fetch_array($get)){
                                            $nama_produk = $p['nama_produk'];
                                            $deskripsi = $p['deskripsi'];
                                            $harga = $p['harga'];
                                            $stock = $p['stock'];
                                            $id_produk = $p['id_produk'];
                                        ?>
                                        
                                        <tr>
                                            <td><?=$i++?></td>
                                            <td><?=$nama_produk?></td>
                                            <td><?=$deskripsi?></td>
                                            <td><?=$stock?></td>
                                            <td>Rp <?=number_format($harga)?></td>
                                            <td> 
                                                <!-- PERBAIKAN: Spasi dihilangkan pada target modal -->
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?=$id_produk;?>">Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?=$id_produk;?>">Delete</button>
                                            </td>
                                        </tr>
                                            
                                        <!-- Modal Edit -->
                                        <!-- PERBAIKAN: Spasi dihilangkan pada id modal -->
                                        <div id="edit<?=$id_produk;?>" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Barang</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form method="post" action=""> 
                                                        <div class="modal-body">
                                                            <!-- PERBAIKAN: Atribut value diisi agar memunculkan data lama saat diedit -->
                                                            <label class="form-label font-weight-bold">Nama Produk</label>
                                                            <input type="text" name="namaproduk" class="form-control mb-2" value="<?=$nama_produk;?>" required>
                                                            
                                                            <label class="form-label font-weight-bold">Deskripsi</label>
                                                            <input type="text" name="deskripsi" class="form-control mb-2" value="<?=$deskripsi;?>" required>
                                                            
                                                            <!-- Stock biasanya dikunci (readonly) jika kamu menggunakan sistem kasir masuk/keluar, namun jika ingin bebas edit silakan lepas readonly nya -->
                                                            <label class="form-label font-weight-bold">Stock</label>
                                                            <input type="number" name="stock" class="form-control mb-2" value="<?=$stock;?>" required>
                                                            
                                                            <label class="form-label font-weight-bold">Harga Barang</label>
                                                            <input type="number" name="harga" class="form-control mb-2" value="<?=$harga;?>" required>
                                                            
                                                            <!-- PERBAIKAN: ID Produk dikirim lewat input hidden -->
                                                            <input type="hidden" name="idpr" value="<?=$id_produk;?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <!-- PERBAIKAN: Mengubah name menjadi editbarang -->
                                                            <button type="submit" class="btn btn-success" name="editbarang">Simpan Perubahan</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Delete -->
                                            <div id="delete<?=$id_produk;?>" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Peringatan Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <form method="post" action=""> 
                                                            <div class="modal-body">
                                                                Apakah anda yakin ingin menghapus barang <strong><?=$nama_produk;?></strong> secara permanen dari daftar stok?
                                                                
                                                                <!-- Mengirim ID Produk ke function.php -->
                                                                <input type="hidden" name="idpr" value="<?=$id_produk;?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
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
