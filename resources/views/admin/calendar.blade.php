@extends('layouts.admin')
@section('content')

    @php
        $selectedDayId = request('filter.day');
        $selectedTeacherId = request('filter.teacher_id');
    @endphp
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ request()->fullUrl() }}">
                    @csrf
                    <input type="submit" class="d-none">
                    <div class="card-header d-flex flex-row">
                        <div class="mr-auto p-2 font-weight-bold font">Teacher TimeTable</div>
                        <div class="p-2">
                            <select class="btn btn-primary dropdown-toggle" name="filter[day]" onchange="this.form.submit()">
                                @foreach($days as $id => $day)
                                    <option class="dropdown-item"value="{{ $id }}"
                                            @if($id == $selectedDayId) selected @endif>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-2">
                            <select class="btn btn-primary" name="filter[teacher_id]" onchange="this.form.submit()">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                            @if($selectedTeacherId == $teacher->id) selected @endif>
                                        {{ $teacher->preferred_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @can('lesson_create')
                            <div class="p-2">
                                <a class="btn btn-success" href="{{ route("admin.lessons.create") }}">
                                    {{ trans('global.add') }} {{ trans('cruds.lesson.title_singular') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body table-responsive">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th class="text-center">Time</th>
                                <th class="text-center">Lesson Description</th>
                                <th class="text-center">Student</th>
                            </thead>
                            <tbody>
                                @foreach($calendarData as $time => $lesson)
                                    <tr>
                                        <td class="text-center" @if(is_null($lesson)) style="background-color:#98FB98" @endif>
                                            {{ $time }}
                                        </td>
                                        @if(is_array($lesson))
                                            <td class="align-middle text-center">
                                                {{ strtoupper($lesson['lesson_name']) }} | Grade: {{ $lesson['grade'] }}
                                            </td>
                                            <td class="text-center">
                                                {{ $lesson['student_name'] }}
                                            </td>
                                        @elseif(is_null($lesson))
                                            <td></td>
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection
