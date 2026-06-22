<?php $pageTitle = 'Leads'; $activeMenu = 'leads'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <span class="badge bg-primary fs-6"><?= number_format($total) ?> leads</span>
  </div>
  <a href="<?= BASE_URL ?>/leads/create" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i>Nuevo Lead
  </a>
</div>

<!-- Filtros -->
<div class="card stat-card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar nombre, email, teléfono..." value="<?= htmlspecialchars($filters['search']) ?>">
      </div>
      <div class="col-md-3">
        <select name="fuente_id" class="form-select form-select-sm">
          <option value="">Todas las fuentes</option>
          <?php foreach ($fuentes as $f): ?>
            <option value="<?= $f['id'] ?>" <?= $filters['fuente_id']==$f['id']?'selected':'' ?>><?= htmlspecialchars($f['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="estado_id" class="form-select form-select-sm">
          <option value="">Todos los estados</option>
          <?php foreach ($estados as $e): ?>
            <option value="<?= $e['id'] ?>" <?= $filters['estado_id']==$e['id']?'selected':'' ?>><?= htmlspecialchars($e['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-1">
        <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-search"></i></button>
        <a href="<?= BASE_URL ?>/leads" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
      </div>
    </form>
  </div>
</div>

<!-- Tabla -->
<div class="card stat-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th><th>Nombre</th><th>Contacto</th><th>Fuente</th><th>Estado</th><th>Valor est.</th><th>Asignado</th><th>Fecha</th><th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leads)): ?>
          <tr><td colspan="9" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay leads con esos filtros</td></tr>
        <?php endif; ?>
        <?php foreach ($leads as $l): ?>
          <tr>
            <td class="text-muted small"><?= $l['id'] ?></td>
            <td>
              <a href="<?= BASE_URL ?>/leads/<?= $l['id'] ?>" class="fw-semibold text-decoration-none text-dark">
                <?= htmlspecialchars($l['nombre']) ?>
              </a>
              <?php if ($l['empresa']): ?>
                <div class="text-muted small"><?= htmlspecialchars($l['empresa']) ?></div>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($l['email']): ?><div class="small"><i class="bi bi-envelope text-muted"></i> <?= htmlspecialchars($l['email']) ?></div><?php endif; ?>
              <?php if ($l['telefono']): ?><div class="small"><i class="bi bi-telephone text-muted"></i> <?= htmlspecialchars($l['telefono']) ?></div><?php endif; ?>
            </td>
            <td>
              <span class="badge" style="background:<?= htmlspecialchars($l['fuente_color']) ?>22;color:<?= htmlspecialchars($l['fuente_color']) ?>;border:1px solid <?= htmlspecialchars($l['fuente_color']) ?>">
                <i class="bi <?= htmlspecialchars($l['fuente_icono']) ?> me-1"></i><?= htmlspecialchars($l['fuente_nombre']) ?>
              </span>
            </td>
            <td>
              <span class="badge rounded-pill" style="background:<?= htmlspecialchars($l['estado_color']) ?>;color:#fff">
                <?= htmlspecialchars($l['estado_nombre']) ?>
              </span>
            </td>
            <td class="fw-semibold text-success">$<?= number_format((float)$l['valor_estimado'],0,',','.') ?></td>
            <td class="small text-muted"><?= htmlspecialchars($l['usuario_nombre'] ?? '—') ?></td>
            <td class="small text-muted"><?= date('d/m/Y', strtotime($l['created_at'])) ?></td>
            <td>
              <div class="d-flex gap-1">
                <a href="<?= BASE_URL ?>/leads/<?= $l['id'] ?>/edit" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                <a href="<?= BASE_URL ?>/ventas/create?lead_id=<?= $l['id'] ?>" class="btn btn-sm btn-outline-success" title="Registrar venta"><i class="bi bi-cash"></i></a>
                <form method="POST" action="<?= BASE_URL ?>/leads/<?= $l['id'] ?>/delete" onsubmit="return confirm('¿Eliminar este lead?')">
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- Paginación -->
  <?php if ($totalPages > 1): ?>
    <div class="card-footer d-flex justify-content-between align-items-center">
      <small class="text-muted">Página <?= $page ?> de <?= $totalPages ?></small>
      <nav>
        <ul class="pagination pagination-sm mb-0">
          <?php for ($p=1; $p<=$totalPages; $p++): ?>
            <li class="page-item <?= $p===$page?'active':'' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($filters,['page'=>$p])) ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  <?php endif; ?>
</div>
