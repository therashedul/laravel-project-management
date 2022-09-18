@extends('layouts.deshboard')
@section('content')
    <div class="container">
        <div class="justify-content-center">

            <div class="card">
                <div class="card-header">Create user
                    <span class="float-right">
                        <a class="btn btn-primary" href="{{ route('developer.users') }}">Users</a>
                    </span>
                </div>
                {{-- {{ $user }} --}}
                <div class="card-body">
                    {!! Form::model($user, ['route' => ['developer.users.update', $user->id], 'method' => 'PATCH']) !!}
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <strong>Email:</strong>
                        {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <strong>Password:</strong>
                        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        {!! Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <strong>Status:</strong>
                        <input type="hidden" value="0" class="js-switch" name="status_id"
                            {{ $user->status_id == 0 ? 'checked' : '' }}>
                        <input type="checkbox" value="1" class="js-switch" name="status_id"
                            {{ $user->status_id == 1 ? 'checked' : '' }}>
                    </div>
                    <div class="form-group">
                        <strong>User:</strong>
                        @php
                            $userRoles = DB::table('roles')
                                ->where('name', Auth::user()->name)
                                ->get();
                        @endphp

                        <select class="form-control" name="role_id">
                            @foreach ($userRoles as $role)
                                <option {{ $user->role_id == $role->id ? 'selected=""' : '' }}
                                    value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>

                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
