<?php
// controllers/userController.php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // دالة لجلب كل المستخدمين من الموديل
    public function getAllUsers(): array {
        return $this->userModel->getAllUsers();
    }
}
