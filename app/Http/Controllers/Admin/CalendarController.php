<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CalendarService;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Filters\FiltersCalendarDay;
use App\Filters\FiltersCalendarTeacherId;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;



class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CalendarService $calendarService)
    {
        $teachers = Teacher::all()->sortBy('last_name');
        $days = Lesson::DAYS;

        $lessons = QueryBuilder::for(Lesson::class)
            ->allowedFilters([
                AllowedFilter::custom('day', new FiltersCalendarDay)->default(2),
                AllowedFilter::custom('teacher_id', new FiltersCalendarTeacherId)->default(1),
            ])
            ->with(['teacher', 'student'])
            ->defaultSort('start_time')
            ->get();
            
        $calendarData = $calendarService->generateCalendarData($lessons);
        
        return view('admin.calendar', compact(['calendarData', 'days','teachers']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
