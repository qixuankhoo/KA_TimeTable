<?php

namespace App\Services;

use App\Models\Lesson;

class CalendarService
{
    public function generateCalendarData($lessons)
    {
        $dailyStart = config('app.calendar.start_time');
        $dailyEnd = config('app.calendar.end_time');

       
        $startTimes = $lessons->pluck('start_time')->toArray();
        $durations = $lessons->pluck('duration')->toArray();
        
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange($dailyStart, $dailyEnd, $startTimes, $durations);
    
        foreach ($timeRange as $time) {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $lesson = $lessons->where('start_time', $time['start'])->first();

            if ($lesson) {
                $calendarData[$timeText] = [
                    'student_name' => $lesson->student->full_name,
                    'lesson_name' => ucfirst($lesson->description),
                    'grade' => $lesson->grade,
                    'rowspan'      => $lesson->difference/30 ?? ''
                ];
            } else {
                $calendarData[$timeText] = null;
            }
        }

        return $calendarData;
    }
}
