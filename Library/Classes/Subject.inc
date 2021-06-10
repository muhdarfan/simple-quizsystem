<?php


class Subject
{
    public static function Retrieve($code = false)
    {
        try {
            $sql = "SELECT subject.subject_code, subject.name, admin.name AS updated_by, subject.updated_at FROM tbl_subjects subject LEFT JOIN tbl_admins admin ON subject.updated_by = admin.admin_id";

            if ($code !== false)
                $sql .= " WHERE subject.subject_code = '{$code}'";

            $sql .= " ORDER BY subject.name ASC";

            $stmt = Helper::$DB->query($sql);
            return ($code !== false ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveSubjectLecturer($code, $limit = 1) {
        try {
            $stmt = Helper::$DB->prepare("SELECT l.name, l.lecturer_id FROM tbl_workload w INNER JOIN tbl_lecturers l ON w.lecturer_id = l.lecturer_id WHERE w.subject_code = :code ORDER BY w.updated_at ASC LIMIT " . $limit);
            $stmt->bindParam(':code', $code);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveByLecturer($id = false)
    {
        try {
            $sql = "SELECT subject.subject_code, subject.name AS subject_name, lecturer.name, lecturer.lecturer_id FROM tbl_workload a INNER JOIN tbl_subjects subject ON a.subject_code = subject.subject_code INNER JOIN tbl_lecturers lecturer ON lecturer.lecturer_id = a.lecturer_id";
            if ($id !== false) {
                $sql .= " WHERE a.lecturer_id = " . $id;
            }

            $stmt = Helper::$DB->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveRegisteredSubject($id)
    {
        try {
//            $stmt = Helper::$DB->prepare("SELECT a.id, s.student_id, s.name as student_name, subject.subject_code, subject.name as subject_name FROM tbl_student_subjects a
//    INNER JOIN tbl_students s ON a.student_id = s.student_id
//    INNER JOIN tbl_subjects subject ON a.subject_code = subject.subject_code WHERE a.student_id = :id LIMIT 1");
            $stmt = Helper::$DB->prepare("SELECT sub.name AS subject_name, sub.subject_code FROM tbl_student_subjects s INNER JOIN tbl_subjects sub ON sub.subject_code = s.subject_code WHERE s.student_id = :id ORDER BY sub.name ASC");
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function AddSubject($code, $name)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_subjects(subject_code, name, updated_by, updated_at) VALUES (:code, :name, :admin, NOW())");
            $stmt->bindParam(':code', strtoupper($code));
            $stmt->bindParam(':name', strtoupper($name));
            $stmt->bindParam(':admin', Users::$Session['id']);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function AssignStudent($student_id, $code)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_student_subjects(student_id, subject_code) VALUES (:sid, :code)");
            $stmt->bindParam(':sid', $student_id);
            $stmt->bindParam(':code', $code);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveStudentForSubject($code) {
        try {
            $stmt = Helper::$DB->prepare("SELECT s.name, s.student_id  FROM tbl_student_subjects sub INNER JOIN tbl_students s ON s.student_id = sub.student_id WHERE sub.subject_code = :code");
            $stmt->bindParam(':code', $code);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function AssignLecturer($lecturer_id, $code)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_workload(lecturer_id, subject_code) VALUES (:lid, :code)");
            $stmt->bindParam(':lid', $lecturer_id);
            $stmt->bindParam(':code', $code);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function DeleteSubject($code)
    {
        try {
            $stmt = Helper::$DB->prepare("DELETE FROM tbl_subjects WHERE subject_code = :code LIMIT 1");
            $stmt->bindParam(':code', $code);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    /*
   * ASSIGNMENT
   */

    public static function RetrieveAssignment($code)
    {
        try {
            $stmt = Helper::$DB->prepare("SELECT t.id, t.subject_code, t.name, t.file_name, l.name AS updated_by, t.updated_at FROM tbl_subject_task t INNER JOIN tbl_lecturers l ON l.lecturer_id = t.updated_by WHERE subject_code = :code");
            $stmt->bindParam(':code', $code);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveAssignmentById($id)
    {
        try {
            $stmt = Helper::$DB->prepare("SELECT t.id, t.subject_code, t.name, t.file_name, subject.name AS subject_name FROM tbl_subject_task t
    INNER JOIN tbl_subjects subject ON subject.subject_code = t.subject_code WHERE t.id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function AddAssignment($code, $name, $fileName)
    {
        try {
            $stmt = Helper::$DB->prepare('INSERT INTO tbl_subject_task(subject_code, name, file_name, updated_by) VALUES (:code, :name, :file, :lect)');
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':file', $fileName);
            $stmt->bindParam(':lect', Users::$Session['id']);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function DeleteAssignment($code) {
        try {
            $stmt = Helper::$DB->prepare('DELETE FROM tbl_subject_task WHERE id = :id');
            $stmt->bindParam(':id', $code);

            $stmt2 = Helper::$DB->prepare('DELETE FROM tbl_subject_task_submission WHERE task_id = :id');
            $stmt2->bindParam(':id', $code);

            $stmt->execute();
            $stmt2->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function AddSubmission($task, $sid, $file) {
        try {
            $stmt = Helper::$DB->prepare('INSERT INTO tbl_subject_task_submission(task_id, student_id, file_name) VALUES (:id, :sid, :file)');
            $stmt->bindParam(':id', $task);
            $stmt->bindParam(':sid', $sid);
            $stmt->bindParam(':file', $file);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function DeleteSubmission($id) {
        try {
            $stmt = Helper::$DB->prepare('DELETE FROM tbl_subject_task_submission WHERE id = :id');
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveSubmission($id) {
        try {
            $stmt = Helper::$DB->prepare("SELECT s.file_name, s.student_id, stu.name FROM tbl_subject_task_submission s INNER JOIN tbl_students stu ON s.student_id = stu.student_id WHERE s.task_id = :id ORDER BY uploaded_at ASC");
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveStudentSubmission($id, $sid) {
        try {
            $stmt = Helper::$DB->prepare("SELECT file_name FROM tbl_subject_task_submission WHERE id = :id AND student_id = :sid");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':sid', $sid);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
}
