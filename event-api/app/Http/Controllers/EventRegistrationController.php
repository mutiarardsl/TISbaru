<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tambahkan import Carbon

class EventRegistrationController extends Controller
{
    public function register(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $this->validate($request, [
            'student_id' => 'required|string',
            'student_name' => 'required|string',
            'student_email' => 'required|email',
        ]);

        $registration = EventRegistration::create([
            'event_id' => $eventId,
            'student_id' => $request->student_id,
            'student_name' => $request->student_name,
            'student_email' => $request->student_email,
            'registration_date' => Carbon::now(),
            'status' => 'pending',
        ]);

        return response()->json([
            'data' => $registration,
            'message' => 'Registration successful'
        ], 201);
    }
}