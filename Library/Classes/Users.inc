<?php


class Users
{
    public static $Session = false;

    public static function CheckSession()
    {
        if (isset($_SESSION['user_id'])) {
            self::$Session = self::LoadUserData($_SESSION['user_id'], $_SESSION['user_type']);
            self::$Session['type'] = $_SESSION['user_type'];

            if (isset($_SESSION['change_pass']) && ($_SERVER['SCRIPT_NAME'] !== '/changepass.php' && $_SERVER['SCRIPT_NAME'] !== '/logout.php'))
                Helper::Redirect('/changepass.php');
        }
    }

    public static function CheckAuth()
    {
        $url = $_SERVER['SCRIPT_NAME'];
        $path = explode('/', $url);

        if ($url == '/index.php')
            return;

        if (strcmp($path[1], 'admin') !== 0 && self::$Session['type'] != 'admin')
            Helper::Redirect('/');

        if (strcmp($path[1], 'lecturer') !== 0 && self::$Session['type'] != 'lecturer')
            Helper::Redirect('/');

        if (strcmp($path[1], 'student') !== 0 && self::$Session['type'] != 'student ')
            Helper::Redirect('/');

        if (isset($_SESSION['change_pass']) && ($_SERVER['SCRIPT_NAME'] !== '/changepass.php' && $_SERVER['SCRIPT_NAME'] !== '/logout.php'))
            Helper::Redirect('/changepass.php');
    }

    private static function LoadUserData($ID, $Type)
    {
        if ($Type == 'student')
            $sql = "SELECT student_id AS id, name FROM tbl_students WHERE student_id = :id LIMIT 1";
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

    public static function RetrieveAll($type)
    {
        if ($type == 'admin')
            $sql = "SELECT admin_id, name FROM tbl_admins";
        elseif ($type == 'lecturer')
            $sql = "SELECT lecturer.lecturer_id, lecturer.name, lecturer.updated_at, admin.name AS updated_by FROM tbl_lecturers lecturer LEFT JOIN tbl_admins admin ON lecturer.updated_by = admin.admin_id";
        elseif ($type == 'student')
            $sql = "SELECT student.student_id, student.name, student.updated_at, admin.name AS updated_by FROM tbl_students student LEFT JOIN tbl_admins admin ON student.updated_by = admin.admin_id";
        else
            return [];

        return Helper::$DB->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function RetrieveById($type, $id)
    {
        if ($type == 'admin')
            $sql = "SELECT admin_id AS id, name FROM tbl_admins WHERE admin_id = {$id}";
        elseif ($type == 'lecturer')
            $sql = "SELECT lecturer_id AS id, name FROM tbl_lecturers WHERE lecturer_id = {$id}";
        elseif ($type == 'student')
            $sql = "SELECT student_id AS id, name FROM tbl_students WHERE student_id = '{$id}'";
        else
            return [];

        return Helper::$DB->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public static function ChangePassword($Password)
    {
        try {
            if (!self::$Session)
                return false;

            if (self::$Session['type'] == 'admin')
                $sql = "UPDATE tbl_admins SET password = :pass WHERE admin_id = :id LIMIT 1";
            elseif (self::$Session['type'] == 'lecturer')
                $sql = "UPDATE tbl_lecturers SET password = :pass WHERE lecturer_id = :id LIMIT 1";
            elseif (self::$Session['type'] == 'student')
                $sql = "UPDATE tbl_students SET password = :pass WHERE student_id = :id LIMIT 1";
            else
                return array('error' => "Bad Request");

            $stmt = Helper::$DB->prepare($sql);
            $stmt->bindParam(':pass', $Password);
            $stmt->bindParam(':id', self::$Session['id']);
            $stmt->execute();

            unset($_SESSION['change_pass']);

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
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

        if (strcmp($user['password'], $user['id']) == 0)
            $_SESSION['change_pass'] = true;

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $type;
        return 200;
    }

    public static function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        return true;
    }

    public static function register($type, $id, $name, $pass = false)
    {
        try {
            if ($type == 'admin')
                $sql = "INSERT INTO tbl_admins(admin_id, name, password) VALUES (:id, :name, :password)";
            elseif ($type == 'lecturer')
                $sql = "INSERT INTO tbl_lecturers(lecturer_id, name, password, updated_by) VALUES (:id, :name:, :password, :admin)";
            elseif ($type == 'student')
                $sql = "INSERT INTO tbl_students(student_id, name, password, updated_by) VALUES (:id, :name, :password, :admin)";
            else
                return array('error' => 'User type not provided.');

            $name = strtoupper($name);
            $pass = ($pass !== false) ? $pass : $id;
            $stmt = Helper::$DB->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $pass);

            if ($type == 'lecturer' || $type == 'student') {
                $stmt->bindParam(':admin', Users::$Session['id']);
            }

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }

        return array('error' => 'Bad Request');
    }

    public static function update($type, $oid, $id, $name)
    {
        try {
            if ($type == 'admin')
                $sql = "UPDATE tbl_admins SET admin_id = :id, name = :name WHERE admin_id = :oid LIMIT 1";
            elseif ($type == 'lecturer')
                $sql = "UPDATE tbl_lecturers SET lecturer_id = :id, name = :name, updated_by = :admin, updated_at = NOW() WHERE lecturer_id = :oid LIMIT 1";
            elseif ($type == 'student')
                $sql = "UPDATE tbl_students SET student_id = :id, name = :name, updated_by = :admin, updated_at = NOW() WHERE student_id = :oid LIMIT 1";
            else
                return array('error' => 'User type not provided.');

            $name = strtoupper($name);

            $stmt = Helper::$DB->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':oid', $oid);


            if ($type == 'lecturer' || $type == 'student') {
                $stmt->bindParam(':admin', Users::$Session['id']);
            }

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }

        return array('error' => 'Bad Request');
    }

    public static function delete($type, $ID)
    {
        try {
            switch ($type) {
                case 'student':

                    $stmt = Helper::$DB->prepare('DELETE FROM tbl_students WHERE student_id = :id LIMIT 1');
                    $stmt->bindParam(':id', $ID);
                    $stmt->execute();
                    return true;

                case 'lecturer':

                    $stmt = Helper::$DB->prepare('DELETE FROM tbl_lecturers WHERE lecturer_id = :id LIMIT 1');
                    $stmt->bindParam(':id', $ID);
                    $stmt->execute();
                    return true;

                case 'admin':
                    if (Admin::hasModified($ID))
                        return array('error' => 'Unsuccessful! Admin data is being used at other tables.');

                    $stmt = Helper::$DB->prepare('DELETE FROM tbl_admins WHERE admin_id = :id LIMIT 1');
                    $stmt->bindParam(':id', $ID);
                    $stmt->execute();

                    return true;

                default:
                    return array('error' => 'Bad Request');
            }
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function HasAccess($type)
    {
        return (self::$Session && self::$Session['type'] === $type);
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['user_id']))
            return true;

        return false;
    }
}
