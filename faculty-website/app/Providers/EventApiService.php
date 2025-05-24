<?php
namespace App\Providers;

use Illuminate\Support\Facades\Http;

class EventApiService
{
protected $baseUrl;
protected $facultyId;

public function __construct()
{
$this->baseUrl = env('EVENT_API_URL', 'http://localhost:8000/api');
$this->facultyId = env('FACULTY_ID', 1);
}

public function getAllEvents()
{
$response = Http::get("{$this->baseUrl}/events");
return $response->json()['data'];
}

public function getFacultyEvents()
{
$response = Http::get("{$this->baseUrl}/events", [
'organizer' => 'faculty',
'organizer_id' => $this->facultyId
]);
return $response->json()['data'];
}

public function getUniversityEvents()
{
$response = Http::get("{$this->baseUrl}/events", [
'organizer' => 'university'
]);
return $response->json()['data'];
}

public function createEvent($eventData)
{
$eventData['organizer'] = 'faculty';
$eventData['organizer_id'] = $this->facultyId;

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
return $response->json()['data'];
}

public function registerEvent($eventId, $studentData)
{
$response = Http::post("{$this->baseUrl}/events/{$eventId}/register", $studentData);
return $response->json();
}
}