<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseModel.php';

final class AppointmentModel extends BaseModel
{
    private function baseSelect(): string
    {
        return "SELECT a.*, p.name patient_name, p.email patient_email, du.name doctor_name, s.name specialization
                FROM appointments a
                JOIN users p ON p.id=a.patient_id
                JOIN doctors d ON d.id=a.doctor_id
                JOIN users du ON du.id=d.user_id
                JOIN specializations s ON s.id=d.specialization_id";
    }

    public function find(int $id): ?array
    {
        return $this->row($this->execute($this->baseSelect() . ' WHERE a.id=? LIMIT 1', 'i', [$id]));
    }

    public function create(array $data): void
    {
        $this->execute(
            'INSERT INTO appointments (patient_id,doctor_id,appt_date,appt_time,reason) VALUES (?,?,?,?,?)',
            'iisss',
            [(int) $data['patient_id'], (int) $data['doctor_id'], $data['appt_date'], $data['appt_time'], $data['reason'] ?: null]
        );
    }

    public function hasConflict(int $doctorId, string $date, string $time, ?int $ignoreId = null): bool
    {
        if ($ignoreId) {
            $row = $this->row($this->execute('SELECT id FROM appointments WHERE doctor_id=? AND appt_date=? AND appt_time=? AND id<>? LIMIT 1', 'issi', [$doctorId, $date, $time, $ignoreId]));
        } else {
            $row = $this->row($this->execute('SELECT id FROM appointments WHERE doctor_id=? AND appt_date=? AND appt_time=? LIMIT 1', 'iss', [$doctorId, $date, $time]));
        }
        return $row !== null;
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): void
    {
        $this->execute('UPDATE appointments SET status=?, doctor_notes=COALESCE(?, doctor_notes) WHERE id=?', 'ssi', [$status, $notes, $id]);
    }

    public function list(array $filters, int $limit, int $offset): array
    {
        [$where, $types, $params] = $this->filters($filters);
        $sql = $this->baseSelect() . $where . ' ORDER BY a.appt_date DESC, a.appt_time DESC LIMIT ? OFFSET ?';
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;
        return $this->rows($this->execute($sql, $types, $params));
    }

    public function count(array $filters): int
    {
        [$where, $types, $params] = $this->filters($filters);
        $sql = 'SELECT COUNT(*) total FROM appointments a JOIN users p ON p.id=a.patient_id JOIN doctors d ON d.id=a.doctor_id ' . $where;
        $row = $this->row($this->execute($sql, $types, $params));
        return (int) ($row['total'] ?? 0);
    }

    public function report(array $filters): array
    {
        [$where, $types, $params] = $this->filters($filters);
        return $this->rows($this->execute($this->baseSelect() . $where . ' ORDER BY a.appt_date, a.appt_time', $types, $params));
    }

    public function dashboardAdmin(): array
    {
        return [
            'roles' => $this->rows($this->execute('SELECT role, COUNT(*) total FROM users GROUP BY role')),
            'today' => $this->row($this->execute('SELECT COUNT(*) total FROM appointments WHERE appt_date=CURDATE()'))['total'] ?? 0,
            'week' => $this->rows($this->execute('SELECT status, COUNT(*) total FROM appointments WHERE WEEK(appt_date)=WEEK(NOW()) GROUP BY status')),
            'recent' => $this->rows($this->execute($this->baseSelect() . ' ORDER BY a.created_at DESC LIMIT 5')),
        ];
    }

    public function dashboardDoctor(int $doctorId): array
    {
        return [
            'today' => $this->rows($this->execute($this->baseSelect() . ' WHERE a.doctor_id=? AND a.appt_date=CURDATE() ORDER BY a.appt_time', 'i', [$doctorId])),
            'month' => $this->row($this->execute('SELECT COUNT(*) total FROM appointments WHERE doctor_id=? AND MONTH(appt_date)=MONTH(CURDATE())', 'i', [$doctorId]))['total'] ?? 0,
            'pending' => $this->row($this->execute("SELECT COUNT(*) total FROM appointments WHERE doctor_id=? AND status='pending'", 'i', [$doctorId]))['total'] ?? 0,
            'completed' => $this->row($this->execute("SELECT COUNT(*) total FROM appointments WHERE doctor_id=? AND status='completed'", 'i', [$doctorId]))['total'] ?? 0,
            'upcoming' => $this->rows($this->execute($this->baseSelect() . ' WHERE a.doctor_id=? AND a.appt_date>=CURDATE() ORDER BY a.appt_date,a.appt_time LIMIT 5', 'i', [$doctorId])),
        ];
    }

    public function dashboardPatient(int $patientId): array
    {
        return [
            'active' => $this->rows($this->execute($this->baseSelect() . " WHERE a.patient_id=? AND a.status IN ('pending','confirmed') ORDER BY a.appt_date,a.appt_time", 'i', [$patientId])),
            'completed' => $this->row($this->execute("SELECT COUNT(*) total FROM appointments WHERE patient_id=? AND status='completed'", 'i', [$patientId]))['total'] ?? 0,
            'prescriptions' => $this->row($this->execute('SELECT COUNT(*) total FROM prescriptions pr JOIN appointments a ON a.id=pr.appointment_id WHERE a.patient_id=?', 'i', [$patientId]))['total'] ?? 0,
        ];
    }

    private function filters(array $filters): array
    {
        $conditions = [];
        $types = '';
        $params = [];
        if (!empty($filters['doctor_id'])) {
            $conditions[] = 'a.doctor_id=?';
            $types .= 'i';
            $params[] = (int) $filters['doctor_id'];
        }
        if (!empty($filters['patient_id'])) {
            $conditions[] = 'a.patient_id=?';
            $types .= 'i';
            $params[] = (int) $filters['patient_id'];
        }
        if (!empty($filters['patient_name'])) {
            $conditions[] = 'p.name LIKE ?';
            $types .= 's';
            $params[] = '%' . $filters['patient_name'] . '%';
        }
        if (!empty($filters['start_date'])) {
            $conditions[] = 'a.appt_date>=?';
            $types .= 's';
            $params[] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $conditions[] = 'a.appt_date<=?';
            $types .= 's';
            $params[] = $filters['end_date'];
        }
        if (!empty($filters['status'])) {
            $conditions[] = 'a.status=?';
            $types .= 's';
            $params[] = $filters['status'];
        }
        return [$conditions ? ' WHERE ' . implode(' AND ', $conditions) : '', $types, $params];
    }
}

