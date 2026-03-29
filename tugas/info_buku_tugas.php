<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Informasi Buku</h1>

        <?php
        // Data Buku (dipakai dalam Bootstrap Cards)
        $books = [
            [
                'judul' => 'Pemrograman PHP Modern',
                'pengarang' => 'Budi Raharjo',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2023,
                'harga' => 125000,
                'stok' => 8,
                'isbn' => '978-602-1234-56-7',
                'kategori' => 'Programming',
                'bahasa' => 'Indonesia',
                'halaman' => 320,
                'berat' => 450,
            ],
            [
                'judul' => 'MySQL Database Administration',
                'pengarang' => 'Johnathan Imam',
                'penerbit' => 'Kompas Gramedia',
                'tahun_terbit' => 2022,
                'harga' => 225000,
                'stok' => 9,
                'isbn' => '978-602-1234-30-2',
                'kategori' => 'Database',
                'bahasa' => 'Inggris',
                'halaman' => 400,
                'berat' => 600,
            ],
            [
                'judul' => 'Mastering Web Design with CSS3',
                'pengarang' => 'Jane Furqon',
                'penerbit' => 'Indomaret',
                'tahun_terbit' => 2021,
                'harga' => 185000,
                'stok' => 12,
                'isbn' => '978-602-4221-30-1',
                'kategori' => 'Web Design',
                'bahasa' => 'Inggris',
                'halaman' => 420,
                'berat' => 550,
            ],
            [
                'judul' => 'Pemrograman PHP Modern',
                'pengarang' => 'Eko Didik',
                'penerbit' => 'Alfamart',
                'tahun_terbit' => 2023,
                'harga' => 115000,
                'stok' => 15,
                'isbn' => '978-623-4221-30-2',
                'kategori' => 'Programming',
                'bahasa' => 'Indonesia',
                'halaman' => 450,
                'berat' => 560,
            ],
        ];

        // Mapping kategori -> warna badge Bootstrap
        $kategoriBadges = [
            'Programming' => 'bg-primary',
            'Database' => 'bg-success',
            'Web Design' => 'bg-info',
        ];

        function getKategoriBadgeClass($kategori)
        {
            global $kategoriBadges;
            return $kategoriBadges[$kategori] ?? 'bg-secondary';
        }
        ?>

        <div class="row g-4">

            <?php foreach ($books as $b): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0"><?php echo $b['judul']; ?></h5>
                                <span class="badge <?php echo getKategoriBadgeClass($b['kategori']); ?>">
                                    <?php echo $b['kategori']; ?>
                                </span>
                            </div>

                            <p class="text-muted mb-2"><?php echo $b['bahasa']; ?> • <?php echo $b['tahun_terbit']; ?></p>

                            <ul class="list-unstyled mb-4">
                                <li><strong>Pengarang:</strong> <?php echo $b['pengarang']; ?></li>
                                <li><strong>Penerbit:</strong> <?php echo $b['penerbit']; ?></li>
                                <li><strong>ISBN:</strong> <?php echo $b['isbn']; ?></li>
                                <li><strong>Halaman:</strong> <?php echo number_format($b['halaman']); ?></li>
                                <li><strong>Berat:</strong> <?php echo number_format($b['berat']); ?> gr</li>
                            </ul>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Stok: <?php echo $b['stok']; ?></span>
                                    <span class="fw-semibold">Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>