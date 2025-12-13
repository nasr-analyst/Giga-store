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

    // 1. إنشاء حساب جديد
    public function registerUser(string $fullName, string $email, string $password, string $phone = null, string $address = null, string $country = null): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';

        $sql = "INSERT INTO Users (full_name, email, password_hash, phone, address, country, role)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Register Prepare Error: " . $this->db->error);
            return false;
        }

        $stmt->bind_param('sssssss', $fullName, $email, $hashedPassword, $phone, $address, $country, $role);

        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Register Execute Error: " . $e->getMessage());
            return false;
        }

        $stmt->close();
        return $result;
    }

    // 2. تسجيل الدخول
    public function loginUser(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT id, full_name, email, password_hash, role FROM Users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']);
                return $user;
            }
        }

        $stmt->close();
        return null;
    }

    // 3. جلب مستخدم بالـ ID
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

    // 4. تحديث بيانات مستخدم
    public function updateUser(int $id, string $fullName, string $phone, string $address, string $country): bool
    {
        $stmt = $this->db->prepare("UPDATE Users SET full_name = ?, phone = ?, address = ?, country = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $fullName, $phone, $address, $country, $id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    //  5. دالة  لجلب كل المستخدمين
    public function getAllUsers(): array
    {
        $sql = "SELECT id, full_name, email, phone, address, country, role FROM Users ORDER BY id ASC";
        $result = $this->db->query($sql);

        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // Get user by email (returns assoc array or null)
    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT id, full_name, email, phone, address, country, role FROM Users WHERE email = ?");
        if (!$stmt) {
            error_log("getUserByEmail prepare error: " . $this->db->error);
            return null;
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $user;
    }

    // Delete a user by id (admin)
    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Users WHERE id = ?");
        if (!$stmt) {
            error_log("deleteUser prepare error: " . $this->db->error);
            return false;
        }
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        if (!$ok)
            error_log("deleteUser execute error: " . $stmt->error);
        $stmt->close();
        return $ok;
    }
}

?>