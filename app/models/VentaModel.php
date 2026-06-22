<?php
/**
 * VentaModel - Operaciones sobre la tabla ventas
 */
class VentaModel extends Model
{
    protected string $table = 'ventas';

    /** Ventas con datos del lead y usuario */
    public function getAllWithRelations(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT v.*, l.nombre AS lead_nombre, l.empresa AS lead_empresa,
                    f.nombre AS fuente_nombre, f.color AS fuente_color,
                    u.nombre AS usuario_nombre
             FROM ventas v
             JOIN leads    l ON v.lead_id   = l.id
             JOIN fuentes  f ON l.fuente_id = f.id
             LEFT JOIN usuarios u ON v.usuario_id = u.id
             ORDER BY v.fecha_venta DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /** Ingresos totales */
    public function totalIngresos(): float
    {
        return (float) $this->db->query("SELECT COALESCE(SUM(monto), 0) FROM ventas")->fetchColumn();
    }

    /** Ingresos del mes actual */
    public function ingresosMesActual(): float
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(monto), 0) FROM ventas
             WHERE MONTH(fecha_venta) = MONTH(NOW()) AND YEAR(fecha_venta) = YEAR(NOW())"
        );
        $stmt->execute();
        return (float) $stmt->fetchColumn();
    }

    /** Ventas por fuente para reportes */
    public function porFuente(string $inicio = '', string $fin = ''): array
    {
        $where  = '';
        $params = [];
        if ($inicio && $fin) {
            $where    = 'WHERE v.fecha_venta BETWEEN ? AND ?';
            $params[] = $inicio;
            $params[] = $fin;
        }
        $stmt = $this->db->prepare(
            "SELECT f.nombre, f.color, COUNT(v.id) AS cantidad, SUM(v.monto) AS total
             FROM ventas v
             JOIN leads   l ON v.lead_id   = l.id
             JOIN fuentes f ON l.fuente_id = f.id
             {$where}
             GROUP BY f.id ORDER BY total DESC"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Conversión: leads -> ventas por fuente */
    public function tasaConversion(): array
    {
        $stmt = $this->db->query(
            "SELECT f.nombre, f.color,
                    COUNT(DISTINCT l.id)  AS total_leads,
                    COUNT(DISTINCT v.id)  AS total_ventas,
                    ROUND(
                        IFNULL(COUNT(DISTINCT v.id) / NULLIF(COUNT(DISTINCT l.id),0) * 100, 0)
                    , 2) AS tasa
             FROM fuentes f
             LEFT JOIN leads  l ON l.fuente_id = f.id
             LEFT JOIN ventas v ON v.lead_id   = l.id
             GROUP BY f.id
             ORDER BY tasa DESC"
        );
        return $stmt->fetchAll();
    }

    /** Ventas agrupadas por mes (últimos 12 meses) */
    public function porMes(): array
    {
        $stmt = $this->db->query(
            "SELECT DATE_FORMAT(fecha_venta, '%Y-%m') AS mes,
                    COUNT(*)        AS cantidad,
                    SUM(monto)      AS total
             FROM ventas
             WHERE fecha_venta >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY mes ORDER BY mes"
        );
        return $stmt->fetchAll();
    }
}
