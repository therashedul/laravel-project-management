@extends('layouts.deshboard')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <div class="card-header">Document
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
                                    if ($pvalue->name == 'document-create') {
                                        echo ' <span class="float-right">';
                                        echo '<a class="btn btn-primary btn-sm" href="documents.create"><i class="fas fa-plus"></i> New Document</a>';
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
                    <div id="project_search_response"></div>
                    <div class="document-hidden">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Document Name</th>
                                    <th>Project Name</th>
                                    <th>File</th>
                                    <th>Create Date</th>
                                    <th width="200px" class="text-center">Action</th>
                                </tr>
                            </thead>
                            @php
                                $rhps = DB::table('role_has_permissions')->get();
                                $permissions = DB::table('permissions')->get();
                                $roles = DB::table('roles')->get();
                                // $roles = DB::table('documents')->get();
                            @endphp

                            <tbody>
                                @foreach ($data as $key => $document)
                                    <tr>
                                        <td>{{ $document->id }}</td>

                                        <td>{{ $document->document_title }}</td>
                                        <td>
                                            @php
                                                $projects = DB::table('projects')
                                                    ->where('id', $document->project_id)
                                                    ->get();
                                                $projectName = $projects[0]->project_name;
                                            @endphp
                                            {{ $projectName }}
                                        </td>
                                        <td>
                                            @if ($document->document_image_id)
                                                @php
                                                    $files = DB::table('image_uploads')
                                                        ->where('id', $document->document_image_id)
                                                        ->get();
                                                    $fileName = $files[0]->title;
                                                @endphp
                                                <a href="{{ asset('files/' . $files[0]->name) }}" target="_blank">
                                                    Download <i class="fas fa-download"></i></a>
                                            @elseif($document->document_image_id == null)
                                                <p>No File here</p>
                                            @endif

                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($document->created_at)) }}</td>
                                        <td class="text-center">
                                            @foreach ($rhps as $rhp)
                                                @foreach ($permissions as $permission)
                                                    @foreach ($roles as $role)
                                                        @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                                            @if ($rhp->permission_id == $permission->id)
                                                                @php
                                                                    $name = $permission->name;
                                                                @endphp
                                                                @if (stristr($name, 'document'))
                                                                    @php
                                                                        $value = substr(strstr($name, '-'), 1);
                                                                    @endphp
                                                                    @if ($value == 'list')
                                                                        {{-- <a class="btn btn-success"
                                                                    href="{{ route('admin.documents.show', $document->id) }}">Show</a> --}}
                                                                    @elseif ($value == 'create')
                                                                        {{-- <a class="btn btn-primary"
                                                                        href="{{ route('admin.documents.create') }}">New
                                                                        document</a> --}}
                                                                    @elseif ($value == 'edit')
                                                                        @if ($document->document_image_id == null)
                                                                            <a class="btn btn-primary btn-sm"
                                                                                onclick="Warn()"
                                                                                href="{{ route('admin.documents.edit', $document->id) }}"><i
                                                                                    class="fas fa-edit"></i></a>
                                                                        @else
                                                                            <a class="btn btn-primary btn-sm"
                                                                                href="{{ route('admin.documents.edit', $document->id) }}"><i
                                                                                    class="fas fa-edit"></i></a>
                                                                        @endif
                                                                    @elseif ($value == 'delete')
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['admin.documents.destroy', $document->id], 'style' => 'display:inline']) !!}
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
                                            {{-- @if ($document->document_image_id == null)
                                            <a class="btn btn-primary" onclick="Warn()"
                                                href="{{ route('admin.documents.edit', $document->id) }}">Edit</a>
                                        @else
                                            <a class="btn btn-primary"
                                                href="{{ route('admin.documents.edit', $document->id) }}">Edit</a>
                                        @endif
                                        @if (Auth::user()->name == 'admin')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['admin.documents.destroy', $document->id], 'style' => 'display:inline']) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endif --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //search image
        $(document).on('input', '#input_search_image_file', function() {
            // use for previews table hidden
            document.getElementsByClassName('document-hidden')[0].style.visibility =
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
                url: "{{ route('admin.documents.filesearch') }}",
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

        function Warn() {
            alert("Please Select Document");
        }
    </script>
@endsection
