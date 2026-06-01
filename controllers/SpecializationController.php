<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

final class SpecializationController extends BaseController
{
    private SpecializationModel $specializations;

    public function __construct()
    {
        $this->specializations = new SpecializationModel();
    }

    public function index(): void
    {
        Auth::requireRole('admin');
        $this->view('specializations/index', ['pageTitle' => 'Specializations', 'specializations' => $this->specializations->all()]);
    }

    public function create(): void
    {
        Auth::requireRole('admin');
        $this->view('specializations/form', ['pageTitle' => 'Create Specialization', 'specialization' => null]);
    }

    public function store(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $this->specializations->create(trim($_POST['name'] ?? ''));
        flash('success', 'Specialization created.');
        redirect(url('specializations'));
    }

    public function edit(): void
    {
        Auth::requireRole('admin');
        $this->view('specializations/form', ['pageTitle' => 'Edit Specialization', 'specialization' => $this->specializations->find((int) $_GET['id'])]);
    }

    public function update(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $this->specializations->update((int) $_POST['id'], trim($_POST['name'] ?? ''));
        flash('success', 'Specialization updated.');
        redirect(url('specializations'));
    }

    public function delete(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        try {
            $this->specializations->delete((int) $_POST['id']);
            flash('success', 'Specialization deleted.');
        } catch (RuntimeException) {
            flash('danger', 'This specialization is linked to doctors and cannot be deleted.');
        }
        redirect(url('specializations'));
    }
}

