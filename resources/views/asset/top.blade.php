  <!-- top navigation -->
  <div class="top_nav">
      <div class="nav_menu">
          <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                  <li class="nav-item dropdown open" style="padding-left: 15px;">
                      <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true"
                          id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                          @php
                              $values = DB::table('users')
                                  ->where('id', Auth::user()->id)
                                  ->get();
                          @endphp
                          @if (!empty($values[0]->profile_image))
                              <img src="{{ asset('thumbnail/' . $values[0]->profile_image) }}" width="100px"
                                  height="80px" alt="" title="">
                          @else
                              <img src="{{ asset('images/profile/profile-image.png') }}" width="100px" height="80px"
                                  alt="" title="">
                          @endif
                          {{ $values[0]->name }}
                          {{-- <img src="{{ asset('/') }}images/img.jpg" alt="">{{ Auth::user()->id }} --}}
                      </a>
                      <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item"
                              href="{{ route($values[0]->name . '.users.show', $values[0]->id) }}">
                              Profile</a>
                          <a class="dropdown-item"
                              href="{{ route($values[0]->name . '.users.edit', $values[0]->id) }}">
                              <span>Settings</span>
                          </a>
                          <a class="dropdown-item" href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"><i
                                  class="fa fa-sign-out pull-right"></i> Log
                              Out</a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                              @csrf
                          </form>
                      </div>
                  </li>
              </ul>
          </nav>
      </div>
  </div>
  <!-- /top navigation -->
