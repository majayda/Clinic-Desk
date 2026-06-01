<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';

final class PrescriptionController extends BaseController
{
    private PrescriptionModel $prescriptions;

    public function __construct()
    {
        $this->prescriptions = new PrescriptionModel();
    }

    public function index(): void
    {
        Auth::requireRole('patient');
        $this->view('prescriptions/index', ['pageTitle' => 'My Prescriptions', 'prescriptions' => $this->prescriptions->forPatient(Auth::id())]);
    }

    public function add(): void
    {
        Auth::requireRole('doctor');
        $appointment = (new AppointmentModel())->find((int) $_GET['appointment_id']);
        $this->authorizeAdd($appointment);
        $this->view('prescriptions/add', ['pageTitle' => 'Add Prescription', 'appointment' => $appointment]);
    }

    public function store(): void
    {
        Auth::requireRole('doctor');
        require_post_csrf();
        $appointment = (new AppointmentModel())->find((int) $_POST['appointment_id']);
        $this->authorizeAdd($appointment);
        $filePath = $this->uploadPdf((int) $_POST['appointment_id']);
        $this->prescriptions->create($_POST + ['file_path' => $filePath]);
        flash('success', 'Prescription saved.');
        redirect(url('appointments', 'show', ['id' => (int) $_POST['appointment_id']]));
    }

    public function download(): void
    {
        Auth::requireRole('admin', 'doctor', 'patient');
        $prescription = $this->prescriptions->withAppointment((int) $_GET['id']);
        if (!$prescription || !$prescription['file_path']) {
            flash('danger', 'Prescription file is missing.');
            redirect(url());
        }
        if (Auth::role() === 'patient' && (int) $prescription['patient_id'] !== Auth::id()) {
            redirect(url('errors', '403'));
        }
        if (Auth::role() === 'doctor') {
            $doctor = (new DoctorModel())->findByUser(Auth::id());
            if (!$doctor || (int) $doctor['id'] !== (int) $prescription['doctor_id']) {
                redirect(url('errors', '403'));
            }
        }
        $path = __DIR__ . '/../public/uploads/prescriptions/' . basename($prescription['file_path']);
        if (!is_file($path)) {
            flash('danger', 'File not found.');
            redirect(url());
        }
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="prescription.pdf"');
        readfile($path);
        exit;
    }

    private function authorizeAdd(?array $appointment): void
    {
        if (!$appointment || $appointment['status'] !== 'completed' || $this->prescriptions->findByAppointment((int) $appointment['id'])) {
            redirect(url('errors', '403'));
        }
        $doctor = (new DoctorModel())->findByUser(Auth::id());
        if (!$doctor || (int) $doctor['id'] !== (int) $appointment['doctor_id']) {
            redirect(url('errors', '403'));
        }
    }

    private function uploadPdf(int $appointmentId): ?string
    {
        if (empty($_FILES['prescription_file']['name'])) {
            return null;
        }
        if ($_FILES['prescription_file']['size'] > MAX_PDF_SIZE) {
            flash('danger', 'PDF file must be 3MB or less.');
            redirect(url('prescriptions', 'add', ['appointment_id' => $appointmentId]));
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if ($finfo->file($_FILES['prescription_file']['tmp_name']) !== 'application/pdf') {
            flash('danger', 'Only PDF prescriptions are allowed.');
            redirect(url('prescriptions', 'add', ['appointment_id' => $appointmentId]));
        }
        $name = 'prescription_' . $appointmentId . '_' . time() . '.pdf';
        move_uploaded_file($_FILES['prescription_file']['tmp_name'], __DIR__ . '/../public/uploads/prescriptions/' . $name);
        return $name;
    }
}

