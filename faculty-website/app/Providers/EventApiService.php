<?php
namespace App\Providers;

use Illuminate\Support\Facades\Http;

class EventApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $apiKey;
    protected $facultyId;

    public function __construct()
    {
        $this->baseUrl = env('EVENT_API_URL', 'http://169.254.64.59:8000/api');
        $this->facultyId = env('FACULTY_ID', 1);
        $this->timeout = 10;
        $this->apiKey = env('EVENT_API_KEY');
    }

    public function getAllEvents($filters = [])
    {
         $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->timeout($this->timeout)
        ->get($this->baseUrl . '/events', [
            'is_active' => true // Pastikan hanya mengambil event aktif
        ]);
        // Build query parameters
        $queryParams = [];
        
        if (!empty($filters['category'])) {
            $queryParams['category_id'] = $filters['category'];
        }
        
        if (!empty($filters['start_date'])) {
            $queryParams['start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $queryParams['end_date'] = $filters['end_date'];
        }
        
        $response = Http::get("{$this->baseUrl}/events", $queryParams);
        
        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }
        
        return [];
    }

    public function getFacultyEvents($filters = [])
    {
        // Build query parameters including organizer filter
        $queryParams = [
            'organizer' => 'faculty',
            'organizer_id' => $this->facultyId
        ];
        
        if (!empty($filters['category'])) {
            $queryParams['category_id'] = $filters['category'];
        }
        
        if (!empty($filters['start_date'])) {
            $queryParams['start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $queryParams['end_date'] = $filters['end_date'];
        }
        
        $response = Http::get("{$this->baseUrl}/events", $queryParams);
        
        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }
        
        return [];
    }

    public function getUniversityEvents($filters = [])
    {
        // Build query parameters including organizer filter
        $queryParams = [
            'organizer' => 'university'
        ];
        
        if (!empty($filters['category'])) {
            $queryParams['category_id'] = $filters['category'];
        }
        
        if (!empty($filters['start_date'])) {
            $queryParams['start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $queryParams['end_date'] = $filters['end_date'];
        }
        
        $response = Http::get("{$this->baseUrl}/events", $queryParams);
        
        if ($response->successful()) {
            return $response->json()['data'] ?? [];
        }
        
        return [];
    }

    public function createEvent($eventData)
    {
        $eventData['organizer'] = 'faculty';
        $eventData['organizer_id'] = $this->facultyId;
        $eventData['is_active'] = true;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->post("{$this->baseUrl}/events", $eventData);
       
        return $response->json();
    }

    public function updateEvent($id, $eventData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->put("{$this->baseUrl}/events/{$id}", $eventData);
       
        // Tambah error handling
        if (!$response->successful()) {
            throw new \Exception('Failed to update event: ' . $response->body());
        }
       
        return $response->json();
    }
    
    public function deleteEvent($id)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->delete("{$this->baseUrl}/events/{$id}");
       
        // Tambah error handling
        if (!$response->successful()) {
            throw new \Exception('Failed to delete event: ' . $response->body());
        }
       
        return $response->json();
    }

    public function getEventDetails($id)
    {
        $response = Http::get("{$this->baseUrl}/events/{$id}");
        
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        
        return null;
    }

    public function registerEvent($eventId, $studentData)
    {
        $response = Http::post("{$this->baseUrl}/events/{$eventId}/register", $studentData);
        return $response->json();
    }
}