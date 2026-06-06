<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

final class UserModel extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        return $this->row($this->execute('SELECT * FROM users WHERE email = ? LIMIT 1', 's', [$email]));
    }

    public function find(int $id): ?array
    {
        return $this->row($this->execute('SELECT * FROM users WHERE id = ? LIMIT 1', 'i', [$id]));
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    public function paginated(int $limit, int $offset, string $search = '', string $role = ''): array
    {
        $sql = 'SELECT * FROM users';
        $conditions = [];
        $types = '';
        $params = [];
        if ($search !== '') {
            $like = '%' . $search . '%';
            $conditions[] = '(name LIKE ? OR email LIKE ?)';
            $types .= 'ss';
            $params[] = $like;
            $params[] = $like;
        }
        if ($role !== '') {
            $conditions[] = 'role = ?';
            $types .= 's';
            $params[] = $role;
        }
        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;
        return $this->rows($this->execute($sql, $types, $params));
    }

    public function count(string $search = '', string $role = ''): int
    {
        $sql = 'SELECT COUNT(*) total FROM users';
        $conditions = [];
        $types = '';
        $params = [];
        if ($search !== '') {
            $like = '%' . $search . '%';
            $conditions[] = '(name LIKE ? OR email LIKE ?)';
            $types .= 'ss';
            $params[] = $like;
            $params[] = $like;
        }
        if ($role !== '') {
            $conditions[] = 'role = ?';
            $types .= 's';
            $params[] = $role;
        }
        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $row = $this->row($this->execute($sql, $types, $params));
        return (int) ($row['total'] ?? 0);
    }

    public function create(array $data): int
    {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->execute(
            'INSERT INTO users (name,email,password,role,phone,avatar,is_active) VALUES (?,?,?,?,?,?,?)',
            'ssssssi',
            [$data['name'], $data['email'], $hash, $data['role'], $data['phone'] ?: null, $data['avatar'] ?: null, (int) ($data['is_active'] ?? 1)]
        );
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        if (!empty($data['avatar'])) {
            $this->execute(
                'UPDATE users SET name=?, email=?, role=?, phone=?, avatar=?, is_active=? WHERE id=?',
                'sssssii',
                [$data['name'], $data['email'], $data['role'], $data['phone'] ?: null, $data['avatar'], (int) ($data['is_active'] ?? 1), $id]
            );
        } else {
            $this->execute(
                'UPDATE users SET name=?, email=?, role=?, phone=?, is_active=? WHERE id=?',
                'ssssii',
                [$data['name'], $data['email'], $data['role'], $data['phone'] ?: null, (int) ($data['is_active'] ?? 1), $id]
            );
        }
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $this->execute('UPDATE users SET password=? WHERE id=?', 'si', [$hash, $id]);
        }
    }

    public function updateProfile(int $id, string $name, ?string $phone, ?string $avatar): void
    {
        if ($avatar) {
            $this->execute('UPDATE users SET name=?, phone=?, avatar=? WHERE id=?', 'sssi', [$name, $phone, $avatar, $id]);
            return;
        }
        $this->execute('UPDATE users SET name=?, phone=? WHERE id=?', 'ssi', [$name, $phone, $id]);
    }

    public function updatePassword(int $id, string $newHash): void
    {
        $this->execute('UPDATE users SET password=? WHERE id=?', 'si', [$newHash, $id]);
    }

    public function delete(int $id): void
    {
        $this->execute('DELETE FROM users WHERE id=?', 'i', [$id]);
    }

    public function toggleActive(int $id): void
    {
        $this->execute('UPDATE users SET is_active = IF(is_active=1, 0, 1) WHERE id=?', 'i', [$id]);
    }

    public function byRole(string $role): array
    {
        return $this->rows($this->execute('SELECT id, name, email FROM users WHERE role=? AND is_active=1 ORDER BY name', 's', [$role]));
    }
}
