<?php
// models/UserModel.php

require_once __DIR__ . '/../config/database.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    // 1. إنشاء حساب جديد (Register)
    public function registerUser(string $fullName, string $email, string $password, string $phone = null, string $address = null, string $country = null): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $role = 'customer'; 

        $sql = "INSERT INTO Users (full_name, email, password_hash, phone, address, country, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Register Prepare Error: " . $this->db->error);
            return false;
        }

        $stmt->bind_param('sssssss', $fullName, $email, $hashedPassword, $phone, $address, $country, $role);
        
        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            // عشان لو الإيميل متكرر نمسك الخطأ
            error_log("Register Execute Error: " . $e->getMessage());
            return false;
        }
        
        $stmt->close();
        return $result;
    }

    // 2. تسجيل الدخول (Login)
    public function loginUser(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT id, full_name, email, password_hash, role FROM Users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            // التحقق من الباسورد (قارن المدخل مع password_hash المخزن)
            if (password_verify($password, $user['password_hash'])) {
                // نحذف الهاش من البيانات المرجعة للأمان
                unset($user['password_hash']);
                return $user;
            }
        }
        
        $stmt->close();
        return null;
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, full_name, email, phone, address, country, role FROM Users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        $user = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $user;
    }

    public function updateUser(int $id, string $fullName, string $phone, string $address, string $country): bool
    {
        $stmt = $this->db->prepare("UPDATE Users SET full_name = ?, phone = ?, address = ?, country = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $fullName, $phone, $address, $country, $id);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>