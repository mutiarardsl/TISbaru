<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
protected $fillable = [
'event_id','student_id','student_name','student_email','registration_date','status'
];

// public function event()
// {
// return $this->belongsTo(Event);
// }

public function category()
{
return $this->belongsTo(EventCategory::class);
}
}