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
            
            // print_r($projectCount);
            
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
                    <h2>Top Profiles</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Settings 1</a>
                                <a class="dropdown-item" href="#">Settings 2</a>
                            </div>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <ul class="list-unstyled top_profiles scroll-view">
                    @php
                        $tasks = DB::table('tasks')->get();
                        $users = DB::table('users')->get();
                    @endphp
                    @foreach ($users as $user)
                        @php
                            $singlepandingCount = DB::table('task_metas')
                                ->join('tasks', 'tasks.id', '=', 'task_metas.task_id')
                                ->selectRaw('tasks.id as tid,  count(task_metas.task_id) as task_count')
                                ->where('task_metas.user_id', $user->id) // if "where" do not work
                                ->where('tasks.task_status', '3')
                                ->get();
                        @endphp

                        {{ $singlepandingCount[0]->task_count }}
                        <li class="media event">
                            <a class="pull-left border-aero profile_thumb">
                                <i class="fa fa-user aero"></i>
                            </a>
                            <div class="media-body">
                                @foreach ($tasks as $task)
                                    @php
                                        $values = explode(',', $task->assign_by);
                                    @endphp
                                    @foreach ($values as $value)
                                        @if ($user->name == $value)
                                            {{-- @if ($task->task_progress != '100') --}}
                                            <a class="title" href="#"></a>
                                            {{-- {{ $task->task_status == 3 ? 'done' : ($task->task_status == 2 ? 'running' : ($task->task_status == 1 ? 'panding' : 'nothing')) }} --}}
                                            </a>
                                            {{-- <p><strong>{{ $task->task_title }} </strong></p>
                                            <p> <small>{{ $task->task_progress }}</small></p> --}}
                                            {{-- @endif --}}
                                        @endif
                                    @endforeach
                                @endforeach
                                {{-- Running:
                                {{ $singlerunningCount[0]->task_count }} Panding:
                                {{ $singledoneCount[0]->task_count }} --}}
                            </div>
                        </li>
                    @endforeach
                    {{-- <li class="media event">
                        <a class="pull-left border-green profile_thumb">
                            <i class="fa fa-user green"></i>
                        </a>
                        <div class="media-body">
                            <a class="title" href="#">Ms. Mary Jane</a>
                            <p><strong>$2300. </strong> Agent Avarage Sales </p>
                            <p> <small>12 Sales Today</small>
                            </p>
                        </div>
                    </li>
                    <li class="media event">
                        <a class="pull-left border-blue profile_thumb">
                            <i class="fa fa-user blue"></i>
                        </a>
                        <div class="media-body">
                            <a class="title" href="#">Ms. Mary Jane</a>
                            <p><strong>$2300. </strong> Agent Avarage Sales </p>
                            <p> <small>12 Sales Today</small>
                            </p>
                        </div>
                    </li>
                    <li class="media event">
                        <a class="pull-left border-aero profile_thumb">
                            <i class="fa fa-user aero"></i>
                        </a>
                        <div class="media-body">
                            <a class="title" href="#">Ms. Mary Jane</a>
                            <p><strong>$2300. </strong> Agent Avarage Sales </p>
                            <p> <small>12 Sales Today</small>
                            </p>
                        </div>
                    </li>
                    <li class="media event">
                        <a class="pull-left border-green profile_thumb">
                            <i class="fa fa-user green"></i>
                        </a>
                        <div class="media-body">
                            <a class="title" href="#">Ms. Mary Jane</a>
                            <p><strong>$2300. </strong> Agent Avarage Sales </p>
                            <p> <small>12 Sales Today</small>
                            </p>
                        </div>
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
@endsection
