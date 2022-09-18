@extends('layouts.deshboard')
@section('content')
    <style>
        #myProgress {
            width: 100%;
            background-color: #ddd;
            overflow: hidden;
        }
    </style>
    <div class="container">
        <div class="justify-content-center">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            @if (\Session::has('worning'))
                <div class="alert alert-danger">
                    <p>{{ \Session::get('worning') }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">Task
                    @php
                        $role_id = Auth::user()->role_id;
                        $rhps = DB::table('role_has_permissions')
                            ->where('role_id', $role_id)
                            ->get();
                        $permissions = DB::table('permissions')->get();
                        foreach ($rhps as $rhpsvalue) {
                            $permissionId = $rhpsvalue->permission_id;
                            foreach ($permissions as $pvalue) {
                                $pid = $pvalue->id;
                                if ($permissionId == $pid) {
                                    // print_r($pvalue->name);
                                    if ($pvalue->name == 'tasks-create') {
                                        echo ' <span class="float-right">';
                                        echo '<a class="btn btn-primary btn-sm" href="tasks.create"><i class="fas fa-plus"></i> New task</a>';
                                        echo '</span>';
                                    }
                                }
                            }
                        }
                    @endphp
                    {{-- <a class="btn btn-primary btn-sm" href="tasks.create"><i class="fas fa-plus"></i> New task</a> --}}
                    <span class="float-right">
                        <button id="search_toggle" class="btn btn-info btn-sm"> <i class="fas fa-search"></i> Search
                        </button>
                    </span>
                </div>
                <div id="search_third" style=" display: none;">
                    <div class="jumbotron justify-content-center">
                        <div class="from-group">
                            <input type="text" name="seach" class="form-control" id="input_search_image_file"
                                placeholder="Search...">
                        </div>
                    </div>
                </div>




                <div class="card-body">
                    <div id="project_search_response"></div>
                    <div class="task-hidden">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Project Name</th>
                                    <th>Assign Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th width="150px" class="text-center">Action</th>
                                </tr>
                            </thead>@php
                                $rhps = DB::table('role_has_permissions')->get();
                                $permissions = DB::table('permissions')->get();
                                $roles = DB::table('roles')->get();
                                // $roles = DB::table('task')->get();
                            @endphp <tbody>

                                @foreach ($data as $key => $tvalue)
                                    <tr>
                                        <td>{{ $tvalue->id }}</td>
                                        <td>{{ $tvalue->task_title }}</td>
                                        <td>{{ $tvalue->task_description }}</td>
                                        <td>
                                            @php
                                                $projects = DB::table('projects')
                                                    ->where('id', $tvalue->project_id)
                                                    ->get();
                                                $projectName = $projects[0]->project_name;
                                            @endphp
                                            {{ $projectName }}
                                        </td>
                                        <td>{{ $tvalue->assign_by }}</td>
                                        <td>
                                            <p class="bg-primary text-white text-center d-block">
                                                {{ date('d-m-Y', strtotime($tvalue->start_date)) }}</p>
                                        </td>
                                        <td>
                                            <p class="bg-info text-white text-center d-block">
                                                {{ date('d-m-Y', strtotime($tvalue->end_date)) }}</p>
                                        </td>
                                        <td>
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
                                                    style='--number:{{ $tvalue->task_progress }};'>
                                                    {{ $tvalue->task_progress }} %</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($tvalue->task_status == 1)
                                                <p class="bg-danger text-white text-center d-block">Panding</p>
                                            @elseif($tvalue->task_status == 2)
                                                <p class="bg-warning text-white text-center d-block">Running</p>
                                            @elseif($tvalue->task_status == 3)
                                                <p class="bg-success text-white text-center d-block">Done</p>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @foreach ($rhps as $rhp)
                                                @foreach ($permissions as $permission)
                                                    @foreach ($roles as $role)
                                                        @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                                            @if ($rhp->permission_id == $permission->id)
                                                                @php
                                                                    $name = $permission->name;
                                                                @endphp
                                                                @if (stristr($name, 'task'))
                                                                    @php
                                                                        $value = substr(strstr($name, '-'), 1);
                                                                    @endphp
                                                                    @if ($value == 'list')
                                                                    @elseif ($value == 'show')
                                                                        <a class="btn btn-info btn-sm"
                                                                            href="{{ route('developer.tasks.show', $tvalue->id) }}"><i
                                                                                class="fas fa-eye"></i></a>
                                                                    @elseif ($value == 'edit')
                                                                        <a class="btn btn-primary btn-sm"
                                                                            href="{{ route('developer.tasks.edit', $tvalue->id) }}"><i
                                                                                class="fas fa-edit"></i></a>
                                                                    @elseif ($value == 'delete')
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['developer.tasks.destroy', $tvalue->id], 'style' => 'display:inline']) !!}
                                                                        {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn btn-danger btn-sm']) }}
                                                                        {!! Form::close() !!}
                                                                    @else
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                            {{-- <a class="btn btn-primary"
                                                href="{{ route('developer.tasks.edit', $tvalue->id) }}">Edit</a>
                                            @if (Auth::user()->name == 'developer')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['developer.tasks.destroy', $tvalue->id], 'style' => 'display:inline']) !!} {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} {!! Form::close() !!}
                                            @endif --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //search image
        $(document).on('input', '#input_search_image_file', function() {
            // use for previews table hidden
            document.getElementsByClassName('task-hidden')[0].style.visibility =
                'hidden';
            var search = $(this).val();
            // alert(search);

            var data = {
                "search": search
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('developer.tasks.search') }}",
                data: data,
                success: function(response) {
                    document.getElementById("project_search_response").innerHTML = response
                }
            });
        });
        const searchtargetDiv = document.getElementById("search_third");
        const searchbtn = document.getElementById("search_toggle");
        searchbtn.onclick = function() {
            if (searchtargetDiv.style.display == "block") {
                searchtargetDiv.style.display = "none";
            } else {
                searchtargetDiv.style.display = "block";

            }
        };
    </script>
@endsection
