<?php
/**
 * FuenteModel - Fuentes de adquisición
 */
class FuenteModel extends Model
{
    protected string $table = 'fuentes';

    public function allActivas(): array
    {
        $stmt = $this->db->query("SELECT * FROM fuentes WHERE activo = 1 ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
