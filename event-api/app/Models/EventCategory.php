<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
protected $fillable = [
'name'
];

// public function event()
// {
// return $this->belongsTo(Event);
// }

public function registrations()
{
return $this->hasMany(EventRegistration::class);
}
}