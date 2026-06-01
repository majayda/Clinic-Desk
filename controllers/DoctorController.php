<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

final class DoctorController extends BaseController
{
    private DoctorModel $doctors;

    public function __construct()
    {
        $this->doctors = new DoctorModel();
    }

    public function index(): void
    {
        Auth::requireRole('admin', 'patient');
        $this->view('doctors/index', ['pageTitle' => 'Doctors', 'doctors' => $this->doctors->all()]);
    }

    public function create(): void
    {
        Auth::requireRole('admin');
        $this->view('doctors/form', ['pageTitle' => 'Create Doctor', 'doctor' => null, 'doctorUsers' => (new UserModel())->byRole('doctor'), 'specializations' => (new SpecializationModel())->all()]);
    }

    public function store(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $photo = upload_image('photo', 'doctor_photos', 'doctor');
        $this->doctors->create($_POST + ['photo' => $photo]);
        flash('success', 'Doctor profile created.');
        redirect(url('doctors'));
    }

    public function edit(): void
    {
        Auth::requireRole('admin', 'doctor');
        $doctor = Auth::role() === 'doctor' ? $this->doctors->findByUser(Auth::id()) : $this->doctors->find((int) $_GET['id']);
        $this->view('doctors/form', ['pageTitle' => 'Edit Doctor', 'doctor' => $doctor, 'doctorUsers' => (new UserModel())->byRole('doctor'), 'specializations' => (new SpecializationModel())->all()]);
    }

    public function update(): void
    {
        Auth::requireRole('admin', 'doctor');
        require_post_csrf();
        $doctorId = (int) $_POST['id'];
        if (Auth::role() === 'doctor') {
            $mine = $this->doctors->findByUser(Auth::id());
            if (!$mine || (int) $mine['id'] !== $doctorId) {
                redirect(url('errors', '403'));
            }
        }
        $photo = upload_image('photo', 'doctor_photos', 'doctor');
        $this->doctors->update($doctorId, $_POST + ['photo' => $photo]);
        flash('success', 'Doctor profile updated.');
        redirect(url('doctors', Auth::role() === 'doctor' ? 'edit' : 'index', Auth::role() === 'doctor' ? [] : []));
    }

    public function delete(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $this->doctors->delete((int) $_POST['id']);
        flash('success', 'Doctor deleted.');
        redirect(url('doctors'));
    }
}
