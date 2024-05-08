<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $qrPath;

    public function __construct($student, $qrPath)
    {
        $this->student = $student;
        $this->qrPath = $qrPath;
    }

    public function build()
    {
        return $this->view('emails.welcome')
                    ->with([
                        'name' => $this->student->name,
                        'qrPath' => $this->qrPath
                    ]);
    }
}
