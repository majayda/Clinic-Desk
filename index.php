<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/helpers.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/CSRF.php';
require_once __DIR__ . '/core/Paginator.php';

$page = $_GET['page'] ?? (Auth::check() ? 'dashboard' : 'auth');
$action = $_GET['action'] ?? ($page === 'auth' ? 'login' : 'index');

$routes = [
    'auth' => AuthController::class,
    'dashboard' => DashboardController::class,
    'users' => UserController::class,
    'doctors' => DoctorController::class,
    'specializations' => SpecializationController::class,
    'appointments' => AppointmentController::class,
    'prescriptions' => PrescriptionController::class,
    'reports' => ReportController::class,
    'errors' => ErrorController::class,
];

foreach (glob(__DIR__ . '/controllers/*.php') as $file) {
    require_once $file;
}

if (!isset($routes[$page])) {
    (new ErrorController())->notFound();
    exit;
}

$controller = new $routes[$page]();
if ($page === 'errors' && $action === '403') {
    $controller->forbidden();
    exit;
}
if (!method_exists($controller, $action)) {
    (new ErrorController())->notFound();
    exit;
}

$controller->$action();
