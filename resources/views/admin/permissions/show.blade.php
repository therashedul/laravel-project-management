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
                <div class="card-header">Permission
                    <span class="float-right">
                        <a class="btn btn-primary" href="{{ route('admin.permissions') }}">Back</a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="lead">
                        <strong>Name:</strong>

                        {{ $permissions->name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
