<?php
// TOTAL HARI
function totalHari($startDate, $endDate)
{
    // Ubah format tanggal menjadi DateTime
    $start = DateTime::createFromFormat('Y-m-d', $startDate);
    $end = DateTime::createFromFormat('Y-m-d', $endDate);

    // Hitung selisih hari antara tanggal awal dan tanggal akhir
    $interval = $start->diff($end);

    // Kembalikan hasil dalam bentuk jumlah hari
    return $interval->days;
}


// TANGGAL TERBILANG
function tanggalTerbilang($date)
{
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'Apri;',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $hari = $dateTime->format('d');
    $bln = $bulan[(int)$dateTime->format('m')];
    $tahun = $dateTime->format('Y');

    return "$hari $bln $tahun";
}

// NAMA BULAN
function namaBulan($nomorBulan)
{
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'Apri;',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    return isset($namaBulan[$nomorBulan]) ? $namaBulan[$nomorBulan] : '';
}

function timeStampToTanggalNamaBulan($timestamp)
{
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($timestamp)));

    $hari = $dateTime->format('d');
    $bulan = namaBulan((int)$dateTime->format('m'));
    $tahun = $dateTime->format('Y');

    return "$hari $bulan $tahun";
}
function timeStampToDate($timestamp)
{
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
    return $dateTime->format('Y-m-d');
}
function getDateFromDate($date)
{
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    return $dateTime->format('d');
}
function dateToMonthName($date)
{
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    $monthNumber = (int)$dateTime->format('m');
    return namaBulan($monthNumber);
}


function timeStampToHourMinute($timestamp)
{
    return date('H:i', strtotime($timestamp));;
}

function getDateFromTimeStamp($timestamp)
{
    return date('d', strtotime($timestamp));
}

function getHourFromTimeStamp($timestamp) {
    if (empty($timestamp) || $timestamp === '0000-00-00 00:00:00') {
        return '';
    }

    $timestampUnix = strtotime($timestamp);
    
    if ($timestampUnix === false) {
        return ''; 
    }

    return date('H:i', $timestampUnix);
}
