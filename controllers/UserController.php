<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/SpecializationModel.php';

final class UserController extends BaseController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function index(): void
    {
        Auth::requireRole('admin');
        $search = trim($_GET['search'] ?? '');
        $role = in_array($_GET['role'] ?? '', ['admin', 'doctor', 'patient'], true) ? $_GET['role'] : '';
        $page = max(1, (int) ($_GET['p'] ?? 1));
        $paginator = new Paginator($this->users->count($search, $role), ITEMS_PER_PAGE, $page);
        $this->view('users/index', ['pageTitle' => 'Users', 'users' => $this->users->paginated(ITEMS_PER_PAGE, $paginator->offset(), $search, $role), 'paginator' => $paginator, 'search' => $search, 'role' => $role]);
    }

    public function create(): void
    {
        Auth::requireRole('admin');
        $this->view('users/form', ['pageTitle' => 'Create User', 'user' => null, 'specializations' => (new SpecializationModel())->all()]);
    }

    public function store(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $avatar = upload_image('avatar', 'avatars', 'avatar');
        $userId = $this->users->create($_POST + ['avatar' => $avatar]);
        if (($_POST['role'] ?? '') === 'doctor') {
            $photo = upload_image('photo', 'doctor_photos', 'doctor');
            (new DoctorModel())->create($_POST + [
                'user_id' => $userId,
                'photo' => $photo,
                'available_days' => $this->availableDaysFromPost(),
            ]);
        }
        flash('success', 'User created.');
        redirect(url('users'));
    }

    public function edit(): void
    {
        Auth::requireRole('admin');
        $this->view('users/form', ['pageTitle' => 'Edit User', 'user' => $this->users->find((int) $_GET['id'])]);
    }

    public function update(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $avatar = upload_image('avatar', 'avatars', 'avatar');
        $this->users->update((int) $_POST['id'], $_POST + ['avatar' => $avatar]);
        flash('success', 'User updated.');
        redirect(url('users'));
    }

    public function delete(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $this->users->delete((int) $_POST['id']);
        flash('success', 'User deleted.');
        redirect(url('users'));
    }

    public function toggleActive(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $id = (int) $_POST['id'];
        if ($id === Auth::id()) {
            flash('danger', 'You cannot deactivate your own account.');
            redirect(url('users'));
        }
        $this->users->toggleActive($id);
        flash('success', 'User status updated.');
        redirect(url('users'));
    }

    private function availableDaysFromPost(): string
    {
        $allowed = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $selected = array_values(array_intersect($allowed, $_POST['available_days'] ?? []));
        return implode(',', $selected ?: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu']);
    }
}
