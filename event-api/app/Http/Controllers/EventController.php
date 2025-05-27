<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('category');

        if ($request->has('organizer')) {
            $query->where('organizer', $request->organizer);
        }
        
        if ($request->has('organizer_id')) {
            $query->where('organizer_id', $request->organizer_id);
        }

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('start_datetime', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('end_datetime', '<=', $request->end_date);
        }

        $events = $query->where('is_active', true)->orderBy('start_datetime')->get();

        return response()->json(['data' => $events]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'organizer' => 'required|in:faculty,university',
            'organizer_id' => 'required|integer',
            'quota' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $event = Event::create($request->all());

        return response()->json(['data' => $event, 'message' => 'Event created successfully'], 201);
    }

    public function show($id)
    {
        $event = Event::with(['category', 'registrations'])->findOrFail($id);

        return response()->json(['data' => $event]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $this->validate($request, [
            'title' => 'string|max:255',
            'description' => 'string',
            'location' => 'string|max:255',
            'start_datetime' => 'date',
            'end_datetime' => 'date|after:start_datetime',
            'quota' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer',
        ]);

        $event->update($request->all());

        return response()->json(['data' => $event, 'message' => 'Event updated successfully']);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}