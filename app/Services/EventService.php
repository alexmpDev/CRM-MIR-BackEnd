<?php

namespace App\Services;

use App\Mail\EventTicketMail;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventService
{
    public function list()
    {
        try {
            $events = Event::with('courses')->get();
            return json_encode($events);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list events', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOne($id)
    {
        try {
            $event = Event::with('courses')->findOrFail($id);
            return json_encode($event);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Event not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve event', 'message' => $e->getMessage()], 500);
        }
    }

    public function eventCreate($data)
    {
        try {
            $event = Event::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'event_date' => $data['event_date']
            ]);
            return response()->json($event, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create event', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Event not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete event', 'message' => $e->getMessage()], 500);
        }
    }

    public function update($data, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'event_date' => $data['event_date']
            ]);
            return response()->json($event, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Event not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update event', 'message' => $e->getMessage()], 500);
        }
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

        try {
            $event = Event::findOrFail($eventId);
            $event->courses()->sync($courseIds);
            return response()->json(['message' => 'Courses assigned successfully', 'event' => $event], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Event not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to assign courses to event', 'message' => $e->getMessage()], 500);
        }
    }

    public function generateTicketsForEvent($eventId)
    {
        try {
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
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Event not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to generate tickets for event', 'message' => $e->getMessage()], 500);
        }
    }

    private function saveQr($ticketId, $studentId, $eventId)
    {
        try {
            $url = env('BASE_URL', 'http://127.0.0.1:8000') . '/api/tickets/validate/' . $ticketId;
            $qrContent = $url . '?event_id=' . $eventId . '&student_id=' . $studentId;
            $image = QrCode::format('png')->size(200)->generate($qrContent);
            $output_file = 'public/qr/tickets/' . $ticketId . '_' . time() . '.png';
            Storage::disk('local')->put($output_file, $image);
            return $output_file;
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
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

        try {
            $event = Event::findOrFail($eventId);
            $event->courses()->detach($courseIds);
            return response()->json(['message' => 'Specific courses unassigned successfully', 'event' => $event], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Event not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to unassign courses from event', 'message' => $e->getMessage()], 500);
        }
    }

    public function validateTicket($ticketId)
    {
        try {
            $ticket = EventTicket::findOrFail($ticketId);

            if ($ticket->verified) {
                return response()->json(['message' => 'Ticket already verified'], 409);
            }

            $ticket->verified = true;
            $ticket->save();

            return response()->json(['message' => 'Ticket validated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Ticket not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to validate ticket', 'message' => $e->getMessage()], 500);
        }
    }

    public function ticketsGenerated($eventId)
    {
        try {
            $ticketsCount = EventTicket::where('event_id', $eventId)->count();
            return response()->json(['tickets_generated' => $ticketsCount > 0], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to count generated tickets', 'message' => $e->getMessage()], 500);
        }
    }
}
