<?php


class Admin
{
    public static function RetrieveAll() {
        return Helper::$DB->query('SELECT admin_id, name FROM tbl_admins')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function hasModified($ID) {
        try {
            $stmt = Helper::$DB->prepare("SELECT 1 FROM tbl_admins a WHERE (EXISTS (SELECT 1 FROM tbl_subjects s WHERE a.admin_id = s.updated_by) OR EXISTS (SELECT 1 FROM tbl_lecturers l WHERE a.admin_id = l.updated_by) OR EXISTS (SELECT 1 FROM tbl_students st WHERE a.admin_id = st.updated_by) ) AND a.admin_id = :id");
            $stmt->bindParam(':id', $ID);

            $stmt->execute();

            if ($stmt->fetchColumn() > 0)
                return true;
        } catch (PDOException $e) {
            return true;
        }

        return false;
    }
}
