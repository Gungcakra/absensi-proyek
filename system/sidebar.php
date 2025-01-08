    <?php
    $menuItems = [
        [
            'title' => 'Dashboards',
            'icon' => '<svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                              </svg>',
            'url' => BASE_URL_HTML . '/system/',
            'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/',
            'submenu' => []
        ],
        [
            'title' => 'Data',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>',
            'url' => '#',
            'active' => false,
            'submenu' => [
                [
                    'title' => 'User',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
                    'url' => BASE_URL_HTML . '/system/data/user/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/data/user/'
                ],
                [
                    'title' => 'Tukang',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
                    'url' => BASE_URL_HTML . '/system/data/tukang/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/data/tukang/'
                ]
            ]
        ]
    ];
    ?>

    <div class="iq-sidebar sidebar-default">
        <div class="iq-sidebar-logo d-flex align-items-center">
            <a href="<?= BASE_URL_HTML ?>/backend/index.html" class="header-logo">
                <img src="<?= BASE_URL_HTML ?>/assets/images/logo.svg" alt="logo">
                <h3 class="logo-title light-logo">TEMPLATE</h3>
            </a>
            <div class="iq-menu-bt-sidebar ml-0">
                <i class="las la-bars wrapper-menu"></i>
            </div>
        </div>
        <div class="data-scrollbar" data-scroll="1">
            <nav class="iq-sidebar-menu">
                <ul id="iq-sidebar-toggle" class="iq-menu">
                    <?php foreach ($menuItems as $item): ?>
                        <li class="<?= $item['active'] ? 'active' : '' ?>">
                            <a href="<?= !empty($item['submenu']) ? '#' . strtolower(str_replace(' ', '-', $item['title'])) : $item['url'] ?>"
                                class="svg-icon <?= !empty($item['submenu']) ? 'collapsed' : 'no-submenu' ?>"
                                <?= !empty($item['submenu']) ? 'data-toggle="collapse" aria-expanded="false"' : '' ?>>
                                <?= $item['icon'] ?>
                                <span class="ml-4"><?= $item['title'] ?></span>
                                <?php if (!empty($item['submenu'])): ?>
                                    <i class="las la-angle-right iq-arrow-right arrow-active"></i>
                                    <i class="las la-angle-down iq-arrow-right arrow-hover"></i>
                                <?php endif; ?>
                            </a>
                            <?php if (!empty($item['submenu'])): ?>
                                <ul id="<?= strtolower(str_replace(' ', '-', $item['title'])) ?>" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <?php foreach ($item['submenu'] as $submenu): ?>
                                        <li class="<?= $submenu['active'] ? 'active' : '' ?>">
                                            <a href="<?= $submenu['url'] ?>" class="svg-icon">
                                                <?= $submenu['icon'] ?>
                                                <span class="ml-4"><?= $submenu['title'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>