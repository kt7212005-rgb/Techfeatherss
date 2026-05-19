<?php
// controllers/FeedController.php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/FeedModel.php';

class FeedController extends BaseController {
    private FeedModel $model;

    public function __construct() {
        $this->model = new FeedModel();
    }

    public function index() {
        require_role(['admin', 'manager']);

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $quantity = (float) ($_POST['quantity_kg'] ?? 0);
            $unitCost = (float) ($_POST['unit_cost'] ?? 0);

            if ($name === '' || $quantity <= 0) {
                $message = 'Please provide a feed name and a valid quantity.';
            } else {
                $this->model->addFeed($name, $quantity, $unitCost);
                $message = 'Feed inventory item added.';
            }
        }

        $data = [
            'message' => $message,
            'feeds' => $this->model->getFeeds(),
            'pageTitle' => 'Feed Inventory',
            'pageDescription' => 'Track feed stock levels and cost for your flock.',
            'activeNav' => 'feed'
        ];

        $this->render('feed', $data);
    }
}
?>