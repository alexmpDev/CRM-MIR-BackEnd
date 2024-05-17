<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        // Asegura que el archivo QR existe y es accesible
        if (Storage::disk('local')->exists($this->qrPath)) {
            return $this->view('emails.welcome')
                        ->subject(`Benvinugts a l'institut Joaquim Mir`)  // Puedes personalizar el asunto del correo
                        ->with([
                            'name' => $this->student->name,
                            'course_id' => $this->student->course_id // Asegúrate de enviar todos los datos necesarios a la vista
                        ])
                        // Adjunta el archivo QR utilizando el path absoluto en el servidor
                        ->attach(storage_path('app/' . $this->qrPath), [
                            'as' => 'QRCode.png',
                            'mime' => 'image/png',
                        ]);
        } else {
            // Manejo en caso de que el archivo no exista o no se pueda leer
            Log::error('El archivo QR no existe: ' . $this->qrPath);
            return $this->view('emails.welcome')
                        ->subject('Bienvenido a nuestro Curso')  // Puedes personalizar el asunto del correo
                        ->with([
                            'name' => $this->student->name,
                            'course_id' => $this->student->course_id // Asegúrate de enviar todos los datos necesarios a la vista
                        ]);
        }
    }
}
