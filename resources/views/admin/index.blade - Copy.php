@extends('layouts.deshboard')
@section('content')
    <H1>Welcome :{{ Auth::user()->name }}</H1>
    <div class="col-md-12">
        @php
            $projectCount = DB::table('projects')
                ->select('id')
                ->count();
            $panddingCount = DB::table('tasks')
                ->select('id')
                ->where('task_status', '1')
                ->count();
            $runningCount = DB::table('tasks')
                ->select('id')
                ->where('task_status', '2')
                ->count();
            $doneCount = DB::table('tasks')
                ->select('id')
                ->where('task_status', '3')
                ->count();
            
            // print_r($projectCount);
            
        @endphp
        <div class="top_tiles">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                    <div class="count">{{ $projectCount }}</div>
                    <h3>Total Project</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-comments-o"></i></div>
                    <div class="count">{{ $panddingCount }}</div>
                    <h3>Total Pandding</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                    <div class="count">{{ $runningCount }}</div>
                    <h3>Total Running</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-check-square-o"></i></div>
                    <div class="count">{{ $doneCount }}</div>
                    <h3>Total Done</h3>
                </div>
            </div>

        </div>
    </div>
@endsection
