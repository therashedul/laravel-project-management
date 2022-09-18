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
                    <div class="col-md-4">
                        @if (!empty($user->profile_image))
                            <img src="{{ asset('images/' . $user->profile_image) }}" width="100%" height="auto"
                                alt="" title="">
                        @else
                            <img src="{{ asset('images/profile/profile-image.png') }}" width="100px" height="80px"
                                alt="" title="">
                        @endif

                    </div>
                    <div class="col-md-8">
                        <div class="lead">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="lead">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="lead">
                            <strong>Password:</strong>
                            ********
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
