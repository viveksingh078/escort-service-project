<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VerifyUser;

class EmailHelper
{
    public static function sendVerificationEmail($email)
    {
        try {
            $encryptedEmail = Crypt::encryptString($email);
            $verificationLink = url('/verify-user/' . $encryptedEmail);

            Mail::to($email)->send(new VerifyUser($verificationLink));

            return [
                'status' => true,
                'message' => 'Verification email sent successfully.'
            ];
        } catch (\Exception $e) {
            Log::error("Mail sending failed: " . $e->getMessage());

            return [
                'status' => false,
                'message' => 'Could not send verification email.'
            ];
        }
    }
}
