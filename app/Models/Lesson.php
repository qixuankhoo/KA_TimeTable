<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;

    public $table = 'lessons';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'description',
        'day',
        'grade',
        'start_time',
        'end_time',
        'student_id',
        'teacher_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const DAYS = [
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday',
    ];

    public function student() 
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getDifferenceAttribute()
    {
        return Carbon::parse($this->end_time)->diffInMinutes($this->start_time);
    }

    public function getStartTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('H:i:s', $value)->format(config('panel.lesson_time_format')) : null;
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value ? Carbon::createFromFormat(config('panel.lesson_time_format'),
            $value)->format('H:i:s') : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('H:i:s', $value)->format(config('panel.lesson_time_format')) : null;
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value ? Carbon::createFromFormat(config('panel.lesson_time_format'),
            $value)->format('H:i:s') : null;
    }

    public static function isTimeAvailable($day, $startTime, $endTime, $teacher, $student, $lesson)
    {
        $lessons = self::where('day', $day)
            ->when($lesson, function ($query) use ($lesson) {
                $query->where('id', '!=', $lesson);
            })
            ->where(function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher);
            })
            ->where(function ($query) use ($student) {
                $query->where('student_id', $student);
            })
            ->where([
                ['start_time', '<', $endTime],
                ['end_time', '>', $startTime],
            ])
            ->count();

        return !$lessons;
    }

    public function scopeCalendarByRole($query)
    {
        return $query->when(!request()->input('class_id'), function ($query) {
                        $query->when(auth()->user()->is_teacher, function ($query) {
                            $query->where('teacher_id', auth()->user()->id);
                        })
                        ->when(auth()->user()->is_student, function ($query) {
                            $query->where('class_id', auth()->user()->class_id ?? '0');
                        });
                })
                ->when(request()->input('class_id'), function ($query) {
                    $query->where('class_id', request()->input('class_id'));
                });
    }
}
