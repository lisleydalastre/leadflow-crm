<?php $pageTitle = 'Reportes de Conversión'; $activeMenu = 'reportes'; ?>

<!-- Filtro de fechas -->
<form method="GET" class="card stat-card p-3 mb-4">
  <div class="row g-2 align-items-end">
    <div class="col-md-4">
      <label class="form-label small fw-semibold">Desde</label>
      <input type="date" name="inicio" class="form-control form-control-sm" value="<?= htmlspecialchars($inicio) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label small fw-semibold">Hasta</label>
      <input type="date" name="fin" class="form-control form-control-sm" value="<?= htmlspecialchars($fin) ?>">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i>Filtrar</button>
    </div>
  </div>
</form>

<!-- Tasa de conversión por fuente -->
<div class="row g-3 mb-4">
  <div class="col-lg-7">
    <div class="card stat-card h-100">
      <div class="card-header bg-transparent border-0 pt-3 fw-semibold">Tasa de conversión por fuente</div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light"><tr><th>Fuente</th><th class="text-center">Leads</th><th class="text-center">Ventas</th><th>Tasa</th></tr></thead>
          <tbody>
            <?php foreach ($tasaConversion as $t): ?>
              <tr>
                <td><span class="badge" style="background:<?= $t['color'] ?>22;color:<?= $t['color'] ?>;border:1px solid <?= $t['color'] ?>"><?= htmlspecialchars($t['nombre']) ?></span></td>
                <td class="text-center"><?= $t['total_leads'] ?></td>
                <td class="text-center"><?= $t['total_ventas'] ?></td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height:8px">
                      <div class="progress-bar" style="width:<?= $t['tasa'] ?>%;background:<?= $t['color'] ?>"></div>
                    </div>
                    <span class="fw-bold small" style="color:<?= $t['color'] ?>"><?= $t['tasa'] ?>%</span>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card stat-card h-100">
      <div class="card-header bg-transparent border-0 pt-3 fw-semibold">Ingresos por fuente (período)</div>
      <div class="card-body"><canvas id="chartIngresosFuente"></canvas></div>
    </div>
  </div>
</div>

<!-- Evolución mensual -->
<div class="card stat-card">
  <div class="card-header bg-transparent border-0 pt-3 fw-semibold">Evolución de ventas mensual</div>
  <div class="card-body"><canvas id="chartMensual" height="80"></canvas></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const vf = <?= json_encode($ventasPorFuente) ?>;
  new Chart(document.getElementById('chartIngresosFuente'), {
    type:'doughnut',
    data:{
      labels:vf.map(r=>r.nombre),
      datasets:[{data:vf.map(r=>parseFloat(r.total||0)),backgroundColor:vf.map(r=>r.color)}]
    },
    options:{responsive:true,plugins:{legend:{position:'bottom'}}}
  });

  const vm = <?= json_encode($ventasPorMes) ?>;
  new Chart(document.getElementById('chartMensual'), {
    type:'line',
    data:{
      labels:vm.map(r=>r.mes),
      datasets:[{
        label:'Ingresos ($)',
        data:vm.map(r=>parseFloat(r.total)),
        borderColor:'#6366f1',backgroundColor:'rgba(99,102,241,.1)',
        tension:.4,fill:true,pointBackgroundColor:'#6366f1'
      }]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>'$'+v.toLocaleString()}}}}
  });
});
</script>
