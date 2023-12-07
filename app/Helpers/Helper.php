<?php

namespace App\Helpers;

class Helper {
    public static function getLogSubjectType(string $subjectType)
    {
        $subjectType = explode('\\', $subjectType);
        switch (end($subjectType)) {
            case 'User':
                return 'người dùng';
            case 'Post':
                return 'bài đăng';
            case 'Industry':
                return 'ngành nghề';
            case 'Group':
                return 'nhóm';
            case 'Permission':
                return 'quyền';
            case 'Cv':
                return 'CV';
            default:
                return end($subjectType);
        }
    }

    public static function getLogDescription(string $description)
    {
        switch ($description) {
            case 'created':
                return 'tạo mới';
            case 'updated':
                return 'cập nhật';
            case 'deleted':
                return 'xóa';
            case 'restored':
                return 'khôi phục';
            default:
                return $description;
        }
    }

    public static function getLogCauser(string $causer)
    {
        $causer = json_decode($causer, true);
        return $causer['name'] . ' - ' .$causer['email'];
    }

    public static function getLogSubject(string $subject)
    {
        $subject = json_decode($subject, true);
        return $subject['name'] ?? $subject['title'] ?? $subject['email'] ?? $subject['slug'] ?? $subject['id'];
    }
}
