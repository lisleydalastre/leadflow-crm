<?php
$flash   = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$user    = $_SESSION['user_nombre'] ?? 'Usuario';
$rol     = $_SESSION['user_rol']    ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> — LeadFlow CRM</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js" defer></script>
  <style>
    :root{--sw:240px;--sbg:#0f172a;--shv:#1e293b;--ac:#6366f1;--acl:#818cf8}
    body{background:#f1f5f9;font-family:'Segoe UI',sans-serif}
    #sidebar{width:var(--sw);min-height:100vh;background:var(--sbg);position:fixed;top:0;left:0;display:flex;flex-direction:column;z-index:1000;transition:width .25s}
    #sidebar .brand{padding:1.25rem 1rem;border-bottom:1px solid var(--shv)}
    #sidebar .brand span{font-size:1.1rem;font-weight:700;color:var(--acl)}
    .snav .nav-link{color:#94a3b8;padding:.6rem 1rem;border-radius:8px;margin:2px 8px;display:flex;align-items:center;gap:.6rem;font-size:.9rem;transition:background .2s,color .2s}
    .snav .nav-link:hover,.snav .nav-link.active{background:var(--shv);color:#fff}
    .snav .nav-link.active{color:var(--acl)}
    .snav .nav-link i{font-size:1.1rem;min-width:20px}
    #main{margin-left:var(--sw);min-height:100vh}
    #topbar{background:#fff;border-bottom:1px solid #e2e8f0;padding:.75rem 1.5rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:900}
    .page-content{padding:1.5rem}
    .stat-card{border:none;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);transition:transform .2s}
    .stat-card:hover{transform:translateY(-2px)}
    .icon-box{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem}
    @media(max-width:768px){#sidebar{width:0;overflow:hidden}#sidebar.open{width:var(--sw)}#main{margin-left:0}}
  </style>
</head>
<body>
<!-- SIDEBAR -->
<nav id="sidebar">
  <div class="brand d-flex align-items-center gap-2">
    <i class="bi bi-graph-up-arrow" style="color:var(--acl);font-size:1.4rem"></i>
    <span>LeadFlow CRM</span>
  </div>
  <ul class="snav nav flex-column mt-2 flex-grow-1">
    <li><a href="<?= BASE_URL ?>/dashboard" class="nav-link <?= ($activeMenu??'')==='dashboard'?'active':'' ?>"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>/leads"     class="nav-link <?= ($activeMenu??'')==='leads'?'active':'' ?>"><i class="bi bi-person-lines-fill"></i>Leads</a></li>
    <li><a href="<?= BASE_URL ?>/ventas"    class="nav-link <?= ($activeMenu??'')==='ventas'?'active':'' ?>"><i class="bi bi-cash-coin"></i>Ventas</a></li>
    <li><a href="<?= BASE_URL ?>/reportes"  class="nav-link <?= ($activeMenu??'')==='reportes'?'active':'' ?>"><i class="bi bi-bar-chart-line"></i>Reportes</a></li>
  </ul>
  <div class="p-3" style="border-top:1px solid var(--shv)">
    <small class="text-secondary d-block"><?= htmlspecialchars($user) ?></small>
    <span class="badge bg-secondary text-uppercase"><?= htmlspecialchars($rol) ?></span>
    <a href="<?= BASE_URL ?>/logout" class="btn btn-sm btn-outline-danger mt-2 w-100"><i class="bi bi-box-arrow-right"></i> Salir</a>
  </div>
</nav>
<!-- MAIN -->
<div id="main">
  <div id="topbar">
    <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="bi bi-list fs-5"></i></button>
    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($pageTitle ?? '') ?></h6>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y') ?></span>
  </div>
  <div class="page-content">
    <?php if ($flash): ?>
      <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
        <i class="bi bi-<?= $flash['type']==='success'?'check-circle':'exclamation-triangle' ?>-fill me-2"></i>
        <?= htmlspecialchars($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <?= $content ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
