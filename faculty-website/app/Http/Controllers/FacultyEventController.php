<?php

namespace App\Http\Controllers;

use App\Providers\EventApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacultyEventController extends Controller
{
    protected $eventApiService;
   
    public function __construct(EventApiService $eventApiService)
    {
        $this->eventApiService = $eventApiService;
    }
   
    public function welcome()
{
    $events = $this->eventApiService->getAllEvents();
    
    // Filter hanya event yang akan datang (start_datetime > sekarang)
    $upcomingEvents = array_filter($events, function($event) {
        return strtotime($event['start_datetime']) > time();
    });

    // Urutkan berdasarkan tanggal terdekat
    usort($upcomingEvents, function($a, $b) {
        return strtotime($a['start_datetime']) - strtotime($b['start_datetime']);
    });

    // Ambil hanya 3 event terdekat
    $upcomingEvents = array_slice($upcomingEvents, 0, 3);

    Log::info("Retrieved upcoming events", ['count' => count($upcomingEvents)]);
    
    return view('welcome', ['upcomingEvents' => $upcomingEvents]);
}
    public function index(Request $request)
    {
        try {
            // Ambil parameter filter dari request
            $filters = [
                'category' => $request->get('category'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
            ];
            
            $facultyEvents = $this->eventApiService->getFacultyEvents($filters);
            return view('faculty.events.index', compact('facultyEvents'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data event: ' . $e->getMessage());
        }
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
            'quota' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer|in:1,2,3,4', 
        ]);
       
        try {
            $result = $this->eventApiService->createEvent($validated);
            return redirect()->route('faculty.events.index')
                ->with('success', 'Event berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat event: ' . $e->getMessage())
                ->withInput();
        }
    }
   
    public function show($id)
    {
        try {
            $event = $this->eventApiService->getEventDetails($id);
            return view('faculty.events.show', compact('event'));
        } catch (\Exception $e) {
            return redirect()->route('faculty.events.index')
                ->with('error', 'Gagal memuat detail event: ' . $e->getMessage());
        }
    }
   
    public function edit($id)
    {
        try {
            $event = $this->eventApiService->getEventDetails($id);
            return view('faculty.events.edit', compact('event'));
        } catch (\Exception $e) {
            return redirect()->route('faculty.events.index')
                ->with('error', 'Gagal memuat data event: ' . $e->getMessage());
        }
    }
   
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'quota' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer|in:1,2,3,4', // Ganti exists dengan in
        ]);
       
        try {
            $result = $this->eventApiService->updateEvent($id, $validated);
            return redirect()->route('faculty.events.show', $id)
                ->with('success', 'Event berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui event: ' . $e->getMessage())
                ->withInput();
        }
    }
   
    public function destroy($id)
    {
        try {
            $this->eventApiService->deleteEvent($id);
            return redirect()->route('faculty.events.index')
                ->with('success', 'Event berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
    }
   
    public function register(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'student_name' => 'required|string',
            'student_email' => 'required|email',
        ]);
       
        try {
            $result = $this->eventApiService->registerEvent($id, $validated);
            return redirect()->route('faculty.events.show', $id)
                ->with('success', 'Berhasil mendaftar event');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mendaftar event: ' . $e->getMessage());
        }
    }
}