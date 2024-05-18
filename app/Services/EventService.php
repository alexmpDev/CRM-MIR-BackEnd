<?php

namespace App\Services;

use App\Mail\EventTicketMail;
use App\Mail\WelcomeStudentMail;
use App\Models\PhoneInfo;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Course;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EventService
{
    public function list()
    {
        
        $events = Event::with('courses')->get();
        return json_encode($events);
    }
    
    public function listOne($id) {

        $event = Event::with('courses')->where('id', $id)->first();
        return json_encode($event);
    }
    
    
    public function eventCreate($data){
        $event = Event::create([
            'name' => $data['name'],
            'description'=>$data['description'],
            'event_date'=>$data['event_date']
        ]);

        return response()->json($event, 201);

    }

    public function delete($id){
        $user = Event::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    public function update($data, $id){
        
        $event= Event::findOrFail($id);
        $event->update([
            'name' => $data['name'],
            'description'=> $data['description'],
            'event_date'=> $data['event_date']
        ]);

        return response()->json($event, 200);
    }

   
    public function assignCoursesToEvent($eventId, $courseIds)
    {
        $validator = Validator::make(
            ['event_id' => $eventId, 'course_ids' => $courseIds],
            [
                'event_id' => 'required|exists:events,id',
                'course_ids' => 'required|array',
                'course_ids.*' => 'exists:courses,id'
            ]
        );
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $event = Event::findOrFail($eventId);
        $event->courses()->sync($courseIds);  // Sincroniza los cursos con el evento
    
        return response()->json(['message' => 'Courses assigned successfully', 'event' => $event], 200);
    }


    public function generateTicketsForEvent($eventId)
{
    $event = Event::with('courses.students')->findOrFail($eventId);
    foreach ($event->courses as $course) {
        foreach ($course->students as $student) {
            $ticketExists = EventTicket::where('student_id', $student->id)
                                       ->where('event_id', $eventId)
                                       ->exists();
            if (!$ticketExists) {
                $ticket = EventTicket::create([
                    'student_id' => $student->id,
                    'event_id' => $eventId,
                    'verified' => false
                ]);
                $qrPath = $this->saveQr($ticket->id, $student->id, $eventId);
                $ticket->update(['qr' => $qrPath]);
                Mail::to($student->email)->send(new EventTicketMail($student, $event, $qrPath));

            }
        }
    }
    return response()->json(['message' => 'Tickets generated successfully', 'event' => $event], 200);
}




private function saveQr($ticketId, $studentId, $eventId)
{
    
    $url = env('BASE_URL', 'http://127.0.0.1:8000') . '/api/tickets/validate/' . $ticketId;

    // Incluir información del evento y estudiante en el QR para garantizar la unicidad
    $qrContent = $url . '?event_id=' . $eventId . '&student_id=' . $studentId;
    $image = QrCode::format('png')->size(200)->generate($qrContent);
    $output_file = 'public/qr/tickets/' . $ticketId . '_' . time() . '.png';
    Storage::disk('local')->put($output_file, $image);
    return $output_file;
}


public function unassignCoursesFromEvent($eventId, $courseIds)
{
    $validator = Validator::make(
        ['event_id' => $eventId, 'course_ids' => $courseIds],
        [
            'event_id' => 'required|exists:events,id',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id'
        ]
    );

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $event = Event::findOrFail($eventId);
    $event->courses()->detach($courseIds);  // Desvincula los cursos especificados del evento

    return response()->json(['message' => 'Specific courses unassigned successfully', 'event' => $event], 200);
}


public function validateTicket($ticketId)
{
    $ticket = EventTicket::find($ticketId);
    if (!$ticket) {
        return response()->json(['message' => 'Ticket not found'], 404);
    }

    if ($ticket->verified) {
        return response()->json(['message' => 'Ticket already verified'], 409);
    }

    $ticket->verified = true;
    $ticket->save();

    return response()->json(['message' => 'Ticket validated successfully'], 200);
}






}