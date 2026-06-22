<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/VentaModel.php';
require_once BASE_PATH . '/app/models/LeadModel.php';

/**
 * ReportController - Reportes y análisis de conversión
 */
class ReportController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $inicio = $_GET['inicio'] ?? date('Y-m-01');
        $fin    = $_GET['fin']    ?? date('Y-m-d');

        $ventaModel = new VentaModel();
        $leadModel  = new LeadModel();

        $this->render('reports/index', [
            'inicio'         => $inicio,
            'fin'            => $fin,
            'tasaConversion' => $ventaModel->tasaConversion(),
            'ventasPorFuente'=> $ventaModel->porFuente($inicio, $fin),
            'ventasPorMes'   => $ventaModel->porMes(),
            'leadsPorFuente' => $leadModel->countByFuente(),
        ]);
    }
}
