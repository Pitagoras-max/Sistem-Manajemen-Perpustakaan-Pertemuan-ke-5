<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Ketersediaan Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4"><i class="bi bi-search"></i> Cek Ketersediaan Buku</h1>

        <?php
        // Data Buku 1
        $judul1 = "Pemprograman PHP untuk Pemula";
        $stok1 = 5; // Jumlah stok buku 1
        $harga1 = 75000;

        // Data Buku 2
        $judul2 = "MySQL Database Administration";
        $stok2 = 0; // Jumlah stok buku 2
        $harga2 = 95000;
        ?>

        <!-- Buku 1 -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $judul1; ?></h5>
            </div>
        </div>

    </div>
</body>
</html>