@extends('layouts.deshboard')
@section('content')
    <H1>Welcome :{{ Auth::user()->name }}</H1>
    <div class="col-md-12">
        @php
            $projectCount = DB::table('projects')
                ->select('id')
                ->count();
            $totaltask = DB::table('tasks')
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
            
            $singlepandingCount = DB::table('task_metas')
                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                ->where('task_metas.user_id', Auth::user()->id) // if "where" do not work
                ->where('tasks.task_status', '3')
                ->get();
            
            $defvalue = $totaltask - $singlepandingCount[0]->task_count;
            
        @endphp
        <div class="col-md-9 col-sm-12 ">
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
                        <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
                        <div class="count">{{ $totaltask }}</div>
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
                        <div class="count">{{ $panddingCount }}</div>
                        <h3>Pandding Task</h3>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
                        <div class="count">{{ $runningCount }}</div>
                        <h3>Running Task</h3>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 ">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-check-square-o"></i></div>
                        <div class="count">{{ $doneCount }}</div>
                        <h3>Task Done</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12 ">
            <div>
                @php
                    $singlepandingCount = DB::table('task_metas')
                        ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                        ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                        ->where('task_metas.user_id', Auth::user()->id) // if "where" do not work
                        ->where('tasks.task_status', '1')
                        ->get();
                    
                    $singlerunningCount = DB::table('task_metas')
                        ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                        ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                        ->where('task_metas.user_id', Auth::user()->id) // if "where" do not work
                        ->where('tasks.task_status', '2')
                        ->get();
                    $singledoneCount = DB::table('task_metas')
                        ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                        ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                        ->where('task_metas.user_id', Auth::user()->id) // if "where" do not work
                        ->where('tasks.task_status', '3')
                        ->get();
                    
                    // print_r($projectCount[0]->task_count);
                    // die();
                    
                @endphp
                <div class="x_title">
                    <h2>User Projects</h2>
                    <ul class="nav navbar-right panel_toolbox">

                    </ul>
                    <div class="clearfix"></div>
                </div>
                <ul class="list-unstyled top_profiles scroll-view">
                    @php
                        $tasks = DB::table('tasks')->get();
                        $users = DB::table('users')->get();
                    @endphp
                    @foreach ($users as $user)
                        @if ($user->status_id == 1)
                            @php
                                $singlepandingCount = DB::table('task_metas')
                                    ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                                    ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                                    ->where('task_metas.user_id', $user->id) // if "where" do not work
                                    ->where('tasks.task_status', '1')
                                    ->get();
                                $singlerunningCount = DB::table('task_metas')
                                    ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                                    ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                                    ->where('task_metas.user_id', $user->id) // if "where" do not work
                                    ->where('tasks.task_status', '2')
                                    ->get();
                                $singledoneCount = DB::table('task_metas')
                                    ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                                    ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                                    ->where('task_metas.user_id', $user->id) // if "where" do not work
                                    ->where('tasks.task_status', '3')
                                    ->get();
                            @endphp
                            <li class="media event">
                                <a class="pull-left border-aero profile_thumb">
                                    <i class="fa fa-user aero"></i>
                                </a>
                                <div class="media-body">
                                    <a class="title" href="{{ route('admin.tasts.profile', $user->name) }}"
                                        style="text-transform: uppercase">{{ $user->name }}</a>
                                    <p>Done : <strong>{{ $singledoneCount[0]->task_count }} </strong></p>
                                    <p>Running: <strong>{{ $singlerunningCount[0]->task_count }} </strong></p>
                                    <p>Panding: <strong>{{ $singlepandingCount[0]->task_count }} </strong></p>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
