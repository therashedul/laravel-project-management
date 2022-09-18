@extends('layouts.deshboard')
@section('content')
    <div class="container">
        <div class="justify-content-center">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">Edit role
                    <span class="float-right">
                        <a class="btn btn-primary" href="{{ route('roles.index') }}">Roles</a>
                    </span>
                </div>
                <div class="card-body">

                    {!! Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'PATCH']) !!}
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br />
                        <div class="col-md-3">
                            <!-- Tabs nav -->
                            <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link mb-3 p-3 shadow active" id="v-pills-user-tab" data-toggle="pill"
                                    href="#v-pills-user" role="tab" aria-controls="v-pills-user" aria-selected="true">
                                    <span class="font-weight-bold small text-uppercase">User</span></a>

                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill"
                                    href="#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                                    aria-selected="false">
                                    <i class="fa fa-calendar-minus-o mr-2"></i>
                                    <span class="font-weight-bold small text-uppercase">Bookings</span></a>

                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill"
                                    href="#v-pills-messages" role="tab" aria-controls="v-pills-messages"
                                    aria-selected="false">
                                    <i class="fa fa-star mr-2"></i>
                                    <span class="font-weight-bold small text-uppercase">Reviews</span></a>

                                <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"
                                    href="#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                                    aria-selected="false">
                                    <i class="fa fa-check mr-2"></i>
                                    <span class="font-weight-bold small text-uppercase">Confirm booking</span></a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <!-- Tabs content -->
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade shadow rounded bg-white show active p-5" id="v-pills-user"
                                    role="tabpanel" aria-labelledby="v-pills-user-tab">

                                    <h4 class="font-italic mb-4">Personal information</h4>
                                    <p class="font-italic text-muted mb-2">Lorem</p>



                                    @foreach ($permission as $value)
                                        @php
                                            $name = $value->name;
                                            $role = stristr($name, 'role');
                                            $user = stristr($name, 'user');
                                        @endphp


                                        @if ($value->name == $user)
                                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name']) }}

                                                {{ $value->name }}</label>

                                        @endif

                                        <br />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
