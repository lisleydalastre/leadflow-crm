<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/LeadModel.php';
require_once BASE_PATH . '/app/models/VentaModel.php';

/**
 * DashboardController - Métricas y resumen ejecutivo
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $leadModel  = new LeadModel();
        $ventaModel = new VentaModel();

        $this->render('dashboard/index', [
            'totalLeads'       => $leadModel->count(),
            'leadsRecientes'   => $leadModel->countRecent(30),
            'totalIngresos'    => $ventaModel->totalIngresos(),
            'ingresosMes'      => $ventaModel->ingresosMesActual(),
            'leadsPorEstado'   => $leadModel->countByEstado(),
            'leadsPorFuente'   => $leadModel->countByFuente(),
            'ventasPorMes'     => $ventaModel->porMes(),
        ]);
    }
}
