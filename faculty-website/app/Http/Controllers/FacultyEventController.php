<?php

namespace App\Http\Controllers;

use App\Providers\EventApiService;
use Illuminate\Http\Request;

class FacultyEventController extends Controller
{
    protected $eventApiService;
    
    public function __construct(EventApiService $eventApiService)
    {
        $this->eventApiService = $eventApiService;
    }
    
    public function index()
    {
        $facultyEvents = $this->eventApiService->getFacultyEvents();
        return view('faculty.events.index', compact('facultyEvents'));
    }
    
    public function create()
    {
        return view('faculty.events.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'quota' => 'nullable|integer',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer',
        ]);
        
        $result = $this->eventApiService->createEvent($validated);
        
        return redirect()->route('faculty.events.index')
            ->with('success', 'Event berhasil dibuat');
    }
    
    public function show($id)
    {
        $event = $this->eventApiService->getEventDetails($id);
        return view('faculty.events.show', compact('event'));
    }
    
    public function edit($id)
    {
        $event = $this->eventApiService->getEventDetails($id);
        return view('faculty.events.edit', compact('event'));
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'quota' => 'nullable|integer',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer',
        ]);
        
        $result = $this->eventApiService->updateEvent($id, $validated);
        
        return redirect()->route('faculty.events.show', $id)
            ->with('success', 'Event berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $this->eventApiService->deleteEvent($id);
        
        return redirect()->route('faculty.events.index')
            ->with('success', 'Event berhasil dihapus');
    }
    
    public function register(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'student_name' => 'required|string',
            'student_email' => 'required|email',
        ]);
        
        $result = $this->eventApiService->registerEvent($id, $validated);
        
        return redirect()->route('faculty.events.show', $id)
            ->with('success', 'Berhasil mendaftar event');
    }
}