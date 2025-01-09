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

function timeStampToTanggalNamaBulan($timestamp) {
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($timestamp)));
    
    $hari = $dateTime->format('d');
    $bulan = namaBulan((int)$dateTime->format('m'));
    $tahun = $dateTime->format('Y');
    
    return "$hari $bulan $tahun";
}
function timeStampToDate($timestamp) {
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
    return $dateTime->format('Y-m-d');
}
function getDateFromDate($date){
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    return $dateTime->format('d');
}
function dateToMonthName($date){
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    $monthNumber = (int)$dateTime->format('m');
    return namaBulan($monthNumber);
}