<?php
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — LeadFlow CRM</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body{background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif}
    .login-card{background:#fff;border-radius:16px;padding:2.5rem;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.4)}
    .brand-icon{width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#fff;margin:0 auto 1rem}
    .btn-login{background:linear-gradient(135deg,#6366f1,#818cf8);border:none;color:#fff;padding:.75rem;font-weight:600;letter-spacing:.5px}
    .btn-login:hover{opacity:.9;color:#fff}
    .form-control:focus{border-color:#6366f1;box-shadow:0 0 0 .2rem rgba(99,102,241,.25)}
  </style>
</head>
<body>
  <div class="login-card">
    <div class="text-center mb-4">
      <div class="brand-icon"><i class="bi bi-graph-up-arrow"></i></div>
      <h4 class="fw-bold mb-0">LeadFlow CRM</h4>
      <p class="text-muted small mt-1">Gestión inteligente de leads</p>
    </div>
    <?php if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] ?> py-2 small"><?= htmlspecialchars($flash['msg']) ?></div>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>/login" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control" placeholder="tu@email.com" required autofocus>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="pwd" class="form-control" placeholder="••••••••" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></button>
        </div>
      </div>
      <button type="submit" class="btn btn-login w-100 rounded-3">Iniciar sesión</button>
    </form>
    <p class="text-center text-muted small mt-4 mb-0">
      Demo: <code>admin@leadflow.com</code> / <code>Admin1234!</code>
    </p>
  </div>
  <script>
    function togglePwd(){
      const p=document.getElementById('pwd'), i=document.getElementById('eyeIcon');
      p.type=p.type==='password'?'text':'password';
      i.className=p.type==='text'?'bi bi-eye-slash':'bi bi-eye';
    }
  </script>
</body>
</html>
