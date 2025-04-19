<?php
session_start();
$config = require 'config.php';

date_default_timezone_set('Europe/Budapest');

$page = $_GET['page'] ?? 'home';

require_once 'users.php';

function is_logged_in()
{
    return isset($_SESSION['user']);
}

?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($config['site_title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><?= htmlspecialchars($config['site_title']) ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach ($config['menu'] as $key => $value): ?>
                        <?php
                        if ($key === 'messages' && !is_logged_in()) continue;
                        ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $page === $key ? ' active' : '' ?>" href="index.php?page=<?= $key ?>"><?= htmlspecialchars($value) ?></a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (!is_logged_in()): ?>
                        <li class="nav-item"><a class="nav-link<?= $page === 'login' ? ' active' : '' ?>" href="index.php?page=login">Belépés</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=logout">Kilépés</a></li>
                    <?php endif; ?>
                </ul>
                <?php if (is_logged_in()): ?>
                    <span class="navbar-text">
                        Bejelentkezett: <?= htmlspecialchars($_SESSION['user']['lastname'] . ' ' . $_SESSION['user']['firstname'] . ' (' . $_SESSION['user']['username'] . ')') ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php
        $controller = "controllers/{$page}.php";
        if (file_exists($controller)) {
            require $controller;
        } else {
            echo "<h1>Az oldal nem található!</h1>";
        }
        ?>
    </div>
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>Mózer Máté (XN87YG)</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Web-programozás 1 - Beadandó feladat</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>