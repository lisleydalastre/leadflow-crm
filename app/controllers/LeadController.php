<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/LeadModel.php';
require_once BASE_PATH . '/app/models/FuenteModel.php';
require_once BASE_PATH . '/app/models/EstadoModel.php';
require_once BASE_PATH . '/app/models/UserModel.php';

/**
 * LeadController - CRUD de leads y cambio de estados
 */
class LeadController extends Controller
{
    private const PER_PAGE = 15;

    private LeadModel  $leads;
    private FuenteModel $fuentes;
    private EstadoModel $estados;

    public function __construct()
    {
        $this->leads   = new LeadModel();
        $this->fuentes = new FuenteModel();
        $this->estados = new EstadoModel();
    }

    /** Listado con filtros y paginación */
    public function index(): void
    {
        $this->requireAuth();

        $filters = [
            'fuente_id' => (int) ($_GET['fuente_id'] ?? 0),
            'estado_id' => (int) ($_GET['estado_id'] ?? 0),
            'search'    => trim($_GET['search'] ?? ''),
        ];

        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * self::PER_PAGE;
        $total  = $this->leads->countFiltered($filters);

        $this->render('leads/index', [
            'leads'      => $this->leads->getAllWithRelations($filters, self::PER_PAGE, $offset),
            'fuentes'    => $this->fuentes->allActivas(),
            'estados'    => $this->estados->allOrdenados(),
            'filters'    => $filters,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => (int) ceil($total / self::PER_PAGE),
        ]);
    }

    /** Formulario de creación */
    public function create(): void
    {
        $this->requireAuth();
        $this->render('leads/form', [
            'lead'    => null,
            'fuentes' => $this->fuentes->allActivas(),
            'estados' => $this->estados->allOrdenados(),
            'users'   => (new UserModel())->listActivos(),
        ]);
    }

    /** Persiste un nuevo lead */
    public function store(): void
    {
        $this->requireAuth();

        $data = [
            'nombre'         => $this->input('nombre'),
            'email'          => $this->input('email') ?: null,
            'telefono'       => $this->input('telefono') ?: null,
            'empresa'        => $this->input('empresa') ?: null,
            'fuente_id'      => (int) $this->input('fuente_id'),
            'estado_id'      => (int) $this->input('estado_id'),
            'usuario_id'     => (int) $this->input('usuario_id') ?: null,
            'notas'          => $this->input('notas') ?: null,
            'valor_estimado' => (float) $this->input('valor_estimado', 0),
        ];

        if (!$data['nombre'] || !$data['fuente_id'] || !$data['estado_id']) {
            $this->flash('danger', 'Nombre, fuente y estado son obligatorios.');
            $this->redirect('leads/create');
            return;
        }

        $id = $this->leads->create($data);

        // Registrar historial
        $this->registrarHistorial($id, null, $data['estado_id']);

        $this->flash('success', 'Lead creado correctamente.');
        $this->redirect('leads');
    }

    /** Formulario de edición */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $lead = $this->leads->findWithRelations((int) $id);
        if (!$lead) { $this->redirect('leads'); }

        $this->render('leads/form', [
            'lead'    => $lead,
            'fuentes' => $this->fuentes->allActivas(),
            'estados' => $this->estados->allOrdenados(),
            'users'   => (new UserModel())->listActivos(),
        ]);
    }

    /** Actualiza un lead existente */
    public function update(string $id): void
    {
        $this->requireAuth();
        $leadId  = (int) $id;
        $actual  = $this->leads->find($leadId);
        if (!$actual) { $this->redirect('leads'); }

        $nuevoEstado = (int) $this->input('estado_id');

        $data = [
            'nombre'         => $this->input('nombre'),
            'email'          => $this->input('email') ?: null,
            'telefono'       => $this->input('telefono') ?: null,
            'empresa'        => $this->input('empresa') ?: null,
            'fuente_id'      => (int) $this->input('fuente_id'),
            'estado_id'      => $nuevoEstado,
            'usuario_id'     => (int) $this->input('usuario_id') ?: null,
            'notas'          => $this->input('notas') ?: null,
            'valor_estimado' => (float) $this->input('valor_estimado', 0),
        ];

        $this->leads->update($leadId, $data);

        // Registrar cambio de estado solo si cambió
        if ($actual['estado_id'] !== $nuevoEstado) {
            $this->registrarHistorial($leadId, (int) $actual['estado_id'], $nuevoEstado);
        }

        $this->flash('success', 'Lead actualizado.');
        $this->redirect('leads');
    }

    /** Elimina un lead */
    public function delete(string $id): void
    {
        $this->requireRole('admin');
        $this->leads->delete((int) $id);
        $this->flash('success', 'Lead eliminado.');
        $this->redirect('leads');
    }

    /** Detalle de un lead con historial */
    public function show(string $id): void
    {
        $this->requireAuth();
        $lead = $this->leads->findWithRelations((int) $id);
        if (!$lead) { $this->redirect('leads'); }

        $db = Database::getInstance();
        $stmt = $db->prepare(
            "SELECT h.*, ea.nombre AS estado_anterior_nombre, en.nombre AS estado_nuevo_nombre,
                    u.nombre AS usuario_nombre
             FROM historial_estados h
             LEFT JOIN estados_lead ea ON h.estado_anterior = ea.id
             LEFT JOIN estados_lead en ON h.estado_nuevo    = en.id
             LEFT JOIN usuarios     u  ON h.usuario_id      = u.id
             WHERE h.lead_id = ?
             ORDER BY h.created_at DESC"
        );
        $stmt->execute([(int) $id]);

        $this->render('leads/show', [
            'lead'      => $lead,
            'historial' => $stmt->fetchAll(),
        ]);
    }

    // -------------------------------------------------------
    // Métodos privados
    // -------------------------------------------------------
    private function registrarHistorial(int $leadId, ?int $anterior, int $nuevo): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare(
            "INSERT INTO historial_estados (lead_id, estado_anterior, estado_nuevo, usuario_id)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$leadId, $anterior, $nuevo, $_SESSION['user_id'] ?? null]);
    }
}
