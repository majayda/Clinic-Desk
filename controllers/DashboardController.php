<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/UserModel.php';

final class DashboardController extends BaseController
{
    public function index(): void
    {
        Auth::requireRole('admin', 'doctor', 'patient');
        $appointments = new AppointmentModel();
        if (Auth::role() === 'admin') {
            $this->view('dashboard/admin', ['pageTitle' => 'Admin Dashboard', 'stats' => $appointments->dashboardAdmin()]);
            return;
        }
        if (Auth::role() === 'doctor') {
            $doctor = (new DoctorModel())->findByUser(Auth::id());
            $this->view('dashboard/doctor', ['pageTitle' => 'Doctor Dashboard', 'doctor' => $doctor, 'stats' => $doctor ? $appointments->dashboardDoctor((int) $doctor['id']) : []]);
            return;
        }
        $this->view('dashboard/patient', ['pageTitle' => 'Patient Dashboard', 'patient' => (new UserModel())->find(Auth::id()), 'stats' => $appointments->dashboardPatient(Auth::id())]);
    }
}
