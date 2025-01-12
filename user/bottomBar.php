<?php
require_once '../library/config.php';

// Navigasi
$navItems = [
    ['id' => 'search', 'icon' => 'fas fa-search', 'path' => '/search'],
    ['id' => 'notifications', 'icon' => 'fas fa-bell', 'path' => '/notifications'],
    ['id' => 'home', 'icon' => 'fas fa-home', 'path' => '/user/'],
    ['id' => 'messages', 'icon' => 'fas fa-envelope', 'path' => '/messages'],
    ['id' => 'profile', 'icon' => 'fas fa-user', 'path' => '/profile'],
];

// Ambil path saat ini dari URL
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan BASE_URL_HTML dari currentPath
$currentPath = str_replace(rtrim(BASE_URL_HTML, '/'), '', $currentPath);

?>

<nav class="navbar navbar-light navbar-expand rounded-pill mb-3 ms-3 me-3 fixed-bottom d-md-none d-lg-none d-xl-none shadow" style="background: #ffffff;">
    <ul class="nav nav-justified w-100" id="myTab" role="tablist">
        <?php foreach ($navItems as $item): ?>
            <?php 
            // Gabungkan BASE_URL_HTML dan path item
            $fullPath = rtrim(BASE_URL_HTML, '/') . $item['path'];
            
            // Periksa apakah currentPath cocok dengan item path
            $isActive = trim($currentPath, '/') === trim($item['path'], '/');
            ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" id="<?php echo $item['id']; ?>-tab" href="<?php echo $fullPath; ?>" role="tab" aria-controls="<?php echo $item['id']; ?>" aria-selected="<?php echo $isActive ? 'true' : 'false'; ?>">
                    <span><i class="<?php echo $item['icon']; ?> <?php echo $isActive ? 'text-white' : 'text-dark'; ?>" style="padding: 10px; <?php  echo $isActive ? 'background: #007bff; border-radius: 50%;' : ''; ?>"></i></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
