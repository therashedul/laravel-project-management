     {{-- {{ $menus }} --}}
     <div class="col-md-3 left_col">
         <div class="left_col scroll-view">
             <div class="navbar nav_title" style="border: 0;">
                 @php
                     $values = DB::table('users')
                         ->where('role_id', Auth::user()->role_id)
                         ->get();
                 @endphp
                 <a href="{{ asset($values[0]->name) }}"
                     class="site_title text-center d-block"><span>Dashaboard</span></a>
             </div>
             <div class="clearfix"></div>

             <!-- sidebar menu -->
             <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                 <div class="menu_section">

                     <ul class="nav side-menu">
                         @php
                             $rhps = DB::table('role_has_permissions')->get();
                             $permissions = DB::table('permissions')->get();
                             $roles = DB::table('roles')->get();
                         @endphp
                         @foreach ($roles as $role)
                             @foreach ($rhps as $rhp)
                                 @foreach ($permissions as $permission)
                                     @if ($role->id == Auth::user()->role_id && $role->id == $rhp->role_id)
                                         @if ($rhp->permission_id == $permission->id)
                                             @php
                                                 $name = $permission->name;
                                             @endphp
                                             @if (stristr($name, 'menu'))
                                                 @php
                                                     $value = substr(strstr($name, '-'), 1);
                                                     $role_id = Auth::user()->role_id;
                                                     $nameRole = DB::table('roles')
                                                         ->where('id', $role_id)
                                                         ->get();
                                                     $role_name = $nameRole[0]->name;
                                                     //  print_r($role_name);
                                                 @endphp

                                                 <li>
                                                     @if ($value == 'users')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'roles')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'permissions')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'projects')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'media')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'documents')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'tasks')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @elseif ($value == 'dailyTask')
                                                         <a class="btn btn-primary"
                                                             href="{{ route($role_name . '.' . $value) }}"
                                                             style="text-transform: uppercase;">{{ $value }}</a>
                                                     @else
                                                     @endif
                                                 </li>
                                             @endif
                                         @endif
                                     @endif
                                 @endforeach
                             @endforeach
                         @endforeach
                     </ul>
                 </div>
             </div>
             <!-- /sidebar menu -->

         </div>
     </div>
