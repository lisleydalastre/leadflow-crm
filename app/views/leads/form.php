<?php
$editing  = $lead !== null;
$pageTitle = $editing ? 'Editar Lead' : 'Nuevo Lead';
$activeMenu = 'leads';
$action   = $editing ? BASE_URL.'/leads/'.$lead['id'].'/update' : BASE_URL.'/leads/store';
?>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0 pt-3 d-flex align-items-center gap-2">
        <a href="<?= BASE_URL ?>/leads" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <h5 class="mb-0"><?= $pageTitle ?></h5>
      </div>
      <div class="card-body">
        <form method="POST" action="<?= $action ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($lead['nombre'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Empresa</label>
              <input type="text" name="empresa" class="form-control" value="<?= htmlspecialchars($lead['empresa'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($lead['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Teléfono / WhatsApp</label>
              <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($lead['telefono'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Fuente <span class="text-danger">*</span></label>
              <select name="fuente_id" class="form-select" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($fuentes as $f): ?>
                  <option value="<?= $f['id'] ?>" <?= ($lead['fuente_id']??'')==$f['id']?'selected':'' ?>><?= htmlspecialchars($f['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
              <select name="estado_id" class="form-select" required>
                <?php foreach ($estados as $e): ?>
                  <option value="<?= $e['id'] ?>" <?= ($lead['estado_id']??'')==$e['id']?'selected':'' ?>><?= htmlspecialchars($e['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Valor estimado ($)</label>
              <input type="number" name="valor_estimado" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($lead['valor_estimado'] ?? '0') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Asignar a</label>
              <select name="usuario_id" class="form-select">
                <option value="">Sin asignar</option>
                <?php foreach ($users as $u): ?>
                  <option value="<?= $u['id'] ?>" <?= ($lead['usuario_id']??'')==$u['id']?'selected':'' ?>><?= htmlspecialchars($u['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Notas</label>
              <textarea name="notas" class="form-control" rows="3"><?= htmlspecialchars($lead['notas'] ?? '') ?></textarea>
            </div>
            <div class="col-12 d-flex gap-2 justify-content-end">
              <a href="<?= BASE_URL ?>/leads" class="btn btn-outline-secondary">Cancelar</a>
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-<?= $editing?'save':'plus-lg' ?> me-1"></i><?= $editing?'Guardar cambios':'Crear Lead' ?>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
