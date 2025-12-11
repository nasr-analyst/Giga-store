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

<<<<<<< HEAD
    // 1. إنشاء حساب جديد (Register)
    public function registerUser(string $fullName, string $email, string $password, string $phone = null, string $address = null, string $country = null): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $role = 'customer'; 

        $sql = "INSERT INTO Users (full_name, email, password_hash, phone, address, country, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
=======
    // 1. إنشاء حساب جديد
    public function registerUser(string $fullName, string $email, string $password, string $phone = null, string $address = null, string $country = null): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';

        $sql = "INSERT INTO Users (full_name, email, password_hash, phone, address, country, role)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Register Prepare Error: " . $this->db->error);
            return false;
        }

        $stmt->bind_param('sssssss', $fullName, $email, $hashedPassword, $phone, $address, $country, $role);
<<<<<<< HEAD
        
        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            // عشان لو الإيميل متكرر نمسك الخطأ
            error_log("Register Execute Error: " . $e->getMessage());
            return false;
        }
        
=======

        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            error_log("Register Execute Error: " . $e->getMessage());
            return false;
        }

>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
        $stmt->close();
        return $result;
    }

<<<<<<< HEAD
    // 2. تسجيل الدخول (Login)
=======
    // 2. تسجيل الدخول
>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
    public function loginUser(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT id, full_name, email, password_hash, role FROM Users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
<<<<<<< HEAD
        
        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            // التحقق من الباسورد (قارن المدخل مع password_hash المخزن)
            if (password_verify($password, $user['password_hash'])) {
                // نحذف الهاش من البيانات المرجعة للأمان
=======

        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
                unset($user['password_hash']);
                return $user;
            }
        }
<<<<<<< HEAD
        
=======

>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
        $stmt->close();
        return null;
    }

<<<<<<< HEAD
=======
    // 3. جلب مستخدم بالـ ID
>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, full_name, email, phone, address, country, role FROM Users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
<<<<<<< HEAD
        
=======

>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
        $user = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $user;
    }

<<<<<<< HEAD
=======
    // 4. تحديث بيانات مستخدم
>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
    public function updateUser(int $id, string $fullName, string $phone, string $address, string $country): bool
    {
        $stmt = $this->db->prepare("UPDATE Users SET full_name = ?, phone = ?, address = ?, country = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $fullName, $phone, $address, $country, $id);
<<<<<<< HEAD
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>
=======

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
}

?>
>>>>>>> 75e420a229a55524ed9567701c3c21fe17ec24aa
