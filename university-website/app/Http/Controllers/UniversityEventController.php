<?php

namespace App\Http\Controllers;

use App\Providers\EventApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UniversityEventController extends Controller
{
    protected $eventApiService;
   
    public function __construct(EventApiService $eventApiService)
    {
        $this->eventApiService = $eventApiService;
    }
   
    public function welcome()
{
    // Test koneksi terlebih dahulu
    if (!$this->eventApiService->testConnection()) {
        Log::error("Cannot connect to Event API");
        return view('welcome', [
            'upcomingEvents' => [],
            'error' => 'Tidak dapat terhubung ke Event API. Pastikan Event API sedang berjalan.'
        ]);
    }

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
        Log::info("University events index called");
       
        // Test koneksi terlebih dahulu
        if (!$this->eventApiService->testConnection()) {
            Log::error("Cannot connect to Event API");
            return view('university.events.index', [
                'universityEvents' => [],
                'error' => 'Tidak dapat terhubung ke Event API. Pastikan Event API sedang berjalan.'
            ]);
        }

        // Ambil parameter filter dari request
        $filters = [
            'category' => $request->get('category'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $events = $this->eventApiService->getUniversityEvents($filters);
        Log::info("Retrieved university events", ['count' => count($events), 'filters' => $filters]);
       
        return view('university.events.index', ['universityEvents' => $events]);
    }
   
    public function create()
    {
        Log::info("Create event form requested");
       
        // Test koneksi sebelum menampilkan form
        if (!$this->eventApiService->testConnection()) {
            Log::error("Cannot connect to Event API for create form");
            return redirect()->route('university.events.index')
                ->with('error', 'Tidak dapat terhubung ke Event API. Pastikan Event API sedang berjalan.');
        }
       
        return view('university.events.create');
    }
   
    public function store(Request $request)
    {
        // Test koneksi terlebih dahulu
        if (!$this->eventApiService->testConnection()) {
            return back()->with('error', 'Tidak dapat terhubung ke Event API. Pastikan Event API sedang berjalan.')
                        ->withInput();
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'quota' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'category_id' => 'nullable|integer|in:1,2,3,4' 
        ]);

        $result = $this->eventApiService->createEvent($validated);

        if (isset($result['error'])) {
            $errorMessage = $result['message'] ?? 'Gagal membuat event. Silakan coba lagi.';
            return back()->with('error', $errorMessage)->withInput();
        }

        return redirect()->route('university.events.index')
            ->with('success', 'Event berhasil dibuat');
    }
   
    public function show($id)
    {
        Log::info("Show event requested", ['id' => $id]);
       
        try {
            $event = $this->eventApiService->getEventDetails($id);
           
            if (!$event) {
                Log::error("Event not found or API not accessible", ['id' => $id]);
                return redirect()->route('university.events.index')
                    ->with('error', 'Event tidak ditemukan atau API tidak dapat diakses.');
            }
           
            Log::info("Event retrieved successfully", ['event_id' => $id, 'title' => $event['title'] ?? 'unknown']);
            return view('university.events.show', compact('event'));
        } catch (\Exception $e) {
            Log::error('Error fetching event details: ' . $e->getMessage(), ['id' => $id]);
            return redirect()->route('university.events.index')
                ->with('error', 'Gagal memuat detail event: ' . $e->getMessage());
        }
    }
   
    public function edit($id)
    {
        Log::info("Edit event requested", ['id' => $id]);
       
        try {
            $event = $this->eventApiService->getEventDetails($id);
           
            if (!$event) {
                Log::error("Event not found for edit", ['id' => $id]);
                return redirect()->route('university.events.index')
                    ->with('error', 'Event tidak ditemukan atau API tidak dapat diakses.');
            }
           
            return view('university.events.edit', compact('event'));
        } catch (\Exception $e) {
            Log::error('Error fetching event for edit: ' . $e->getMessage(), ['id' => $id]);
            return redirect()->route('university.events.index')
                ->with('error', 'Gagal memuat data event: ' . $e->getMessage());
        }
    }
   
    public function update(Request $request, $id)
    {
        Log::info("Update event requested", ['id' => $id, 'data' => $request->all()]);
       
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
           
            if ($result && isset($result['data'])) {
                Log::info("Event updated successfully", ['id' => $id]);
                return redirect()->route('university.events.show', $id)
                    ->with('success', 'Event berhasil diperbarui');
            } else {
                $errorMsg = 'Gagal memperbarui event.';
                if ($result && isset($result['message'])) {
                    $errorMsg .= ' Error: ' . $result['message'];
                }
               
                Log::error("Failed to update event", ['id' => $id, 'result' => $result]);
                return back()->with('error', $errorMsg)->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'Terjadi kesalahan saat memperbarui event: ' . $e->getMessage())
                        ->withInput();
        }
    }
   
    public function destroy($id)
    {
        Log::info("Delete event requested", ['id' => $id]);
       
        try {
            $result = $this->eventApiService->deleteEvent($id);
           
            if ($result) {
                Log::info("Event deleted successfully", ['id' => $id]);
                return redirect()->route('university.events.index')
                    ->with('success', 'Event berhasil dihapus');
            } else {
                Log::error("Failed to delete event", ['id' => $id]);
                return back()->with('error', 'Gagal menghapus event.');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage(), ['id' => $id]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus event: ' . $e->getMessage());
        }
    }
   
    public function register(Request $request, $id)
    {
        Log::info("Register for event requested", ['event_id' => $id, 'data' => $request->all()]);
       
        try {
            $validated = $request->validate([
                'student_id' => 'required|string',
                'student_name' => 'required|string',
                'student_email' => 'required|email',
            ]);

            $result = $this->eventApiService->registerEvent($id, $validated);

            if ($result === null) {
                Log::error("API returned null response for registration", ['event_id' => $id]);
                throw new \Exception('API returned null response');
            }

            Log::info("Registration successful", ['event_id' => $id, 'student_id' => $validated['student_id']]);
            return redirect()->route('university.events.show', $id)
                ->with('success', 'Berhasil mendaftar event');
               
        } catch (\Exception $e) {
            Log::error('Event registration failed: ' . $e->getMessage(), [
                'exception' => $e,
                'event_id' => $id,
                'input' => $request->all()
            ]);
           
            return back()->with('error', 'Gagal mendaftar: ' . $e->getMessage())
                        ->withInput();
        }
    }
   
    // Method tambahan untuk debug API status
    public function apiStatus()
    {
        $status = $this->eventApiService->getApiStatus();
        return response()->json($status);
    }
}