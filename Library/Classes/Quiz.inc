<?php


class Quiz
{
    public static function AddTF($code, $question, $Answer)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_question_tf(subject_code, question, answer, updated_by) VALUES (:code, :question, :answer, :lect)");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $Answer);
            $stmt->bindParam(':lect', Users::$Session['id']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function UpdateTF($id, $question, $answer)
    {
        try {
            $stmt = Helper::$DB->prepare("UPDATE tbl_question_tf SET question = :question, answer = :answer, updated_by = :lect, updated_at = NOW() WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);
            $stmt->bindParam(':lect', Users::$Session['id']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function DeleteTF($id)
    {
        try {
            // Question
            $stmt = Helper::$DB->prepare("DELETE FROM tbl_question_tf WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Answer
            $stmt = Helper::$DB->prepare("DELETE FROM tbl_question_tf_answer WHERE question_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveTFQuestion($code, $id = false)
    {
        try {
            $sql = "SELECT q.*, l.name as updated_by FROM tbl_question_tf q INNER JOIN tbl_lecturers l ON q.updated_by = l.lecturer_id WHERE subject_code = :code ";
            if ($id !== false)
                $sql .= " AND id = :id";
            $sql .= " ORDER BY id ASC";

            $stmt = Helper::$DB->prepare($sql);
            $stmt->bindParam(':code', $code);
            if ($id !== false)
                $stmt->bindParam(':id', $id);

            $stmt->execute();
            return ($id !== false ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveTFQuestionById($id = false)
    {
        try {
            $stmt = Helper::$DB->prepare("SELECT q.*, l.name as updated_by FROM tbl_question_tf q INNER JOIN tbl_lecturers l ON q.updated_by = l.lecturer_id WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveTFResult($code, $student = false)
    {
        try {
            $stmt = Helper::$DB->prepare("SELECT s.student_id, stu.name, SUM(a.is_right = '1') as correct FROM tbl_student_subjects s LEFT JOIN tbl_question_tf_answer a ON a.student_id = s.student_id INNER JOIN tbl_students stu ON s.student_id = stu.student_id WHERE s.subject_code = :code GROUP BY s.student_id");
            $stmt->bindParam(':code', $code);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($student !== false)
                return array_filter($result, function ($row) use ($student) {
                    return ($row['student_id'] == $student);
                });

            return $result;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function AddStudentTFAnswer($qid, $student, $choice) {
        try {
            $question = self::RetrieveTFQuestionById($qid);
            $correct = ($question['answer'] == $choice) ? '1' : '0';
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_question_tf_answer(question_id, student_id, choice, is_right) VALUES (:qid, :sid, :choice, :answer)");

            $stmt->bindParam(':qid', $qid);
            $stmt->bindParam(':sid', $student);
            $stmt->bindParam(':choice', $choice);
            $stmt->bindParam(':answer', $correct);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function RetrieveTFStudentAnswer($code, $student)
    {
        try {
            $data = Array();
            $stmt = Helper::$DB->prepare("SELECT q.id, q.question, a.choice, a.is_right FROM tbl_question_tf_answer AS a
	INNER JOIN tbl_question_tf AS q ON a.question_id = q.id WHERE q.subject_code = :code AND a.student_id = :id ");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':id', $student);
            $stmt->execute();

            $data['data'] = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);
            $data['answered'] = count($data['data']) > 0;

            $stmt2 = Helper::$DB->prepare("SELECT SUM(a.is_right = '1') AS correct FROM tbl_question_tf_answer a INNER JOIN tbl_question_tf AS q ON a.question_id = q.id WHERE q.subject_code = :code AND a.student_id = :id GROUP BY a.student_id");
            $stmt2->bindParam(':code', $code);
            $stmt2->bindParam(':id', $student);
            $stmt2->execute();

            $data['correct'] = $stmt2->fetch(PDO::FETCH_ASSOC)['correct'];

            return $data;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function ReAttemptTF($code, $student) {
        try {
            $stmt = Helper::$DB->prepare("DELETE a.* FROM tbl_question_tf_answer a INNER JOIN tbl_question_tf q ON a.question_id = q.id WHERE q.subject_code = :code AND a.student_id = :sid");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':sid', $student);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    /*
     * MCQ Quiz
     */

    public static function AddMCQ($code, $question, $answer)
    {
        try {
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_question_mcq(subject_code, question, answer_a, answer_b, answer_c, answer_d, correct_answer, updated_by) VALUES (:code, :question, :answer_a, :answer_b, :answer_c, :answer_d, :correct, :lect)");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer_a', $answer['a']);
            $stmt->bindParam(':answer_b', $answer['b']);
            $stmt->bindParam(':answer_c', $answer['c']);
            $stmt->bindParam(':answer_d', $answer['d']);
            $stmt->bindParam(':correct', $answer['correct']);
            $stmt->bindParam(':lect', Users::$Session['id']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function UpdateMCQ($id, $question, $answer)
    {
        try {
            $stmt = Helper::$DB->prepare("UPDATE tbl_question_mcq SET question = :question, answer_a = :answer_a, answer_b = :answer_b, answer_c = :answer_c, answer_d = :answer_d, correct_answer = :correct, updated_by = :lect, updated_at = NOW() WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer_a', $answer['a']);
            $stmt->bindParam(':answer_b', $answer['b']);
            $stmt->bindParam(':answer_c', $answer['c']);
            $stmt->bindParam(':answer_d', $answer['d']);
            $stmt->bindParam(':correct', $answer['correct']);
            $stmt->bindParam(':lect', Users::$Session['id']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function DeleteMCQ($id)
    {
        try {
            // Question
            $stmt = Helper::$DB->prepare("DELETE FROM tbl_question_mcq WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Answer
            $stmt = Helper::$DB->prepare("DELETE FROM tbl_question_mcq_answer WHERE question_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveMCQQuestion($code, $id = false)
    {
        try {
            $sql = "SELECT q.*, l.name as updated_by FROM tbl_question_mcq q INNER JOIN tbl_lecturers l ON q.updated_by = l.lecturer_id WHERE subject_code = :code ";
            if ($id !== false)
                $sql .= " AND id = :id";
            $sql .= " ORDER BY id ASC";

            $stmt = Helper::$DB->prepare($sql);
            $stmt->bindParam(':code', $code);
            if ($id !== false)
                $stmt->bindParam(':id', $id);

            $stmt->execute();
            return ($id !== false ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveMCQQuestionById($id = false)
    {
        try {
            $stmt = Helper::$DB->prepare("SELECT q.*, l.name as updated_by FROM tbl_question_mcq q INNER JOIN tbl_lecturers l ON q.updated_by = l.lecturer_id WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    public static function RetrieveMCQResult($code)
    {
        try {
            /*
            $stmt = Helper::$DB->prepare("SELECT s.name, s.student_id, SUM(a.is_right = '1') AS correct, SUM(a.is_right = '0') as wrong FROM tbl_question_mcq_answer a
    INNER JOIN tbl_students s ON a.student_id = s.student_id
    WHERE a.question_id IN (SELECT t.id FROM tbl_question_mcq t WHERE t.subject_code = :code)
    GROUP BY a.student_id");
            */

            //$stmt = Helper::$DB->prepare("SELECT a.* FROM tbl_student_subjects s RIGHT JOIN tbl_question_mcq_answer a ON a.student_id = s.student_id WHERE a.question_id IN (SELECT t.id FROM tbl_question_mcq t WHERE t.subject_code = :code)");
            $stmt = Helper::$DB->prepare("SELECT s.student_id, stu.name, SUM(a.is_right = '1') as correct FROM tbl_student_subjects s LEFT JOIN tbl_question_mcq_answer a ON a.student_id = s.student_id INNER JOIN tbl_students stu ON s.student_id = stu.student_id WHERE s.subject_code = :code GROUP BY s.student_id");
            $stmt->bindParam(':code', $code);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function AddStudentMCQAnswer($qid, $student, $choice) {
        try {
            $question = self::RetrieveMCQQuestionById($qid);
            $correct = ($question['correct_answer'] == $choice) ? '1' : '0';
            $stmt = Helper::$DB->prepare("INSERT INTO tbl_question_mcq_answer(question_id, student_id, choice, is_right) VALUES (:qid, :sid, :choice, :answer)");

            $stmt->bindParam(':qid', $qid);
            $stmt->bindParam(':sid', $student);
            $stmt->bindParam(':choice', $choice);
            $stmt->bindParam(':answer', $correct);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function RetrieveMCQStudentAnswer($code, $student)
    {
        try {
            $data = Array();
            $stmt = Helper::$DB->prepare("SELECT q.id, q.question, a.choice, a.is_right FROM tbl_question_mcq_answer AS a
	INNER JOIN tbl_question_mcq AS q ON a.question_id = q.id WHERE q.subject_code = :code AND a.student_id = :id ");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':id', $student);
            $stmt->execute();

            $data['data'] = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);
            $data['answered'] = count($data['data']) > 0;

            $stmt2 = Helper::$DB->prepare("SELECT SUM(a.is_right = '1') AS correct FROM tbl_question_mcq_answer a INNER JOIN tbl_question_mcq AS q ON a.question_id = q.id WHERE q.subject_code = :code AND a.student_id = :id GROUP BY a.student_id");
            $stmt2->bindParam(':code', $code);
            $stmt2->bindParam(':id', $student);
            $stmt2->execute();

            $data['correct'] = $stmt2->fetch(PDO::FETCH_ASSOC)['correct'];

            return $data;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

    public static function ReAttemptMCQ($code, $student) {
        try {
            $stmt = Helper::$DB->prepare("DELETE a.* FROM tbl_question_mcq_answer a INNER JOIN tbl_question_mcq q ON a.question_id = q.id WHERE q.subject_code = :code AND a.student_id = :sid");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':sid', $student);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }

}
