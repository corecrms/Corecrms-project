<nav class="navbar navbar-expand border-bottom bg-white navbar-light sticky-top px-4 py-0" style="height: 80px">
    <a href="#" class="sidebar-toggler text-decoration-none flex-shrink-0 align-items-center d-inline-flex btn-orange-bg">
        <i class="fa fa-bars"></i>
    </a>
    @php
        $role = auth()->user()->roles->first()->name ?? '';
        if ($role == 'Admin' || $role == 'Manager') {
            $warehouses = \App\Models\Warehouse::all();
        } else {
            $warehouses = \App\Models\Warehouse::where('user_id', auth()->user()->id)->get();
        }
        // $warehouses = \App\Models\Warehouse::all();
    @endphp

    @if (auth()->user()->hasRole('Admin'))
        <form class="ms-4" action="{{ route('switch-warehouse') }}" method="POST">
            @csrf
            <select class="form-select form-control rounded-5" name="warehouse_id" id="selected_warehouse_id"
                onchange="this.form.submit()">
                <option value="">Select warehouse</option>
                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}"
                        {{ $warehouse->id == session('selected_warehouse_id') ? 'selected' : '' }}>
                        {{ $warehouse->users->name ?? '' }}</option>
                @endforeach
            </select>
        </form>
    @endif
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item">
            <a href="{{route('sales.pos')}}" class="nav-link">
                <button class="border-theme btn bg-theme btn-orange-bg rounded-5">POS</button>
            </a>

        </div>
          <div class="nav-item dropdown">
              <a href="#" class="nav-link" data-bs-toggle="dropdown">
                  <i class="fa fa-bell align-items-center d-inline-flex"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">

                    @php
                        $notifications = \App\Models\UserNotification::all();
                    @endphp
                  @forelse ( $notifications as $notification)
                      <a href="#" class="dropdown-item">
                          <h6 class="fw-normal mb-0">{{ $notification->subject }}</h6>
                          <small>{{ $notification->created_at->diffForHumans() }}</small>
                      </a>
                    @empty
                        <a href="#" class="dropdown-item">
                            <h6 class="fw-normal mb-0">No notification</h6>
                        </a>
                  @endforelse

                  <a href="{{route('notification.index')}}" class="dropdown-item text-center">See all notifications</a>
              </div>
          </div>
          <div class="nav-item dropdown">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                  <img class="rounded-circle me-lg-2" src="{{(isset(auth()->user()->image) || auth()->user()->image !=null)  ? asset('/storage'.auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}" alt=""
                      style="width: 40px; height: 40px" />
              </a>
              <div class="dropdown-menu dropdown-menu-end bg-light  rounded-0 rounded-bottom m-0">
                  <a href="{{route('profile.index')}}" class="dropdown-item">My Profile</a>
                  @role(['Admin', 'Manager'])
                    <a href="{{route('setting.index')}}" class="dropdown-item">Settings</a>
                  @endrole

                      <li><a class="dropdown-item" href="{{ route('logout') }}"
                          onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">Logout</a>
                  </li>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
              </div>
          </div>
      </div>
</nav>
