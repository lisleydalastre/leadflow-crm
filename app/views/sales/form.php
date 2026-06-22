<?php $pageTitle = 'Nueva Venta'; $activeMenu = 'ventas'; ?>

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0 pt-3 d-flex align-items-center gap-2">
        <a href="<?= BASE_URL ?>/ventas" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h5 class="mb-0">Registrar Venta</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/ventas/store">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Lead <span class="text-danger">*</span></label>
              <select name="lead_id" class="form-select" required>
                <option value="">Seleccionar lead...</option>
                <?php foreach ($leads as $l): ?>
                  <option value="<?= $l['id'] ?>" <?= $leadId==$l['id']?'selected':'' ?>><?= htmlspecialchars($l['nombre']) ?><?= $l['empresa']?' — '.htmlspecialchars($l['empresa']):'' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Producto / Servicio <span class="text-danger">*</span></label>
              <input type="text" name="producto" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Monto ($) <span class="text-danger">*</span></label>
              <input type="number" name="monto" class="form-control" min="0" step="0.01" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Fecha de venta <span class="text-danger">*</span></label>
              <input type="date" name="fecha_venta" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Método de pago</label>
              <select name="metodo_pago" class="form-select">
                <option value="">Seleccionar...</option>
                <option>Transferencia</option><option>Efectivo</option>
                <option>Tarjeta crédito</option><option>Tarjeta débito</option>
                <option>PayPal</option><option>Nequi</option><option>Daviplata</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Notas</label>
              <textarea name="notas" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-12 d-flex gap-2 justify-content-end">
              <a href="<?= BASE_URL ?>/ventas" class="btn btn-outline-secondary">Cancelar</a>
              <button type="submit" class="btn btn-success px-4"><i class="bi bi-cash-coin me-1"></i>Registrar Venta</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
