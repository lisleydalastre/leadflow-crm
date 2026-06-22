<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/VentaModel.php';
require_once BASE_PATH . '/app/models/LeadModel.php';

/**
 * VentaController - Registro de conversiones / ventas
 */
class VentaController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $model = new VentaModel();
        $this->render('sales/index', [
            'ventas'        => $model->getAllWithRelations(),
            'totalIngresos' => $model->totalIngresos(),
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        // Se puede pre-seleccionar un lead desde la URL
        $leadId    = (int) ($_GET['lead_id'] ?? 0);
        $leadModel = new LeadModel();

        $this->render('sales/form', [
            'venta'  => null,
            'leads'  => $leadModel->getAllWithRelations(),
            'leadId' => $leadId,
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $data = [
            'lead_id'     => (int) $this->input('lead_id'),
            'usuario_id'  => $_SESSION['user_id'],
            'monto'       => (float) $this->input('monto'),
            'producto'    => $this->input('producto'),
            'fecha_venta' => $this->input('fecha_venta'),
            'metodo_pago' => $this->input('metodo_pago') ?: null,
            'notas'       => $this->input('notas') ?: null,
        ];

        if (!$data['lead_id'] || !$data['monto'] || !$data['producto'] || !$data['fecha_venta']) {
            $this->flash('danger', 'Completa los campos obligatorios.');
            $this->redirect('ventas/create');
            return;
        }

        (new VentaModel())->create($data);
        $this->flash('success', 'Venta registrada exitosamente.');
        $this->redirect('ventas');
    }

    public function delete(string $id): void
    {
        $this->requireRole('admin');
        (new VentaModel())->delete((int) $id);
        $this->flash('success', 'Venta eliminada.');
        $this->redirect('ventas');
    }
}
