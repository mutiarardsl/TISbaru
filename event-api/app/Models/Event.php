<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
protected $fillable = [
'title', 'description', 'location', 'start_datetime', 'end_datetime',
'quota', 'registration_deadline', 'category_id', 'organizer', 'organizer_id', 'is_active'
];

public function category()
{
return $this->belongsTo(EventCategory::class);
}

public function registrations()
{
return $this->hasMany(EventRegistration::class);
}
}