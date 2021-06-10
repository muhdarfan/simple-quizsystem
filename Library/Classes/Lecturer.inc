<?php


class Lecturer
{
    public static function RetrieveAll()
    {
        return Helper::$DB->query('SELECT lecturer_id, name, updated_at, updated_by FROM tbl_lecturers')->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    public static function AddLecturer($id, $name)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_lecturers(lecturer_id, name, password, updated_by, updated_at) VALUES (:id, :name, :pass, :admin, NOW())");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', strtoupper($name));
            $stmt->bindParam(':pass', $id);
            $stmt->bindParam(':admin', Users::$Session['id']);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    */

    public static function Update($id, $name, $oid)
    {
        try {
            $stmt = Helper::$DB->prepare("UPDATE tbl_lecturers SET lecturer_id = :id, name = :name, updated_by = :admin, updated_at = NOW() WHERE lecturer_id = :oid LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', strtoupper($name));
            $stmt->bindParam(':admin', Users::$Session['id']);
            $stmt->bindParam(':oid', $oid);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function Delete($id)
    {
        try {
            $subjects = Subject::RetrieveByLecturer($id);

            if (isset($subjects['error']) || count($subjects) > 0)
                return array('error' => 'Lecturer can\'t be deleted as they have assigned to one or more subjects.');

            $stmt = Helper::$DB->prepare("DELETE FROM tbl_lecturers WHERE lecturer_id = :code LIMIT 1");
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function AssignWorkload($lecturer_id, $code)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_workload(lecturer_id, subject_code) VALUES (:lid, :code)");
            $stmt->bindParam(':lid', $lecturer_id);
            $stmt->bindParam(':code', $code);

            $stmt->execute();
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
}
