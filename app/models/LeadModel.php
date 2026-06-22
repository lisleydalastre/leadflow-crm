<?php
/**
 * LeadModel - Operaciones sobre la tabla leads
 */
class LeadModel extends Model
{
    protected string $table = 'leads';

    /**
     * Retorna leads con JOIN a fuentes, estados y usuario asignado.
     * Soporta filtros y paginación.
     *
     * @param array $filters  ['fuente_id'=>?, 'estado_id'=>?, 'search'=>?]
     * @param int   $limit
     * @param int   $offset
     */
    public function getAllWithRelations(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['fuente_id'])) {
            $where[]  = 'l.fuente_id = ?';
            $params[] = (int) $filters['fuente_id'];
        }
        if (!empty($filters['estado_id'])) {
            $where[]  = 'l.estado_id = ?';
            $params[] = (int) $filters['estado_id'];
        }
        if (!empty($filters['search'])) {
            $where[]  = '(l.nombre LIKE ? OR l.email LIKE ? OR l.telefono LIKE ?)';
            $term     = '%' . $filters['search'] . '%';
            array_push($params, $term, $term, $term);
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT l.*,
                       f.nombre   AS fuente_nombre,
                       f.icono    AS fuente_icono,
                       f.color    AS fuente_color,
                       e.nombre   AS estado_nombre,
                       e.color    AS estado_color,
                       u.nombre   AS usuario_nombre
                FROM leads l
                JOIN fuentes      f ON l.fuente_id = f.id
                JOIN estados_lead e ON l.estado_id = e.id
                LEFT JOIN usuarios u ON l.usuario_id = u.id
                {$whereSQL}
                ORDER BY l.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Cuenta total de leads con los mismos filtros (para paginación).
     */
    public function countFiltered(array $filters = []): int
    {
        $where  = [];
        $params = [];

        if (!empty($filters['fuente_id'])) {
            $where[]  = 'fuente_id = ?';
            $params[] = (int) $filters['fuente_id'];
        }
        if (!empty($filters['estado_id'])) {
            $where[]  = 'estado_id = ?';
            $params[] = (int) $filters['estado_id'];
        }
        if (!empty($filters['search'])) {
            $where[]  = '(nombre LIKE ? OR email LIKE ? OR telefono LIKE ?)';
            $term     = '%' . $filters['search'] . '%';
            array_push($params, $term, $term, $term);
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM leads {$whereSQL}");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Detalle de un lead con todas sus relaciones.
     */
    public function findWithRelations(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT l.*,
                    f.nombre AS fuente_nombre, f.icono AS fuente_icono, f.color AS fuente_color,
                    e.nombre AS estado_nombre, e.color AS estado_color,
                    u.nombre AS usuario_nombre
             FROM leads l
             JOIN fuentes      f ON l.fuente_id = f.id
             JOIN estados_lead e ON l.estado_id = e.id
             LEFT JOIN usuarios u ON l.usuario_id = u.id
             WHERE l.id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Métricas para el dashboard: total por estado.
     */
    public function countByEstado(): array
    {
        $stmt = $this->db->query(
            "SELECT e.nombre, e.color, COUNT(l.id) AS total
             FROM estados_lead e
             LEFT JOIN leads l ON l.estado_id = e.id
             GROUP BY e.id
             ORDER BY e.orden"
        );
        return $stmt->fetchAll();
    }

    /**
     * Leads nuevos en los últimos N días.
     */
    public function countRecent(int $days = 30): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)"
        );
        $stmt->execute([$days]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Distribución por fuente para el dashboard.
     */
    public function countByFuente(): array
    {
        $stmt = $this->db->query(
            "SELECT f.nombre, f.icono, f.color, COUNT(l.id) AS total
             FROM fuentes f
             LEFT JOIN leads l ON l.fuente_id = f.id
             GROUP BY f.id
             ORDER BY total DESC"
        );
        return $stmt->fetchAll();
    }
}
