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
                <div class="card-header">Users
                    <span class="float-right">
                        {{-- <a class="btn btn-primary" href="{{ route('developer.users.create') }}">New User</a> --}}
                        {{-- <a class="btn btn-primary" href="{{ route('users.roles') }}">User Role</a> --}}
                    </span>
                </div>
                <div class="card-body">


                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="250px">Action</th>
                            </tr>
                        </thead>
                        @php
                            $rhps = DB::table('role_has_permissions')->get();
                            $permissions = DB::table('permissions')->get();
                            $roles = DB::table('roles')->get();
                            $data = DB::table('users')
                                ->where('id', Auth::user()->id)
                                ->get();
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
                                                                    <a class="btn btn-success"
                                                                        href="{{ route('developer.users.show', $user->id) }}">Show</a>
                                                                @elseif ($value == 'create')
                                                                    <a class="btn btn-primary"
                                                                        href="{{ route('developer.users.create') }}">New
                                                                        user</a>
                                                                @elseif ($value == 'edit')
                                                                    <a class="btn btn-primary"
                                                                        href="{{ route('developer.users.edit', $user->id) }}">Edit</a>
                                                                @elseif ($value == 'active')
                                                                    @if ($user->status_id == 1)
                                                                        <a href="{{ route('developer.users.publish', $user->id) }}"
                                                                            class="btn btn-info "><i
                                                                                class="fa fa-arrow-circle-up"
                                                                                aria-hidden="true"></i></a>
                                                                    @else
                                                                        <a href="{{ route('developer.users.unpublish', $user->id) }}"
                                                                            class="btn btn-info btn-warning">
                                                                            <i class="fa fa-arrow-circle-down "
                                                                                aria-hidden="true"></i>
                                                                        </a>
                                                                    @endif
                                                                @elseif ($value == 'delete')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['developer.users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                                @else
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
