<?php
session_start();

// 1. Hapus semua variabel session
session_unset();

// 2. Hancurkan session yang sedang berjalan
session_destroy();

// 3. Alihkan halaman kembali ke login.php
header("Location: login.php");
exit();
?>