<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        protected  EventService $eventService,
    ) {}
   
    public function index()
    {
        return $this->eventService->list();
    }
    
    public function store(EventRequest $request)
    {

        return $this->eventService->eventCreate($request->all());

    }

    public function show(string $id)
    {
        return $this->eventService->listOne($id);
    }

    public function update(EventRequest $request, $id)
    {
        return $this->eventService->update($request->all(), $id);
    }

    public function update_workaround(EventRequest $request, $id)
    {
        return $this->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->eventService->delete($id);
    }

    public function assignCourses(Request $request)
    {

        $courseIds = $request->input('course_ids');
        $eventId = $request->input('event_id');
        return $this->eventService->assignCoursesToEvent($eventId, $courseIds);
    }

    public function generateTickets(Request $request)
    {
        $eventId = $request->input('event_id');  // Obtener el ID del evento del cuerpo de la solicitud
    
        if (!$eventId) {
            return response()->json(['message' => 'Event ID is required'], 400);
        }
    
        return $this->eventService->generateTicketsForEvent($eventId);
    }
    
    public function unassignCourses(Request $request, $eventId)
    {
        $courseIds = $request->input('course_ids');
        return $this->eventService->unassignCoursesFromEvent($eventId, $courseIds);
    }


    
}
