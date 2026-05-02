<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div id="authModal" class="auth-modal">
  <div class="auth-box">
    <button type="button" class="auth-close" onclick="closeAuth()">&times;</button>
    <div class="text-center mb-4">
      <h2 class="brand">DesainIn</h2>
      <p class="text-muted">Masuk ke akun Anda atau daftar untuk membuat pesanan.</p>
    </div>

    <?php if (!empty($_SESSION['auth_error'])): ?>
      <div class="auth-alert"><?= htmlspecialchars($_SESSION['auth_error']); ?></div>
      <?php unset($_SESSION['auth_error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['auth_success'])): ?>
      <div class="auth-success"><?= htmlspecialchars($_SESSION['auth_success']); ?></div>
      <?php unset($_SESSION['auth_success']); ?>
    <?php endif; ?>

    <div id="loginForm" class="auth-form active">
      <form action="/desainIn/controllers/auth.php?action=login" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Masuk</button>
      </form>
      <p class="small-text">
        Belum punya akun? <a href="#" onclick="showAuthTab('register')">Daftar</a>
      </p>
    </div>

    <div id="registerForm" class="auth-form">
      <form action="/desainIn/controllers/auth.php?action=register" method="POST">
        <input type="text" name="full_name" placeholder="Nama Lengkap" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone" placeholder="Nomor Handphone" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        <button type="submit">Daftar</button>
      </form>
      <p class="small-text">
        Sudah punya akun? <a href="#" onclick="showAuthTab('login')">Masuk</a>
      </p>
    </div>
  </div>
</div>

<?php if (!empty($_SESSION['auth_open'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    showAuthTab('<?php echo !empty($_SESSION['auth_tab']) ? htmlspecialchars($_SESSION['auth_tab']) : 'login'; ?>');
    openAuth();
  });
</script>
<?php unset($_SESSION['auth_open'], $_SESSION['auth_tab']); endif; ?>
