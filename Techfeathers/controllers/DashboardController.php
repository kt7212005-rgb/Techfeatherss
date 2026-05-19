<?php
// controllers/DashboardController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController extends BaseController {
    private DashboardModel $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function index() {
        require_login();

        $user = current_user();
        $isAdmin = $user['role'] === 'admin';
        $isManager = $user['role'] === 'manager';

        if ($isAdmin) {
            $data = [
                'totalChickens' => $this->model->getTotalChickens(),
                'dailyEggs' => $this->model->getDailyEggs(date('Y-m-d')),
                'feedInventoryKg' => $this->model->getFeedInventoryKg(),
                'monthlyNetProfit' => $this->model->getMonthlyNetProfit(date('Y-m')),
                'batches' => $this->model->getActiveBatches(),
                'eggTrend' => $this->model->getEggProductionTrend(),
                'pageTitle' => 'Farm Overview',
                'pageDescription' => 'Real-time pulse of your poultry operations.',
                'activeNav' => 'dashboard'
            ];
            $this->render('dashboard', $data);
        } elseif ($isManager) {
            $data = [
                'totalChickens' => $this->model->getTotalChickens(),
                'dailyEggs' => $this->model->getDailyEggs(date('Y-m-d')),
                'eggTrend' => $this->model->getEggProductionTrend(),
                'pageTitle' => 'My Dashboard',
                'pageDescription' => 'Overview of egg production and farm status.',
                'activeNav' => 'dashboard'
            ];
            $this->render('user_dashboard', $data);
        } else {
            // Customer
            $data = [
                'pageTitle' => 'Customer Portal',
                'pageDescription' => 'Welcome to our farm products ordering system.',
                'activeNav' => 'dashboard'
            ];
            $this->render('customer_dashboard', $data);
        }
    }
}
?>