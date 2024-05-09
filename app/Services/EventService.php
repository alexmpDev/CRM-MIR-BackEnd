<?php

namespace App\Services;

use App\Mail\WelcomeStudentMail;
use App\Models\PhoneInfo;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Course;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class EventService
{
    public function list()
    {
        
        $events = Event::with('courses')->get();
        return json_encode($events);
    }
    
    public function listOne($id) {

        $event = Event::where('id', $id)->get();
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

    // public function assignCoursesToEvent($eventId, $courseIds)
    // {
    //     $event = Event::findOrFail($eventId);
    //     $event->courses()->sync($courseIds);  // Sincroniza los cursos con el evento
    
    //     return response()->json(['message' => 'Courses assigned successfully', 'event' => $event], 200);
    // }
    

  
    // public function generateTicketsForEvent($eventId)
    // {
    //     $event = Event::with('courses.students')->findOrFail($eventId);
    
    //     foreach ($event->courses as $course) {
    //         foreach ($course->students as $student) {
    //             EventTicket::create([
    //                 'student_id' => $student->id,
    //                 'event_id' => $eventId
    //             ]);
    //         }
    //     }
    
    //     return response()->json(['message' => 'Tickets generated successfully', 'event' => $event], 200);
    // }
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
            // Verificar si ya existe un ticket para evitar duplicados
            $ticketExists = EventTicket::where('student_id', $student->id)
                                       ->where('event_id', $eventId)
                                       ->exists(); // Asegúrate de que 'exists()' esté en la misma línea con '->'

            if (!$ticketExists) {
                EventTicket::create([
                    'student_id' => $student->id,
                    'event_id' => $eventId,
                    'verified' => false // Asumiendo que quieres que los tickets estén no verificados al crearlos
                ]);
            }
        }
    }

    return response()->json(['message' => 'Tickets generated successfully', 'event' => $event], 200);
}






}