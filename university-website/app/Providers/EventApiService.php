<?php
namespace App\Providers;

use Illuminate\Support\Facades\Http;

class EventApiService{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('EVENT_API_URL', 'http://localhost:8000/api');
    }

    public function getAllEvents()
    {
        $response = Http::get("{$this->baseUrl}/events");
        $jsonData = $response->json();
        
        // Periksa apakah respons berhasil dan memiliki data
        if ($response->successful() && isset($jsonData['data'])) {
            return $jsonData['data'];
        }
        
        // Jika tidak ada data atau respons gagal, kembalikan array kosong
        return [];
    }

    public function getUniversityEvents()
    {
        $response = Http::get("{$this->baseUrl}/events", [
            'organizer' => 'university'
        ]);
        $jsonData = $response->json();
        
        // Periksa apakah respons berhasil dan memiliki data
        if ($response->successful() && isset($jsonData['data'])) {
            return $jsonData['data'];
        }
        
        return [];
    }

    public function createEvent($eventData)
    {
        $eventData['organizer'] = 'university';
        $eventData['organizer_id'] = 1; // ID universitas

        $response = Http::post("{$this->baseUrl}/events", $eventData);
        return $response->json();
    }

    public function updateEvent($id, $eventData)
    {
        $response = Http::put("{$this->baseUrl}/events/{$id}", $eventData);
        return $response->json();
    }

    public function deleteEvent($id)
    {
        $response = Http::delete("{$this->baseUrl}/events/{$id}");
        return $response->json();
    }

    public function getEventDetails($id)
    {
        $response = Http::get("{$this->baseUrl}/events/{$id}");
        $jsonData = $response->json();
        
        // Periksa apakah respons berhasil dan memiliki data
        if ($response->successful() && isset($jsonData['data'])) {
            return $jsonData['data'];
        }
        
        return null;
    }
}