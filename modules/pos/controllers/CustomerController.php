<?php
// modules/pos/controllers/CustomerController.php
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/TenantMiddleware.php';
require_once __DIR__ . '/../models/Customer.php';

class CustomerController {
    public function index() {
        TenantMiddleware::handle();
        AuthMiddleware::handle();

        if (!Tenant::hasSystem('POS System')) {
            die('POS system not subscribed');
        }

        if (Tenant::getPosLevel() < 2) {
             die('Upgrade to POS Standard ($50) or Premium ($100) to manage customers.');
        }

        if (!Auth::hasPermission('pos', 'read')) {
            die('No permission');
        }

        $customers = Customer::getAll();

        include __DIR__ . '/../views/customers.php';
    }

    public function create() {
        TenantMiddleware::handle();
        AuthMiddleware::handle();

        if (!Auth::hasPermission('pos', 'write')) {
            die('No permission');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            include __DIR__ . '/../views/customer_form.php';
        }
    }

    public function edit($id) {
        TenantMiddleware::handle();
        AuthMiddleware::handle();

        if (!Auth::hasPermission('pos', 'write')) {
            die('No permission');
        }

        $customer = Customer::getById($id);
        if (!$customer) {
            die('Customer not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            include __DIR__ . '/../views/customer_form.php';
        }
    }

    public function delete($id) {
        TenantMiddleware::handle();
        AuthMiddleware::handle();

        if (!Auth::hasPermission('pos', 'delete')) {
            die('No permission');
        }

        Customer::delete($id);
        header('Location: /Mekong_CyberUnit/' . Tenant::getCurrent()['subdomain'] . '/pos/customers');
        exit;
    }

    private function store() {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];

        Customer::create($data);
        header('Location: /Mekong_CyberUnit/' . Tenant::getCurrent()['subdomain'] . '/pos/customers');
        exit;
    }

    private function update($id) {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];

        Customer::update($id, $data);
        header('Location: /Mekong_CyberUnit/' . Tenant::getCurrent()['subdomain'] . '/pos/customers');
        exit;
    }
}
?>