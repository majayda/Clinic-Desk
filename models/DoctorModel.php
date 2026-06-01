<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

final class DoctorModel extends BaseModel
{
    public function all(): array
    {
        return $this->rows($this->execute(
            'SELECT d.*, u.name, u.email, u.phone, s.name specialization
             FROM doctors d JOIN users u ON u.id=d.user_id
             JOIN specializations s ON s.id=d.specialization_id
             ORDER BY u.name'
        ));
    }

    public function find(int $id): ?array
    {
        return $this->row($this->execute(
            'SELECT d.*, u.name, u.email, u.phone, u.avatar, s.name specialization
             FROM doctors d JOIN users u ON u.id=d.user_id
             JOIN specializations s ON s.id=d.specialization_id WHERE d.id=? LIMIT 1',
            'i',
            [$id]
        ));
    }

    public function findByUser(int $userId): ?array
    {
        return $this->row($this->execute('SELECT * FROM doctors WHERE user_id=? LIMIT 1', 'i', [$userId]));
    }

    public function create(array $data): void
    {
        $this->execute(
            'INSERT INTO doctors (user_id,specialization_id,bio,photo,consultation_fee,available_days) VALUES (?,?,?,?,?,?)',
            'iissds',
            [(int) $data['user_id'], (int) $data['specialization_id'], $data['bio'] ?: null, $data['photo'] ?: null, (float) $data['consultation_fee'], $data['available_days']]
        );
    }

    public function update(int $id, array $data): void
    {
        if (!empty($data['photo'])) {
            $this->execute(
                'UPDATE doctors SET specialization_id=?, bio=?, photo=?, consultation_fee=?, available_days=? WHERE id=?',
                'issdsi',
                [(int) $data['specialization_id'], $data['bio'] ?: null, $data['photo'], (float) $data['consultation_fee'], $data['available_days'], $id]
            );
            return;
        }

        $this->execute(
            'UPDATE doctors SET specialization_id=?, bio=?, consultation_fee=?, available_days=? WHERE id=?',
            'isdsi',
            [(int) $data['specialization_id'], $data['bio'] ?: null, (float) $data['consultation_fee'], $data['available_days'], $id]
        );
    }

    public function delete(int $id): void
    {
        $this->execute('DELETE FROM doctors WHERE id=?', 'i', [$id]);
    }
}
