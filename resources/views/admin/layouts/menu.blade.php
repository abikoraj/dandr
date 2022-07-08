<aside id="leftsidebar" class="sidebar">
    <div class="navbar-brand">
        <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
        <a href="index.html"><img src="{{ asset('backend/images/logo.svg') }}" width="25" alt="Aero"><span class="m-l-10">Dairy Management</span></a>
    </div>
    <div class="menu">
        <ul class="list">
            <li>
                <div class="user-info">
                    <a class="image" href="#"><img src="{{ asset('backend/images/user.png') }}" alt="User"></a>
                    <div class="detail">
                        <h4>{{ Auth::user()->name }}</h4>
                        @if(env('authphone','9852059717')==Auth::user()->phone)
                            <small>Super Admin</small>
                        @else
                            <small>Admin</small>
                        @endif
                    </div>
                </div>
            </li>
            {{-- helloooo --}}
            @php
                $blocks = \App\Menu::get();
                $pers=Config::get('per.per', []);

            @endphp
            <script>
                console.log({!! json_encode($pers) !!})
            </script>
            @foreach ($blocks as $block)
                @include('admin.layouts.menu_block',['block'=>$block,'pers'=>$pers])
            @endforeach
            <li><a href="{{ route('logout') }}" class="waves-effect waves-block" target="_top"><i class="zmdi zmdi-power"></i><span>Sign Out</span></a></li>
        </ul>
    </div>
</aside>
