<?php

namespace App\Library;

namespace App\Library;

class MyHelpers
{
    public static function formatEmail($email)
    {
        return '<a href="mailto:' . $email . '">' . $email . '</a>';
    }
}
