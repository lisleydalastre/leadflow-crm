<?php
/**
 * Model - Clase base para todos los modelos
 * 
 * Provee acceso a PDO y métodos CRUD genéricos
 * que los modelos concretos pueden sobrescribir o extender.
 */
abstract class Model
{
    protected PDO    $db;
    protected string $table  = '';
    protected string $pk     = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Retorna todos los registros de la tabla. */
    public function all(string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $dir  = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$dir}");
        return $stmt->fetchAll();
    }

    /** Busca un registro por su PK. */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->pk} = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Inserta un registro y retorna el ID generado. */
    public function create(array $data): int
    {
        $cols   = implode(', ', array_keys($data));
        $params = implode(', ', array_fill(0, count($data), '?'));
        $stmt   = $this->db->prepare("INSERT INTO {$this->table} ({$cols}) VALUES ({$params})");
        $stmt->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    /** Actualiza un registro por su PK. */
    public function update(int $id, array $data): bool
    {
        $set  = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE {$this->pk} = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    /** Elimina un registro por su PK. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->pk} = ?");
        return $stmt->execute([$id]);
    }

    /** Cuenta registros con condición opcional. */
    public function count(string $where = '', array $params = []): int
    {
        $sql  = "SELECT COUNT(*) FROM {$this->table}" . ($where ? " WHERE {$where}" : '');
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}
