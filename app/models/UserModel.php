<?php
/**
 * UserModel - Operaciones sobre la tabla usuarios
 */
class UserModel extends Model
{
    protected string $table = 'usuarios';

    /** Busca usuario activo por email */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE email = ? AND activo = 1 LIMIT 1"
        );
        $stmt->execute([strtolower(trim($email))]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Lista usuarios activos (sin password) */
    public function listActivos(): array
    {
        $stmt = $this->db->query(
            "SELECT id, nombre, email, rol, created_at FROM usuarios WHERE activo = 1 ORDER BY nombre"
        );
        return $stmt->fetchAll();
    }
}
