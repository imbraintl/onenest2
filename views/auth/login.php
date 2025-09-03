<?php
$title = 'Login - Mummycare';

ob_start();
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Welcome Back</h1>
        
        <?php if (isset($error)): ?>
            <div style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" required 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <div class="auth-link">
            <a href="#">Forgot Password?</a>
        </div>
        <div class="auth-link">
            Don't have an account? <a href="/register">Join Now</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>