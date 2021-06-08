<?php


class Subject
{
    public function RetrieveSubject($opt = array())
    {
        try {
            $stmt = Helper::$DB->prepare("SELECT * FROM tbl_subjects");

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getCode());
        }
    }

    public function AddSubject($code, $name)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_subjects(subject_code, name) VALUES (:code, :name)");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name', $name);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getCode());
        }
    }

    public function AssignLecturer($lecturer_id, $code)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_workload(lecturer_id, subject_code) VALUES (:lid, :code)");
            $stmt->bindParam(':lid', $lecturer_id);
            $stmt->bindParam(':code', $code);

            $stmt->execute();
        } catch (PDOException $e) {
            return array('error' => $e->getCode());
        }
    }
}
