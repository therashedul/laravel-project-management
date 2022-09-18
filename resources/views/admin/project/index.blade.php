@extends('layouts.deshboard')
@section('content')
    <div class="container">
        <div class="justify-content-center">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
            @if (\Session::has('worning'))
                <div class="alert alert-danger">
                    <p>{{ \Session::get('worning') }}</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">Porject
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
                                    if ($pvalue->name == 'project-create') {
                                        echo ' <span class="float-right">';
                                        echo '<a class="btn btn-primary btn-sm" href="projects.create"><i class="fas fa-plus"></i> New Project</a>';
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
                <div class="card-body" id="index_field">
                    <table class="table table-hover project-hidden">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Logo</th>
                                <th>Create Date</th>
                                <th width="200px" class="text-center">Action</th>
                            </tr>
                        </thead>
                        @php
                            $rhps = DB::table('role_has_permissions')->get();
                            $permissions = DB::table('permissions')->get();
                            $roles = DB::table('roles')->get();
                        @endphp
                        <tbody>
                            <div id="project_search_response"></div>
                            @foreach ($data as $key => $project)
                                <tr>
                                    <td class="align-middle">{{ $project->id }}</td>
                                    <td class="align-middle">{{ $project->project_name }}</td>
                                    <td class="align-middle">

                                        @if (!empty($project->project_logo))
                                            <div id="post_select_image_container" class="post-select-image-container">
                                                <img src="{{ asset('images/' . $project->project_logo) }}"
                                                    id="selected_image_file" alt="" width="100px" height="80px">
                                            </div>
                                        @else
                                            <div id="post_select_image_container" class="post-select-image-container">
                                                <img src="{{ asset('images/profile/cemera.jpg') }}" width="100px"
                                                    height="80px" alt="" title="">
                                            </div>
                                        @endif

                                        {{-- <img src="{{ asset('thumbnail/' . $project->project_logo) }}" width="100px"
                                            height="80px" alt="" title=""> --}}

                                    </td>
                                    <td class="align-middle">
                                        {{ date('d-m-Y', strtotime($project->created_at)) }}</td>
                                    <td class="text-center align-middle">
                                        @foreach ($rhps as $rhp)
                                            @foreach ($permissions as $permission)
                                                @foreach ($roles as $role)
                                                    @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                                        @if ($rhp->permission_id == $permission->id)
                                                            @php
                                                                $name = $permission->name;
                                                            @endphp
                                                            @if (stristr($name, 'project'))
                                                                @php
                                                                    $value = substr(strstr($name, '-'), 1);
                                                                    // echo $value;
                                                                @endphp
                                                                @if ($value == 'list')
                                                                    {{-- <a class="btn btn-success"
                                                                    href="{{ route('admin.projects.show', $project->id) }}">Show</a> --}}
                                                                @elseif ($value == 'create')

                                                                @elseif ($value == 'edit')
                                                                    <a class="btn btn-primary btn-sm"
                                                                        href="{{ route('admin.projects.edit', $project->id) }}"><i
                                                                            class="fas fa-edit"></i></a>
                                                                @elseif ($value == 'delete')
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.projects.destroy', $project->id], 'style' => 'display:inline-table']) !!}
                                                                    {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn btn-danger btn-sm']) }}
                                                                    {!! Form::close() !!}
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
                    {{ $data->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //search image
        $(document).on('input', '#input_search_image_file', function() {
            // use for previews table hidden
            document.getElementsByClassName('project-hidden')[0].style.visibility =
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
                url: "{{ route('admin.projects.imageSearch') }}",
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
