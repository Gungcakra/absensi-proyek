<?php
// TOTAL HARI
function totalHari($startDate, $endDate) {
    // Ubah format tanggal menjadi DateTime
    $start = DateTime::createFromFormat('Y-m-d', $startDate);
    $end = DateTime::createFromFormat('Y-m-d', $endDate);
    
    // Hitung selisih hari antara tanggal awal dan tanggal akhir
    $interval = $start->diff($end);
    
    // Kembalikan hasil dalam bentuk jumlah hari
    return $interval->days;
}


// TANGGAL TERBILANG
function tanggalTerbilang($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    $bulan = [
        1 => 'Jan', 
        2 => 'Feb', 
        3 => 'Mar', 
        4 => 'Apr', 
        5 => 'May', 
        6 => 'Jun',
        7 => 'Jul', 
        8 => 'Aug', 
        9 => 'Sep', 
        10 => 'Oct', 
        11 => 'Nov', 
        12 => 'Dec'
    ];

    $hari = $dateTime->format('d');
    $bln = $bulan[(int)$dateTime->format('m')];
    $tahun = $dateTime->format('Y');

    return "$hari $bln $tahun";
}

// NAMA BULAN
function namaBulan($nomorBulan) {
    $namaBulan = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agt',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    ];

    return isset($namaBulan[$nomorBulan]) ? $namaBulan[$nomorBulan] : '';
}
