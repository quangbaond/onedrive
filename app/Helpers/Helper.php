<?php

namespace App\Helpers;

class Helper {
    public static function getLogSubjectType(string $subjectType)
    {
        $subjectType = explode('\\', $subjectType);
        return end($subjectType);

    }


}
