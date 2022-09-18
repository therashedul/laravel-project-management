@extends('layouts.deshboard')
@section('content')
    <div class="container">
        <div class="justify-content-center">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">User
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
                                    if ($pvalue->name == 'user-create') {
                                        echo ' <span class="float-right">';
                                        echo '<a class="btn btn-primary btn-sm" href="users.create"><i class="fas fa-plus"></i> New User</a>';
                                        echo '</span>';
                                    }
                                }
                            }
                        }
                    @endphp
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="200px" valign="center">Action</th>
                            </tr>
                        </thead>
                        @php
                            $rhps = DB::table('role_has_permissions')->get();
                            $permissions = DB::table('permissions')->get();
                            $roles = DB::table('roles')->get();
                        @endphp
                        <tbody>
                            @foreach ($data as $key => $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($roles as $role)
                                            @if ($role->id == $user->role_id)
                                                {{ $role->name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{-- <a class="btn btn-success" href="{{ route('users.show', $user->id) }}">Show</a>
                                        <a class="btn btn-primary" href="{{ route('users.edit', $user->id) }}">Edit</a> --}}

                                        @foreach ($roles as $role)
                                            @foreach ($rhps as $rhp)
                                                @foreach ($permissions as $permission)
                                                    @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                                        @if ($rhp->permission_id == $permission->id)
                                                            @php
                                                                $name = $permission->name;
                                                            @endphp
                                                            @if (stristr($name, 'user'))
                                                                @php
                                                                    $value = substr(strstr($name, '-'), 1);
                                                                    // echo $value;
                                                                @endphp
                                                                @if ($value == 'list')
                                                                    <a class="btn btn-success btn-sm"
                                                                        href="{{ route('executive.users.show', $user->id) }}"><i
                                                                            class="fas fa-eye"></i></a>
                                                                @elseif ($value == 'create')
                                                                    {{-- <a class="btn btn-primary"
                                                                    href="{{ route('executive.users.create') }}">New
                                                                    user</a> --}}
                                                                @elseif ($value == 'active')
                                                                    @if ($user->status_id == 1)
                                                                        <a href="{{ route('executive.users.publish', $user->id) }}"
                                                                            class="btn btn-info btn-sm"><i
                                                                                class="fa fa-arrow-circle-up"
                                                                                aria-hidden="true"></i></a>
                                                                    @else
                                                                        <a href="{{ route('executive.users.unpublish', $user->id) }}"
                                                                            class="btn btn-info btn-warning btn-sm">
                                                                            <i class="fa fa-arrow-circle-down "
                                                                                aria-hidden="true"></i></a>
                                                                    @endif
                                                                @elseif ($value == 'edit')
                                                                    <a class="btn btn-primary btn-sm"
                                                                        href="{{ route('executive.users.edit', $user->id) }}"><i
                                                                            class="fas fa-edit"></i></a>
                                                                @elseif ($value == 'delete')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['executive.users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                                                    {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn btn-danger btn-sm']) }}
                                                                    {!! Form::close() !!}
                                                                    {{-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} --}}
                                                                @else
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach

                                        {{-- <a class="btn btn-success"
                                            href="{{ route('executive.users.show', $user->id) }}">Show</a>
                                        <a class="btn btn-primary"
                                            href="{{ route('executive.users.edit', $user->id) }}">Edit</a>


                                        {!! Form::open(['method' => 'DELETE', 'route' => ['executive.users.destroy', $user->id], 'style' => 'display:inline']) !!}

                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!} --}}


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->render() }}
                </div>
            </div>
        </div>
    </div>
@endsection
