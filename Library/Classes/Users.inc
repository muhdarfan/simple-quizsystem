<?php


class Users
{
    public static $Session = false;

    public static function CheckSession()
    {
        if (isset($_SESSION['user_id'])) {
            self::$Session = self::LoadUserData($_SESSION['user_id'], $_SESSION['user_type']);
            self::$Session['type'] = $_SESSION['user_type'];
        }
    }

    private static function LoadUserData($ID, $Type)
    {
        if ($Type == 'student')
            $sql = "SELECT student_id AS id, name, email FROM tbl_students WHERE student_id = :id LIMIT 1";
        elseif ($Type == 'admin')
            $sql = "SELECT admin_id AS id, name FROM tbl_admins WHERE admin_id = :id LIMIT 1";
        elseif ($Type == 'lecturer')
            $sql = "SELECT lecturer_id AS id, name FROM tbl_lecturers WHERE lecturer_id = :id LIMIT 1";
        else {
            self::logout();
            return null;
        }

        try {
            $stmt = Helper::$DB->prepare($sql);
            $stmt->bindParam(':id', $ID);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            self::logout();
            return null;
        }
    }

    public static function register($type, $id, $name, $pass = '', $email = '')
    {
        try {
            if ($type == 'student') {
                $sql = "INSERT INTO tbl_students VALUES (:id, :name, :email, :id)";

                $stmt = Helper::$DB->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);

                $stmt->execute();

                return 200;
            } elseif ($type == 'admin' || $type == 'lecturer') {
                $sql = '';
                if ($type == 'admin')
                    $sql = "INSERT INTO tbl_admins VALUES (:id, :name, :password)";
                elseif ($type == 'lecturer')
                    $sql = "INSERT INTO tbl_lecturers VALUES (:id, :name:, :password)";

                $stmt = Helper::$DB->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':password', $pass);

                $stmt->execute();

                return 200;
            }
        } catch (PDOException $e) {
            return $e->getCode();
        }

        return 404;
    }

    public static function login($user, $password, $type)
    {
        switch ($type) {
            case 'student':
                $sql = "SELECT a.student_id AS id, a.* FROM tbl_students a WHERE a.student_id = :student AND a.password = :pass LIMIT 1";
                break;

            case 'admin':
                $sql = "SELECT a.admin_id AS id, a.* FROM tbl_admins a WHERE a.admin_id = :student AND a.password = :pass LIMIT 1";
                break;

            case 'lecturer':
                $sql = "SELECT a.lecturer_id AS id, a.* FROM tbl_lecturers a WHERE a.lecturer_id = :student AND a.password = :pass LIMIT 1";
                break;

            default:
                return 403; // err: student type is unknown;
        }

        $query = Helper::$DB->prepare($sql);
        $query->bindParam(":student", $user);
        $query->bindParam(":pass", $password);

        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);
        if ($query->rowCount() == 0)
            return 404; // student not found / incorrect password

        if ($type == 'student' && strcmp($user['password'], $user['id']) == 0)
            $_SESSION['change_pass'] = true;

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $type;
        return 200;
    }

    public static function delete($user)
    {
        $sql = "DELETE FROM tbl_admins WHERE admin_id = :id LIMIT 1";
        $stmt = Helper::$DB->prepare($sql);
        $stmt->bindParam('id', $user);

        $stmt->execute();

        return true;
    }

    public static function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        return true;
    }

    public static function HasAccess($type) {
        return (self::$Session && self::$Session['type'] === $type);
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['user_id']))
            return true;

        return false;
    }
}
