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

                <div class="card-body">
                    {!! Form::open(['route' => 'developer.users.store', 'method' => 'POST']) !!}
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
                        <strong>User:</strong>
                        {!! Form::select('role_id', ['1' => 'developer', '2' => 'Executive', '3' => 'Developer'], '3', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::hidden('status_id', 0, ['class' => 'form-control']) !!}
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
