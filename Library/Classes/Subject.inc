<?php


class Subject
{
    public function RetrieveSubject($opt = Array()) {
        try {
            $stmt = Helper::$DB->prepare('');
            
        } catch (PDOException $e) {

        }
    }

    public function AddSubject($code, $name) {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_subjects(subject_code, name) VALUES (:code, :name)");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name', $name);
        } catch (PDOException $e) {

        }
    }

    public function AssignLecturer($lecturer_id, $code) {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_workload(lecturer_id, subject_code) VALUES (:lid, :code)");
            $stmt->bindParam(':lid', $lecturer_id);
            $stmt->bindParam(':code', $code);
        } catch (PDOException $e) {

        }
    }
}
