<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

final class PrescriptionModel extends BaseModel
{
    public function findByAppointment(int $appointmentId): ?array
    {
        return $this->row($this->execute('SELECT * FROM prescriptions WHERE appointment_id=? LIMIT 1', 'i', [$appointmentId]));
    }

    public function create(array $data): void
    {
        $this->execute(
            'INSERT INTO prescriptions (appointment_id,diagnosis,medications,notes,file_path) VALUES (?,?,?,?,?)',
            'issss',
            [(int) $data['appointment_id'], $data['diagnosis'], $data['medications'], $data['notes'] ?: null, $data['file_path'] ?: null]
        );
    }

    public function update(int $id, array $data): void
    {
        $this->execute(
            'UPDATE prescriptions SET diagnosis=?, medications=?, notes=?, file_path=COALESCE(?, file_path) WHERE id=?',
            'ssssi',
            [$data['diagnosis'], $data['medications'], $data['notes'] ?: null, $data['file_path'] ?: null, $id]
        );
    }

    public function forPatient(int $patientId): array
    {
        return $this->rows($this->execute(
            'SELECT pr.*, a.appt_date, du.name doctor_name
             FROM prescriptions pr
             JOIN appointments a ON a.id=pr.appointment_id
             JOIN doctors d ON d.id=a.doctor_id
             JOIN users du ON du.id=d.user_id
             WHERE a.patient_id=?
             ORDER BY pr.created_at DESC',
            'i',
            [$patientId]
        ));
    }

    public function withAppointment(int $appointmentId): ?array
    {
        return $this->row($this->execute(
            'SELECT pr.*, a.patient_id, a.doctor_id
             FROM prescriptions pr JOIN appointments a ON a.id=pr.appointment_id
             WHERE pr.appointment_id=? LIMIT 1',
            'i',
            [$appointmentId]
        ));
    }
}
