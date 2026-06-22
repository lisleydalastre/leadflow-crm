<?php $pageTitle = 'Detalle del Lead'; $activeMenu = 'leads'; ?>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <a href="<?= BASE_URL ?>/leads" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
          <h5 class="mb-0"><?= htmlspecialchars($lead['nombre']) ?></h5>
        </div>
        <a href="<?= BASE_URL ?>/leads/<?= $lead['id'] ?>/edit" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
      </div>
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-5 text-muted small">Empresa</dt>
          <dd class="col-7"><?= htmlspecialchars($lead['empresa'] ?? '—') ?></dd>
          <dt class="col-5 text-muted small">Email</dt>
          <dd class="col-7"><?= htmlspecialchars($lead['email'] ?? '—') ?></dd>
          <dt class="col-5 text-muted small">Teléfono</dt>
          <dd class="col-7"><?= htmlspecialchars($lead['telefono'] ?? '—') ?></dd>
          <dt class="col-5 text-muted small">Fuente</dt>
          <dd class="col-7"><span class="badge" style="background:<?= $lead['fuente_color'] ?>22;color:<?= $lead['fuente_color'] ?>;border:1px solid <?= $lead['fuente_color'] ?>"><i class="bi <?= $lead['fuente_icono'] ?> me-1"></i><?= htmlspecialchars($lead['fuente_nombre']) ?></span></dd>
          <dt class="col-5 text-muted small">Estado</dt>
          <dd class="col-7"><span class="badge rounded-pill" style="background:<?= $lead['estado_color'] ?>"><?= htmlspecialchars($lead['estado_nombre']) ?></span></dd>
          <dt class="col-5 text-muted small">Valor est.</dt>
          <dd class="col-7 fw-bold text-success">$<?= number_format((float)$lead['valor_estimado'],0,',','.') ?></dd>
          <dt class="col-5 text-muted small">Asignado</dt>
          <dd class="col-7"><?= htmlspecialchars($lead['usuario_nombre'] ?? '—') ?></dd>
          <dt class="col-5 text-muted small">Notas</dt>
          <dd class="col-7 small"><?= nl2br(htmlspecialchars($lead['notas'] ?? '—')) ?></dd>
        </dl>
        <hr>
        <a href="<?= BASE_URL ?>/ventas/create?lead_id=<?= $lead['id'] ?>" class="btn btn-success w-100">
          <i class="bi bi-cash-coin me-1"></i>Registrar Venta
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0 pt-3 fw-semibold">Historial de estados</div>
      <div class="card-body p-0">
        <?php if (empty($historial)): ?>
          <p class="text-muted text-center py-4 mb-0">Sin movimientos registrados</p>
        <?php endif; ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($historial as $h): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start py-3">
              <div>
                <div class="small text-muted"><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?> · <?= htmlspecialchars($h['usuario_nombre'] ?? 'Sistema') ?></div>
                <div class="mt-1">
                  <?php if ($h['estado_anterior_nombre']): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($h['estado_anterior_nombre']) ?></span>
                    <i class="bi bi-arrow-right text-muted mx-1"></i>
                  <?php endif; ?>
                  <span class="badge bg-primary"><?= htmlspecialchars($h['estado_nuevo_nombre']) ?></span>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
