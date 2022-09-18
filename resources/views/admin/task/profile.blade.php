@extends('layouts.deshboard')
@section('content')
    <div class="col-md-12 col-sm-12 ">

        <div class="x_title">
            <h2 style="text-transform: uppercase;">{{ $user[0]->name }} Projects</h2>
            <ul class="nav navbar-right panel_toolbox">

            </ul>
            <div class="clearfix"></div>
        </div>
        <ul class="list-unstyled top_profiles scroll-view">
            @php
                $tasks = DB::table('tasks')
                    ->orderBy('id', 'DESC')
                    ->get();
            @endphp
            @foreach ($user as $use)
                @foreach ($tasks as $task)
                    @php
                        $values = explode(',', $task->assign_by);
                    @endphp
                    @foreach ($values as $value)
                        @if ($use->name == $value)
                            <li class="media event">
                                <a class="pull-left border-aero profile_thumb">
                                    <i class="fa fa-user aero"></i>
                                </a>
                                <div class="media-body">
                                    <p>Project Name : <strong>{{ $task->task_title }} </strong></p>
                                    <style>
                                        :root {
                                            --bg: #04AA6D;
                                            --padding: 1;
                                        }

                                        #myBar.progress-line {
                                            width: calc(var(--number)*1%);
                                            height: 30px;
                                            background-color: var(--bg);
                                            text-align: center;
                                            line-height: 30px;
                                            color: white;
                                        }
                                    </style>
                                    <div id="myProgress">
                                        <div id="myBar" class="progress-line"
                                            style='--number:{{ $task->task_progress }};'>
                                            {{ $task->task_progress }} %</div>
                                    </div>
                                    {{ $task->task_status == 1 ? 'Panding' : '' }}
                                    {{ $task->task_status == 2 ? 'Running' : '' }}
                                    {{ $task->task_status == 3 ? 'Done !' : '' }}

                                </div>
                            </li>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
        </ul>
    </div>
@endsection
