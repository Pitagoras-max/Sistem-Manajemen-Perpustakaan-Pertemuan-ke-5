<?php
// 1. Function untuk hitung total anggota
function hitung_total_anggota($anggota_list) {
    return count($anggota_list);
}
 
// 2. Function untuk hitung anggota aktif
function hitung_anggota_aktif($anggota_list) {
    $count = 0;
    foreach ($anggota_list as $anggota) {
        if ($anggota['status'] == 'Aktif') {
            $count++;
        }
    }
    return $count;
}
 
// 3. Function untuk hitung rata-rata pinjaman
function hitung_rata_rata_pinjaman($anggota_list) {
    $total = count($anggota_list);
    if ($total == 0) return 0;
    
    $total_pinjaman = 0;
    foreach ($anggota_list as $anggota) {
        $total_pinjaman += $anggota['total_pinjaman'];
    }
    
    return round($total_pinjaman / $total, 1);
}
 
// 4. Function untuk cari anggota by ID
function cari_anggota_by_id($anggota_list, $id) {
    foreach ($anggota_list as $anggota) {
        if ($anggota['id'] == $id) {
            return $anggota;
        }
    }
    return null;
}
 
// 5. Function untuk cari anggota teraktif
function cari_anggota_teraktif($anggota_list) {
    $teraktif = null;
    $max_pinjaman = -1;
    
    foreach ($anggota_list as $anggota) {
        if ($anggota['total_pinjaman'] > $max_pinjaman) {
            $max_pinjaman = $anggota['total_pinjaman'];
            $teraktif = $anggota;
        }
    }
    return $teraktif;
}
 
// 6. Function untuk filter by status
function filter_by_status($anggota_list, $status) {
    if ($status == 'Semua') {
        return $anggota_list;
    }
    
    $filtered = [];
    foreach ($anggota_list as $anggota) {
        if ($anggota['status'] == $status) {
            $filtered[] = $anggota;
        }
    }
    return $filtered;
}
 
// 7. Function untuk validasi email
function validasi_email($email) {
    if (empty($email)) {
        return false;
    }
    if (strpos($email, '@') === false) {
        return false;
    }
    if (strpos($email, '.') === false) {
        return false;
    }
    return true;
}
 
// 8. Function untuk format tanggal Indonesia
function format_tanggal_indo($tanggal) {
    $bulan_indo = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    
    $pecahkan = explode('-', $tanggal);
    
    if (count($pecahkan) != 3) {
        return $tanggal;
    }
    
    // index 0 = tahun, 1 = bulan, 2 = tanggal
    return $pecahkan[2] . ' ' . $bulan_indo[$pecahkan[1]] . ' ' . $pecahkan[0];
}

// 9. Function untuk sort anggota by nama (A-Z)
function sort_anggota_by_nama($anggota_list) {
    usort($anggota_list, function($a, $b) {
        return strcmp($a['nama'], $b['nama']);
    });
    return $anggota_list;
}

// 10. Function untuk search anggota by nama (partial match)
function search_anggota_by_nama($anggota_list, $keyword) {
    if (empty($keyword)) {
        return $anggota_list;
    }
    
    $filtered = [];
    $keyword = strtolower($keyword);
    foreach ($anggota_list as $anggota) {
        if (strpos(strtolower($anggota['nama']), $keyword) !== false) {
            $filtered[] = $anggota;
        }
    }
    return $filtered;
}
?>
