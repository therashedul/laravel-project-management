@extends('layouts.deshboard')
@section('content')
    <div class="container">
        <div class="justify-content-center">

            <div class="card">
                <div class="card-header">Create user
                    <span class="float-right">
                        <a class="btn btn-primary" href="{{ route('users.index') }}">Users</a>
                    </span>
                </div>

                <div class="card-body">
                    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PATCH']) !!}
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

                    <button type="submit" class="btn btn-primary">Update</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
