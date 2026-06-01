<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/PrescriptionModel.php';

final class AppointmentController extends BaseController
{
    private AppointmentModel $appointments;

    public function __construct()
    {
        $this->appointments = new AppointmentModel();
    }

    public function index(): void
    {
        Auth::requireRole('admin', 'doctor', 'patient');
        $filters = [
            'doctor_id' => $_GET['doctor_id'] ?? '',
            'patient_id' => Auth::role() === 'patient' ? Auth::id() : '',
            'patient_name' => $_GET['patient_name'] ?? '',
            'start_date' => $_GET['start_date'] ?? '',
            'end_date' => $_GET['end_date'] ?? '',
            'status' => $_GET['status'] ?? '',
        ];
        if (Auth::role() === 'doctor') {
            $doctor = (new DoctorModel())->findByUser(Auth::id());
            $filters['doctor_id'] = $doctor['id'] ?? 0;
        }
        $page = max(1, (int) ($_GET['p'] ?? 1));
        $paginator = new Paginator($this->appointments->count($filters), ITEMS_PER_PAGE, $page);
        $this->view('appointments/index', [
            'pageTitle' => 'Appointments',
            'appointments' => $this->appointments->list($filters, ITEMS_PER_PAGE, $paginator->offset()),
            'paginator' => $paginator,
            'doctors' => (new DoctorModel())->all(),
            'filters' => $filters,
        ]);
    }

    public function book(): void
    {
        Auth::requireRole('patient', 'admin');
        $this->view('appointments/book', ['pageTitle' => 'Book Appointment', 'doctors' => (new DoctorModel())->all()]);
    }

    public function store(): void
    {
        Auth::requireRole('patient', 'admin');
        require_post_csrf();
        $patientId = Auth::role() === 'patient' ? Auth::id() : (int) ($_POST['patient_id'] ?? Auth::id());
        $doctor = (new DoctorModel())->find((int) $_POST['doctor_id']);
        if (!$doctor) {
            flash('danger', 'Selected doctor was not found.');
            redirect(url('appointments', 'book'));
        }
        $day = date('D', strtotime($_POST['appt_date']));
        $availableDays = array_map('trim', explode(',', $doctor['available_days']));
        if (!in_array($day, $availableDays, true)) {
            flash('danger', 'This doctor is not available on the selected day.');
            redirect(url('appointments', 'book'));
        }
        if ($this->appointments->hasConflict((int) $_POST['doctor_id'], $_POST['appt_date'], $_POST['appt_time'])) {
            flash('danger', 'This doctor already has an appointment at that time.');
            redirect(url('appointments', 'book'));
        }
        $this->appointments->create($_POST + ['patient_id' => $patientId]);
        flash('success', 'Appointment booked.');
        redirect(url('appointments'));
    }

    public function show(): void
    {
        Auth::requireRole('admin', 'doctor', 'patient');
        $appointment = $this->appointments->find((int) $_GET['id']);
        $this->authorizeAppointment($appointment);
        $prescription = (new PrescriptionModel())->findByAppointment((int) $appointment['id']);
        $this->view('appointments/show', ['pageTitle' => 'Appointment Details', 'appointment' => $appointment, 'prescription' => $prescription]);
    }

    public function updateStatus(): void
    {
        Auth::requireRole('admin', 'doctor');
        require_post_csrf();
        $appointment = $this->appointments->find((int) $_POST['id']);
        $this->authorizeAppointment($appointment);
        $this->appointments->updateStatus((int) $_POST['id'], $_POST['status'], $_POST['doctor_notes'] ?? null);
        flash('success', 'Appointment status updated.');
        redirect(url('appointments', 'show', ['id' => (int) $_POST['id']]));
    }

    private function authorizeAppointment(?array $appointment): void
    {
        if (!$appointment) {
            redirect(url('errors', '404'));
        }
        if (Auth::role() === 'patient' && (int) $appointment['patient_id'] !== Auth::id()) {
            redirect(url('errors', '403'));
        }
        if (Auth::role() === 'doctor') {
            $doctor = (new DoctorModel())->findByUser(Auth::id());
            if (!$doctor || (int) $appointment['doctor_id'] !== (int) $doctor['id']) {
                redirect(url('errors', '403'));
            }
        }
    }
}
