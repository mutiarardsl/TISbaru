<?php
namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventApiService
{
    protected $baseUrl;
    protected $timeout;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('EVENT_API_URL', 'http://169.254.64.59:8000/api');
        $this->timeout = 10;
        $this->apiKey = env('EVENT_API_KEY');
    }

    protected function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $headers = [];
           
            // Tambahkan API key jika tersedia
            if ($this->apiKey) {
                $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            }

            Log::info("Making API request", [
                'method' => $method,
                'url' => "{$this->baseUrl}/{$endpoint}",
                'data' => $data,
                'headers' => $headers
            ]);

            $response = Http::timeout($this->timeout)
                ->withHeaders($headers)
                ->{$method}("{$this->baseUrl}/{$endpoint}", $data);
           
            Log::info("API response received", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }
           
            Log::error("Event API Error: " . $response->status() . " - " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Event API Connection Error: " . $e->getMessage(), [
                'exception' => $e,
                'endpoint' => $endpoint,
                'method' => $method
            ]);
            return null;
        }
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
        
        $response = $this->makeRequest('get', 'events', $queryParams);
       
        if ($response && isset($response['data'])) {
            return $response['data'];
        }
       
        return [];
    }

    public function getUniversityEvents($filters = [])
    {
        try {
            // Build query parameters including organizer filter
            $queryParams = [
                'organizer' => 'university',
                'is_active' => true
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
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->timeout($this->timeout)
            ->get($this->baseUrl . '/events', $queryParams);

            if ($response->successful()) {
                $jsonData = $response->json();
                return $jsonData['data'] ?? [];
            }

            Log::error("Failed to fetch university events: " . $response->status());
            return [];
        } catch (\Exception $e) {
            Log::error("University events connection error: " . $e->getMessage());
            return [];
        }
    }

    public function createEvent($eventData)
    {
        $payload = array_merge($eventData, [
            'organizer' => 'university',
            'organizer_id' => 1,
            'is_active' => true
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->timeout($this->timeout)
            ->post($this->baseUrl . '/events', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Create event failed: " . $response->body());
            return ['error' => true, 'message' => $response->json()['message'] ?? 'Failed to create event'];

        } catch (\Exception $e) {
            Log::error("Create event error: " . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function updateEvent($id, $eventData)
    {
        $response = $this->makeRequest('put', "events/{$id}", $eventData);
        return $response;
    }

    public function deleteEvent($id)
    {
        $response = $this->makeRequest('delete', "events/{$id}");
        return $response;
    }

    public function getEventDetails($id)
    {
        $response = $this->makeRequest('get', "events/{$id}");
       
        if ($response && isset($response['data'])) {
            return $response['data'];
        }
       
        return null;
    }

    public function registerEvent($eventId, $registrationData)
    {
        $response = $this->makeRequest('post', "events/{$eventId}/register", $registrationData);
        return $response;
    }

    public function getCategories()
    {
        $response = $this->makeRequest('get', 'categories');
       
        if ($response && isset($response['data'])) {
            return $response['data'];
        }
       
        return [];
    }

    // Method untuk test koneksi
    public function testConnection()
    {
        try {
            Log::info("Testing API connection to: " . $this->baseUrl . '/events');
           
            $response = Http::timeout(5)->get($this->baseUrl . '/events');
           
            Log::info("Connection test result", [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);
           
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Connection test failed: " . $e->getMessage());
            return false;
        }
    }

    // Method tambahan untuk mengecek status API
    public function getApiStatus()
    {
        try {
            $response = Http::timeout(5)->get($this->baseUrl . '/test');
           
            if ($response->successful()) {
                return [
                    'status' => 'connected',
                    'message' => 'API is working properly'
                ];
            }
           
            return [
                'status' => 'error',
                'message' => 'API returned status: ' . $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection failed: ' . $e->getMessage()
            ];
        }
    }
}