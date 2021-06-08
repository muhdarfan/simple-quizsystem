<?php


class Lecturer
{
    public static function RetrieveAll() {
        return Helper::$DB->query('SELECT lecturer_id, name, updated_at, updated_by FROM tbl_lecturers')->fetchAll(PDO::FETCH_ASSOC);
    }
}
