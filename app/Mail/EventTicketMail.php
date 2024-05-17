<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Event;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $event;
    public $qrPath;

    public function __construct(Student $student, Event $event, $qrPath)
    {
        $this->student = $student;
        $this->event = $event;
        $this->qrPath = $qrPath;
    }

    public function build()
    {
        if (Storage::disk('local')->exists($this->qrPath)) {
            return $this->view('emails.eventTicket')
                        ->subject("Your Ticket for " . $this->event->name)
                        ->with([
                            'studentName' => $this->student->name,
                            'eventName' => $this->event->name,
                            'eventDate' => $this->event->event_date
                        ])
                        ->attach(storage_path('app/' . $this->qrPath), [
                            'as' => 'EventQRCode.png',
                            'mime' => 'image/png',
                        ]);
        } else {
            Log::error('El archivo QR no existe: ' . $this->qrPath);
            return $this->view('emails.eventTicket')
                        ->subject("Your Ticket for " . $this->event->name)
                        ->with([
                            'studentName' => $this->student->name,
                            'eventName' => $this->event->name,
                            'eventDate' => $this->event->event_date
                        ]);
        }
    }
}
