@extends('layouts.deshboard')
@section('content')
    <div class="container">
        <div class="justify-content-center">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
                @if (\Session::has('worning'))
                    <div class="alert alert-danger">
                        <p>{{ \Session::get('worning') }}</p>
                    </div>
                @endif
            @endif
            <div class="card">
                <div class="card-header">Porject
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
                                    if ($pvalue->name == 'project-create') {
                                        echo ' <span class="float-right">';
                                        echo '<a class="btn btn-primary" href="projects.create">New Project</a>';
                                        echo '</span>';
                                    }
                                }
                            }
                        }
                    @endphp
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Logo</th>
                                <th>Create Date</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        @php
                            $rhps = DB::table('role_has_permissions')->get();
                            $permissions = DB::table('permissions')->get();
                            $roles = DB::table('roles')->get();
                            $user = Auth::user()->name;
                            
                        @endphp
                        <tbody>
                            @foreach ($data as $key => $project)
                                <tr>
                                    <td class="align-middle">{{ $project->id }}</td>
                                    <td class="align-middle">{{ $project->project_name }}</td>
                                    <td class="align-middle"><img src="{{ asset('thumbnail/' . $project->project_logo) }}"
                                            width="100px" height="80px" alt="" title=""></td>
                                    <td class="align-middle">{{ date('d-m-Y', strtotime($project->created_at)) }}</td>
                                    <td class="align-middle">
                                        @foreach ($rhps as $rhp)
                                            @foreach ($permissions as $permission)
                                                @foreach ($roles as $role)
                                                    @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                                        @if ($rhp->permission_id == $permission->id)
                                                            @php
                                                                $name = $permission->name;
                                                            @endphp
                                                            @if (stristr($name, 'project'))
                                                                @php
                                                                    $value = substr(strstr($name, '-'), 1);
                                                                    // echo $value;
                                                                @endphp
                                                                @if ($value == 'list')
                                                                    {{-- <a class="btn btn-success"
                                                                    href="{{ route('developer.projects.show', $project->id) }}">Show</a> --}}
                                                                @elseif ($value == 'create')
                                                                    {{-- <a class="btn btn-primary"
                                                                        href="{{ route('developer.projects.create') }}">New
                                                                        project</a> --}}
                                                                @elseif ($value == 'edit')
                                                                    <a class="btn btn-primary"
                                                                        href="{{ route('developer.projects.edit', $project->id) }}">Edit</a>
                                                                @elseif ($value == 'delete')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['developer.projects.destroy', $project->id], 'style' => 'display:inline-table']) !!}
                                                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                                    {!! Form::close() !!}
                                                                @else
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </td>
                                    {{-- <td>
                                        <a class="btn btn-primary"
                                            href="{{ route('developer.projects.edit', $project->id) }}">Edit</a>
                                        @if (Auth::user()->name == 'developer')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['developer.projects.destroy', $project->id], 'style' => 'display:inline']) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td> --}}


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $data->appends($_GET)->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
