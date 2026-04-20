<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Registrasi Anggota Perpustakaan</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        // Kode ini dibuat dengan bantuan Copilot dan Antigravity buat diajak ngobrol
                        // sama nyari solusi kalau kodenya ada yang sudah atau error
                        // Variabel untuk menyimpan pesan sukses
                        $success = '';
                        
                        // Array asosiatif untuk menyimpan error per field
                        $errors = [];
                        // Variabel untuk menyimpan input (keep value)
                        $nama = '';
                        $email = '';
                        $telepon = '';
                        $alamat = '';
                        $jenis_kelamin = '';
                        $tanggal_lahir = '';
                        $pekerjaan = '';
                        $nim = '';
                        // Proses form jika di-submit
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // Ambil dan sanitasi data
                            $nama = trim(htmlspecialchars($_POST['nama'] ?? ''));
                            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
                            $telepon = trim(htmlspecialchars($_POST['telepon'] ?? ''));
                            $alamat = trim(htmlspecialchars($_POST['alamat'] ?? ''));
                            $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
                            $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
                            $pekerjaan = trim($_POST['pekerjaan'] ?? '');
                            $nim = trim(htmlspecialchars($_POST['nim'] ?? ''));
                            // Validasi Nama Lengkap
                            if (empty($nama)) {
                                $errors['nama'] = "Nama lengkap wajib diisi.";
                            } elseif (strlen($nama) < 3) {
                                $errors['nama'] = "Nama lengkap minimal 3 karakter.";
                            }
                            // Validasi Email
                            if (empty($email)) {
                                $errors['email'] = "Email wajib diisi.";
                            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = "Format email tidak valid.";
                            }
                            // Validasi Telepon
                            if (empty($telepon)) {
                                $errors['telepon'] = "Nomor telepon wajib diisi.";
                            } elseif (!preg_match('/^08[0-9]{8,11}$/', $telepon)) {
                                $errors['telepon'] = "Format telepon tidak valid (harus diawali '08' dan terdiri dari 10-13 digit).";
                            }
                            // Validasi Alamat
                            if (empty($alamat)) {
                                $errors['alamat'] = "Alamat wajib diisi.";
                            } elseif (strlen($alamat) < 10) {
                                $errors['alamat'] = "Alamat minimal 10 karakter.";
                            }
                            // Validasi Jenis Kelamin
                            if (empty($jenis_kelamin)) {
                                $errors['jenis_kelamin'] = "Jenis kelamin wajib dipilih.";
                            }
                            // Validasi Tanggal Lahir dan Umur
                            if (empty($tanggal_lahir)) {
                                $errors['tanggal_lahir'] = "Tanggal lahir wajib diisi.";
                            } else {
                                $bday = new DateTime($tanggal_lahir);
                                $today = new DateTime('today');
                                $umur = $bday->diff($today)->y;
                                
                                if ($umur < 10) {
                                    $errors['tanggal_lahir'] = "Umur pendaftar minimal 10 tahun.";
                                }
                            }
                            // Validasi Pekerjaan
                            if (empty($pekerjaan)) {
                                $errors['pekerjaan'] = "Pekerjaan wajib diisi.";
                            }
                            // Validasi NIM jika Mahasiswa
                            if ($pekerjaan == 'Mahasiswa') {
                                if (empty($nim)) {
                                    $errors['nim'] = "NIM wajib diisi untuk mahasiswa.";
                                } elseif (!preg_match('/^[0-9]{9,12}$/', $nim)) {
                                    $errors['nim'] = "NIM harus terdiri dari 9-12 digit angka.";
                                }
                            }
                            // Jika tidak ada error, set pesan sukses
                            if (count($errors) == 0) {
                                $success = "Registrasi berhasil!";
                            }
                        }
                        ?>
                        <!-- Area Pesan Sukses dan Output Kartu -->
                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill"></i> <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-card-text"></i> Data Anggota yang Disimpan</h6>
                            </div>
                            <div class="card-body bg-light">
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">Nama Lengkap</div>
                                    <div class="col-sm-8 fw-bold"><?= $nama ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">Email</div>
                                    <div class="col-sm-8 fw-bold"><?= $email ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">Nomor Telepon</div>
                                    <div class="col-sm-8 fw-bold"><?= $telepon ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                                    <div class="col-sm-8 fw-bold"><?= $jenis_kelamin == 'Laki-laki' ? 'Laki-laki' : 'Perempuan' ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">Tanggal Lahir</div>
                                    <div class="col-sm-8 fw-bold"><?= date('d F Y', strtotime($tanggal_lahir)) ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">Pekerjaan</div>
                                    <div class="col-sm-8 fw-bold"><?= $pekerjaan ?></div>
                                </div>
                                <?php if ($pekerjaan == 'Mahasiswa'): ?>
                                <div class="row mb-2">
                                    <div class="col-sm-4 text-muted">NIM</div>
                                    <div class="col-sm-8 fw-bold"><?= $nim ?></div>
                                </div>
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-sm-4 text-muted">Alamat</div>
                                    <div class="col-sm-8 fw-bold"><?= nl2br($alamat) ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- Form -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                                        id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>"
                                        placeholder="Masukkan nama lengkap">
                                <?php if(isset($errors['nama'])): ?>
                                    <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                            id="email" name="email" value="<?= htmlspecialchars($email) ?>"
                                            placeholder="contoh@email.com">
                                    <?php if(isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : '' ?>"
                                            id="telepon" name="telepon" value="<?= htmlspecialchars($telepon) ?>"
                                            placeholder="08xxxxxxxxxx">
                                    <?php if(isset($errors['telepon'])): ?>
                                        <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>" type="radio" name="jenis_kelamin" id="jk_laki" value="Laki-laki" <?= ($jenis_kelamin == 'Laki-laki') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="jk_laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>" type="radio" name="jenis_kelamin" id="jk_perempuan" value="Perempuan" <?= ($jenis_kelamin == 'Perempuan') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                                </div>
                                <?php if(isset($errors['jenis_kelamin'])): ?>
                                    <div class="d-block invalid-feedback mt-1"><?= $errors['jenis_kelamin'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?= isset($errors['tanggal_lahir']) ? 'is-invalid' : '' ?>"
                                            id="tanggal_lahir" name="tanggal_lahir" value="<?= htmlspecialchars($tanggal_lahir) ?>">
                                    <?php if(isset($errors['tanggal_lahir'])): ?>
                                        <div class="invalid-feedback"><?= $errors['tanggal_lahir'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                    <select class="form-select <?= isset($errors['pekerjaan']) ? 'is-invalid' : '' ?>" id="pekerjaan" name="pekerjaan" onchange="document.getElementById('nim-field').style.display = (this.value === 'Mahasiswa') ? 'block' : 'none';">
                                        <option value="">-- Pilih Pekerjaan --</option>
                                        <option value="Pelajar" <?= ($pekerjaan == 'Pelajar') ? 'selected' : '' ?>>Pelajar</option>
                                        <option value="Mahasiswa" <?= ($pekerjaan == 'Mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                                        <option value="Pegawai" <?= ($pekerjaan == 'Pegawai') ? 'selected' : '' ?>>Pegawai</option>
                                        <option value="Lainnya" <?= ($pekerjaan == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                    <?php if(isset($errors['pekerjaan'])): ?>
                                        <div class="invalid-feedback"><?= $errors['pekerjaan'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3" id="nim-field" style="<?= ($pekerjaan == 'Mahasiswa') ? 'display: block;' : 'display: none;' ?>">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>"
                                        id="nim" name="nim" value="<?= htmlspecialchars($nim) ?>"
                                        placeholder="Masukkan NIM (9-12 digit)">
                                <?php if(isset($errors['nim'])): ?>
                                    <div class="invalid-feedback"><?= $errors['nim'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-4">
                                <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>"
                                        id="alamat" name="alamat" rows="3"
                                        placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($alamat) ?></textarea>
                                <?php if(isset($errors['alamat'])): ?>
                                    <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-person-check-fill"></i> Daftarkan Anggota
                                </button>
                                <!-- Kita hapus onClick reload untuk mencegah value hilang, ganti menjadi link kosong agar menjadi reset murni atau reload jika mau mematikan keep value -->
                                <a href="" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Bersihkan Form
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Info Validasi -->
                <div class="card mt-3 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-shield-check"></i> Aturan Validasi Registrasi</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Semua field dengan tanda bintang (<span class="text-danger">*</span>) harus diisi.</li>
                            <li>Format email harus benar dan valid.</li>
                            <li>Nomor telepon diawali dengan <b>08</b> dan panjang 10 - 13 karakter angka.</li>
                            <li>Alamat harus dijelaskan minimal 10 karakter.</li>
                            <li>Anggota memiliki minimal usia <b>10 tahun</b> (berdasarkan tanggal lahir).</li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>