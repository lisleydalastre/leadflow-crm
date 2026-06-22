<?php $pageTitle = 'Dashboard'; $activeMenu = 'dashboard'; ?>

<!-- Tarjetas de métricas -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="card stat-card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
        <div>
          <div class="fs-4 fw-bold"><?= number_format($totalLeads) ?></div>
          <div class="text-muted small">Total Leads</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card stat-card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-person-plus-fill"></i></div>
        <div>
          <div class="fs-4 fw-bold"><?= number_format($leadsRecientes) ?></div>
          <div class="text-muted small">Leads (30 días)</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card stat-card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-currency-dollar"></i></div>
        <div>
          <div class="fs-4 fw-bold">$<?= number_format($ingresosMes, 0, ',', '.') ?></div>
          <div class="text-muted small">Ingresos del mes</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="card stat-card h-100">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="icon-box bg-info bg-opacity-10 text-info"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
          <div class="fs-4 fw-bold">$<?= number_format($totalIngresos, 0, ',', '.') ?></div>
          <div class="text-muted small">Ingresos totales</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Gráficas -->
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="card stat-card h-100">
      <div class="card-header bg-transparent border-0 fw-semibold pt-3">Ventas por mes (últimos 12 meses)</div>
      <div class="card-body"><canvas id="chartVentas" height="100"></canvas></div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card stat-card h-100">
      <div class="card-header bg-transparent border-0 fw-semibold pt-3">Leads por fuente</div>
      <div class="card-body d-flex align-items-center"><canvas id="chartFuentes"></canvas></div>
    </div>
  </div>
</div>

<!-- Pipeline por estado -->
<div class="card stat-card">
  <div class="card-header bg-transparent border-0 fw-semibold pt-3">Pipeline comercial</div>
  <div class="card-body">
    <div class="row g-2">
      <?php foreach ($leadsPorEstado as $e): ?>
        <div class="col-6 col-md-3">
          <div class="p-3 rounded-3" style="background:<?= htmlspecialchars($e['color']) ?>18;border-left:4px solid <?= htmlspecialchars($e['color']) ?>">
            <div class="fw-bold fs-4" style="color:<?= htmlspecialchars($e['color']) ?>"><?= (int)$e['total'] ?></div>
            <div class="small text-muted"><?= htmlspecialchars($e['nombre']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  // Ventas por mes
  const vm = <?= json_encode($ventasPorMes) ?>;
  new Chart(document.getElementById('chartVentas'), {
    type:'bar',
    data:{
      labels: vm.map(r=>r.mes),
      datasets:[{
        label:'Ingresos ($)',
        data: vm.map(r=>parseFloat(r.total)),
        backgroundColor:'rgba(99,102,241,.7)',
        borderRadius:6
      }]
    },
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{ticks:{callback:v=>'$'+v.toLocaleString()}}}}
  });

  // Fuentes
  const f = <?= json_encode($leadsPorFuente) ?>;
  new Chart(document.getElementById('chartFuentes'), {
    type:'doughnut',
    data:{
      labels: f.map(r=>r.nombre),
      datasets:[{data:f.map(r=>parseInt(r.total)),backgroundColor:f.map(r=>r.color)}]
    },
    options:{responsive:true,plugins:{legend:{position:'bottom'}}}
  });
});
</script>
