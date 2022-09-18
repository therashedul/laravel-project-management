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
                                    if ($pvalue->name == 'permission-create') {
                                        echo ' <span class="float-right">';
                                        echo '<a class="btn btn-primary btn-sm" href="permissions.create"><i class="fas fa-plus"></i> New Permission</a>';
                                        echo '</span>';
                                    }
                                }
                            }
                        }
                    @endphp
                    <span class="float-right">
                        <button id="search_toggle" class="btn btn-info btn-sm"> <i class="fas fa-search"></i> Search
                        </button>
                    </span>
                </div>
                <div id="search_third" style=" display: none;">
                    <div class="jumbotron justify-content-center">
                        <div class="from-group">
                            <input type="text" name="seach" class="form-control" id="input_search_image_file"
                                placeholder="Search...">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover permission-hidden">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th width="200px">Action</th>
                            </tr>
                        </thead>
                        @php
                            $rhps = DB::table('role_has_permissions')->get();
                            // $permissions = DB::table('permissions')->get();
                            $roles = DB::table('roles')->get();
                        @endphp
                        <tbody>
                            <div id="project_search_response"></div>
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
                                                        @if (stristr($name, 'user') || stristr($name, 'role') || stristr($name, 'permission') || stristr($name, 'project') || stristr($name, 'document') || stristr($name, 'menu') || stristr($name, 'media'))
                                                            @php
                                                                $value = substr(strstr($name, '-'), 1);
                                                                // echo $value;
                                                            @endphp
                                                            @if ($value == 'list' || $value == 'create' || $value == 'edit' || $value == 'delete' || $value == 'active')
                                                                <a class="btn btn-success btn-sm"
                                                                    href="{{ route('executive.permissions.show', $permission->id) }}"><i
                                                                        class="fas fa-eye"></i></a>
                                                                <a class="btn btn-primary btn-sm"
                                                                    href="{{ route('executive.permissions.edit', $permission->id) }}"><i
                                                                        class="fas fa-edit"></i></a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['executive.permissions.destroy', $permission->id], 'style' => 'display:inline-table']) !!}
                                                                {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn btn-danger btn-sm']) }}
                                                                {!! Form::close() !!}
                                                            @else
                                                                @if (Auth::user()->role_id == '1')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['executive.permissions.destroy', $permission->id], 'style' => 'display:inline']) !!}
                                                                    {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn btn-danger btn-sm']) }}
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //search image
        $(document).on('input', '#input_search_image_file', function() {
            // use for previews table hidden
            document.getElementsByClassName('permission-hidden')[0].style.visibility =
                'hidden';
            var search = $(this).val();

            var data = {
                "search": search
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{ route('executive.permissons.search') }}",
                data: data,
                success: function(response) {
                    document.getElementById("project_search_response").innerHTML = response
                }
            });
        });
        const searchtargetDiv = document.getElementById("search_third");
        const searchbtn = document.getElementById("search_toggle");
        searchbtn.onclick = function() {
            if (searchtargetDiv.style.display == "block") {
                searchtargetDiv.style.display = "none";
            } else {
                searchtargetDiv.style.display = "block";

            }
        };
    </script>
@endsection
