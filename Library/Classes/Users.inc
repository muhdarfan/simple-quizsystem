<?php


class Users
{
    public function register($type, $user, $pass, $email = '')
    {

    }

    public function login($user, $password, $type)
    {
        switch ($type) {
            case 'student':
                $sql = "SELECT student_id AS id, * FROM tbl_students WHERE student_id = :user AND password = :pass LIMIT 1";
                break;

            case 'admin':
                $sql = "SELECT admin_id AS id, * FROM tbl_admins WHERE admin_id = :user AND password = :pass LIMIT 1";
                break;

            case 'lecturer':
                $sql = "SELECT lecturer_id AS id, * FROM tbl_lecturers WHERE lecturer_id = :user AND password = :pass LIMIT 1";
                break;

            default:
                return 403; // err: user type is unknown;
        }

        $query = Helper::$DB->prepare($sql);
        $query->bindParam(":user", $user);
        $query->bindParam(":pass", $password);

        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);
        if ($user->rowCount() == 0)
            return 404; // user not found / incorrect password

        if ($type == 'student' && strcmp($user['password'], $user['id']) == 0)
            $_SESSION['change_pass'] = true;

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $type;
        return 200;
    }

    public function delete($user)
    {
        $sql = "DELETE FROM tbl_admins WHERE admin_id = :id LIMIT 1";
        $stmt = Helper::$DB->prepare($sql);
        $stmt->bindParam('id', $user);

        $stmt->execute();

        return true;
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        return true;
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id']))
            return true;

        return false;
    }
}
