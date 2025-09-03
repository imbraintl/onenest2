<?php
$title = '404 - Page Not Found';

ob_start();
?>

<div style="text-align: center; padding: 100px 20px;">
    <div class="container">
        <h1 style="font-size: 6rem; color: var(--primary-color); margin-bottom: 20px;">404</h1>
        <h2 style="margin-bottom: 20px;">Page Not Found</h2>
        <p style="margin-bottom: 30px; color: #666;">The page you're looking for doesn't exist.</p>
        <a href="/" class="btn btn-primary">Go Home</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>