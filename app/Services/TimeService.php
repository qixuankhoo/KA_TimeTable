<?php

namespace App\Services;

use Carbon\Carbon;


class TimeService
{
    public function generateTimeRange($from, $to, $startTimes, $durations)
    {
        $time = Carbon::parse($from);
        $dailyEnd = Carbon::parse($to);
        $timeRange = [];
        $index = 0;
    
        while ($time->format("H:i") < $to) {
            if ($index < count($startTimes)) {
                $startTime = Carbon::parse($startTimes[$index]);
                $duration = $durations[$index];
            }

            if (isset($startTime) && $startTime->format("H:i") <= $time->format("H:i")) {
                array_push($timeRange, [
                    'start' => $startTime->format("H:i"),
                    'end' => $startTime->addMinutes($duration)->format("H:i")
                ]);
                $time = $startTime;
                $index++;
                $startTime = null;
                continue;
            } 

            $timeDiff = 30; //If no class at all? 

            if ($index < count($startTimes)) {
                if ($index < count($startTimes) && Carbon::parse($startTimes[$index])->diffInMinutes($time) > 60) {
                    $timeDiff = $this->nearestHourHelper($time);
                } else {
                    $timeDiff = $startTime->diffInMinutes($time);
                }
            } 

            if ($index >= count($startTimes)) {
                $timeDiff = $this->nearestHourHelper($time);
            }
            
            if ($dailyEnd->diffInMinutes($time) <= 30) {
                $timeDiff = $dailyEnd->diffInMinutes($time);
            }

            array_push($timeRange, [
                'start' => $time->format("H:i"),
                'end' => $time->addMinutes($timeDiff)->format("H:i")
            ]);  
        }

        return $timeRange;
    }

    public function nearestHourHelper(Carbon $time)
    {
        $timeCopy = clone $time;
        $timeCopy->addHour(1);
        $timeCopy = Carbon::parse($timeCopy->format("H:00")); 
        $diff = $timeCopy->diffInMinutes($time);
        return $diff;
    }
}

