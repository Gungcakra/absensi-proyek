<?php

// KONVERSI  RUPIAH
function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function ubahKePersen($currentValue, $totalValue) {
    if ($totalValue == 0) {
        return 0; 
    }
    
    $percentage = ($currentValue / $totalValue) * 100;
    
    return number_format($percentage, 2) . '%';
}