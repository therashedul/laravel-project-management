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
                <div class="card-header">Permissions
                    <span class="float-right">

                    </span>

                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        @php
                            $rhps = DB::table('role_has_permissions')->get();
                            $permissions = DB::table('permissions')->get();
                            $roles = DB::table('roles')->get();
                        @endphp
                        <tbody>
                            @foreach ($data as $key => $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        @foreach ($roles as $role)
                                            @foreach ($rhps as $rhp)
                                                @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                                    @if ($rhp->permission_id == $permission->id)
                                                        @php
                                                            $name = $permission->name;
                                                        @endphp
                                                        @php
                                                            $value = substr(strstr($name, '-'), 1);
                                                        @endphp
                                                        <a class="btn btn-success"
                                                            href="{{ route('developer.permissions.show', $permission->id) }}">Show</a>

                                                        {{-- <a class="btn btn-primary"
                                                            href="{{ route('developer.permissions.create') }}">New
                                                            permission</a> --}}

                                                        <a class="btn btn-primary"
                                                            href="{{ route('developer.permissions.edit', $permission->id) }}">Edit</a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                    {{-- <td>
                                        <a class="btn btn-success"
                                            href="{{ route('developer.permissions.show', $permission->id) }}">Show</a>

                                        <a class="btn btn-primary"
                                            href="{{ route('developer.permissions.edit', $permission->id) }}">Edit</a>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['developer.permissions.destroy', $permission->id], 'style' => 'display:inline']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}

                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
