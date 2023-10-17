<?php

namespace App\Helpers;
use App\Models\Setting;

use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function PasswordCreateTokenMail($user)
    {
        $email = Setting::find(1, ['contact_email']);

        $class1 = new NewAppointment($appointment);
        self::send_mail($appointment->email, $class1);

        $class2 = new NewAdminAppointment($appointment);
        self::send_mail($email->contact_email, $class2);
        return true;
    }
    
    public static function send_mail($email, $class)
    {
        Mail::to($email)
            ->bcc('rohandesigndirect@gmail.com')
            ->send($class);
        return true;
    }   

}

