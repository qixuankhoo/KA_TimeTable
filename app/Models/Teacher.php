<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function groupLessons()
    {
        return $this->morphMany(GroupLesson::class, 'groupable', 'groupable_type', 'groupable_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_teacher');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getPreferredNameAttribute()
    {
        $preferredName = $this->known_as ?? $this->last_name;

        return $this->honorific . ' ' . $preferredName;
    }
}
