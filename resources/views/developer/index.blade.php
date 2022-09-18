@extends('layouts.deshboard')
@section('content')
    <H1>Welcome :{{ Auth::user()->name }}</H1>
    <div class="col-md-12">
        @php
            $projectCount = DB::table('task_metas')
                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                ->where('task_metas.user_id', Auth::user()->id) // if "where" do not work
                // select *, COUNT('task_id') from `task_metas` where user_id=1
                // MIN(),MAX(),COUNT() AVG() Error
                //                     So for solving this error there are 2 ways:
                //                     ->groupBy('tasks.id')
                //                     You can use GROUP BY on relevant column.
                //                     You can disable the strict mode in config/database.php by setting it to false.
                //                     'strict' => false, //it will be true by default set this to false
            
                // ->groupBy('tasks.id')
                ->get();
            $totaltask = DB::table('task_metas')
                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                ->where('task_metas.user_id', Auth::user()->id)
                ->get();
            
            $pandingCount = DB::table('task_metas')
                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                ->where('task_metas.user_id', Auth::user()->id)
                ->where('tasks.task_status', '1')
                ->get();
            
            $runningCount = DB::table('task_metas')
                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                ->where('task_metas.user_id', Auth::user()->id)
                ->where('tasks.task_status', '2')
                ->get();
            $doneCount = DB::table('task_metas')
                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                ->where('task_metas.user_id', Auth::user()->id)
                ->where('tasks.task_status', '3')
                ->get();
            
            $defvalue = $totaltask[0]->task_count - $doneCount[0]->task_count;
            
            // print_r($defvalue);
            // die();
            
        @endphp
        <div class="top_tiles">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                    <div class="count">{{ $projectCount[0]->task_count }}</div>
                    <h3>Total Project</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                    <div class="count">{{ $totaltask[0]->task_count }}</div>
                    <h3>Total Task</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                    <div class="count">{{ $defvalue }}</div>
                    <h3>Rest of Task</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-comments-o"></i></div>
                    <div class="count">{{ $pandingCount[0]->task_count }}</div>
                    <h3>Task Pandding</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                    <div class="count">{{ $runningCount[0]->task_count }}</div>
                    <h3>Task Running</h3>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-check-square-o"></i></div>
                    <div class="count">{{ $doneCount[0]->task_count }}</div>
                    <h3>Task Done</h3>
                </div>
            </div>

        </div>
    </div>
@endsection
