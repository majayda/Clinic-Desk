<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

final class SpecializationModel extends BaseModel
{
    public function all(): array
    {
        return $this->rows($this->execute('SELECT * FROM specializations ORDER BY name'));
    }

    public function find(int $id): ?array
    {
        return $this->row($this->execute('SELECT * FROM specializations WHERE id=? LIMIT 1', 'i', [$id]));
    }

    public function create(string $name): void
    {
        $this->execute('INSERT INTO specializations (name) VALUES (?)', 's', [$name]);
    }

    public function update(int $id, string $name): void
    {
        $this->execute('UPDATE specializations SET name=? WHERE id=?', 'si', [$name, $id]);
    }

    public function delete(int $id): void
    {
        $this->execute('DELETE FROM specializations WHERE id=?', 'i', [$id]);
    }
}
