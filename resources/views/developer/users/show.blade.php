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
                    <span class="float-right">
                        <a class="btn btn-primary" href="{{ route('admin.users') }}">Back</a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="lead">
                        <strong>Name:</strong>
                        {{ $users->name }}
                    </div>
                    <div class="lead">
                        <strong>Email:</strong>
                        {{ $users->email }}
                    </div>
                    <div class="lead">
                        <strong>Password:</strong>
                        ********
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
