<?php $pageTitle = 'Ventas'; $activeMenu = 'ventas'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div class="fs-5 fw-bold text-success"><i class="bi bi-currency-dollar"></i> Total: $<?= number_format($totalIngresos, 0, ',', '.') ?></div>
  <a href="<?= BASE_URL ?>/ventas/create" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Nueva Venta</a>
</div>

<div class="card stat-card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr><th>Fecha</th><th>Lead</th><th>Producto</th><th>Monto</th><th>Fuente</th><th>Pago</th><th>Vendedor</th><th></th></tr>
      </thead>
      <tbody>
        <?php if (empty($ventas)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4"><i class="bi bi-inbox fs-4 d-block mb-2"></i>No hay ventas registradas aún</td></tr>
        <?php endif; ?>
        <?php foreach ($ventas as $v): ?>
          <tr>
            <td class="small"><?= date('d/m/Y', strtotime($v['fecha_venta'])) ?></td>
            <td>
              <a href="<?= BASE_URL ?>/leads/<?= $v['lead_id'] ?>" class="text-decoration-none fw-semibold"><?= htmlspecialchars($v['lead_nombre']) ?></a>
              <?php if ($v['lead_empresa']): ?><div class="small text-muted"><?= htmlspecialchars($v['lead_empresa']) ?></div><?php endif; ?>
            </td>
            <td><?= htmlspecialchars($v['producto']) ?></td>
            <td class="fw-bold text-success">$<?= number_format((float)$v['monto'], 0, ',', '.') ?></td>
            <td><span class="badge" style="background:<?= $v['fuente_color'] ?>22;color:<?= $v['fuente_color'] ?>;border:1px solid <?= $v['fuente_color'] ?>"><?= htmlspecialchars($v['fuente_nombre']) ?></span></td>
            <td class="small"><?= htmlspecialchars($v['metodo_pago'] ?? '—') ?></td>
            <td class="small text-muted"><?= htmlspecialchars($v['usuario_nombre'] ?? '—') ?></td>
            <td>
              <form method="POST" action="<?= BASE_URL ?>/ventas/<?= $v['id'] ?>/delete" onsubmit="return confirm('¿Eliminar esta venta?')">
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
