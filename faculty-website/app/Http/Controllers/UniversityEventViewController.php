<?php

namespace App\Http\Controllers;

use App\Providers\EventApiService;
use Illuminate\Http\Request;

class UniversityEventViewController extends Controller
{
    protected $eventApiService;
   
    public function __construct(EventApiService $eventApiService)
    {
        $this->eventApiService = $eventApiService;
    }
   
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $filters = [
            'category' => $request->get('category'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        
        $universityEvents = $this->eventApiService->getUniversityEvents($filters);
        return view('university.events.index', compact('universityEvents'));
    }
   
    public function show($id)
    {
        $event = $this->eventApiService->getEventDetails($id);
        return view('university.events.show', compact('event'));
    }
   
    public function register(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|string',
            'student_name' => 'required|string',
            'student_email' => 'required|email',
        ]);
       
        $result = $this->eventApiService->registerEvent($id, $validated);
       
        return redirect()->route('university.events.show', $id)
            ->with('success', 'Berhasil mendaftar event');
    }
}