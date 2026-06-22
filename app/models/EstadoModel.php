<?php
/**
 * EstadoModel - Pipeline comercial
 */
class EstadoModel extends Model
{
    protected string $table = 'estados_lead';

    public function allOrdenados(): array
    {
        $stmt = $this->db->query("SELECT * FROM estados_lead ORDER BY orden");
        return $stmt->fetchAll();
    }
}
