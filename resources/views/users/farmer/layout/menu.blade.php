<aside id="leftsidebar" class="sidebar">
    <div class="navbar-brand">
        <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
        <a href="#"><img src="{{ asset('backend/images/logo.svg') }}" width="25" alt="Aero"><span class="m-l-10">{{ env('APP_NAME') }}</span></a>
    </div>
    <div class="menu">
        <ul class="list">
            <li>
                <div class="user-info">
                    <a class="image" href="profile.html"><img src="{{ asset('backend/images/user.png') }}" alt="User"></a>
                    <div class="detail">
                        <h4>{{ Auth::user()->name }}</h4>
                        <small>Farmer</small>
                    </div>
                </div>
            </li>
            <li class="active open"><a href="{{ route('farmer.dashboard')}}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
            <li><a href="{{ route('farmer.milk.detail') }}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Transation Detail</span></a></li>
            <li><a href="{{ route('farmer.password.page') }}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Change Password</span></a></li>
            <li><a href="{{ route('logout') }}" class="waves-effect waves-block" target="_top"><i class="zmdi zmdi-power"></i><span>Sign Out</span></a></li>
        </ul>
    </div>
</aside>
