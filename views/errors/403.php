<?php
$title = '403 - Access Denied';

ob_start();
?>

<div style="text-align: center; padding: 100px 20px;">
    <div class="container">
        <h1 style="font-size: 6rem; color: var(--error-color); margin-bottom: 20px;">403</h1>
        <h2 style="margin-bottom: 20px;">Access Denied</h2>
        <p style="margin-bottom: 30px; color: #666;">You don't have permission to access this page.</p>
        <a href="/" class="btn btn-primary">Go Home</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>