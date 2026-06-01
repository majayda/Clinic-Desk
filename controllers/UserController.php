<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UserModel.php';

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
        $page = max(1, (int) ($_GET['p'] ?? 1));
        $paginator = new Paginator($this->users->count($search), ITEMS_PER_PAGE, $page);
        $this->view('users/index', ['pageTitle' => 'Users', 'users' => $this->users->paginated(ITEMS_PER_PAGE, $paginator->offset(), $search), 'paginator' => $paginator, 'search' => $search]);
    }

    public function create(): void
    {
        Auth::requireRole('admin');
        $this->view('users/form', ['pageTitle' => 'Create User', 'user' => null]);
    }

    public function store(): void
    {
        Auth::requireRole('admin');
        require_post_csrf();
        $avatar = upload_image('avatar', 'avatars', 'avatar');
        $this->users->create($_POST + ['avatar' => $avatar]);
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
}
