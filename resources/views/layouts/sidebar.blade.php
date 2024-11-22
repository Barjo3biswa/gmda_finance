<nav id="sidebar" class="">
    <div class="sidebar-header">
        <a href="{{ env('APP_URL') }}/GMDA/gmda-auth/public/"><img class="main-logo" src="{{ asset('img/logo/logo.png')}}" alt="" /></a>
        <strong><a href="{{ env('APP_URL') }}/GMDA/gmda-auth/public/"><img src="{{ asset('img/logo/logosn.png')}}" alt="" /></a></strong>
    </div>
    <div class="left-custom-menu-adp-wrap comment-scrollbar">
        <nav class="sidebar-nav left-sidebar-menu-pro">
            <ul class="metismenu" id="menu1">
                {{-- <li class="active">
                    <a class="has-arrow" href="index.html">
                           <span class="educate-icon educate-home icon-wrap"></span>
                           <span class="mini-click-non">Education</span>
                        </a>
                    <ul class="submenu-angle" aria-expanded="true">
                        <li><a title="Dashboard v.1" href="index.html"><span class="mini-sub-pro">Dashboard v.1</span></a></li>
                        <li><a title="Dashboard v.2" href="index-1.html"><span class="mini-sub-pro">Dashboard v.2</span></a></li>
                    </ul>
                </li> --}}
                <li class="active">
                    <a title="Home" href="{{ env('APP_URL') }}/GMDA/gmda-auth/public/" aria-expanded="false"><span class="educate-icon educate-home icon-wrap"></span></span> <span class="mini-click-non">Home</span></a>
                </li>
                <li>
                    <a title="Leave Module" href="{{ env('APP_URL') }}/GMDA/gmda-leave/public/?token={{ session('jwt_token') }}" aria-expanded="false"><span class="educate-icon educate-event icon-wrap sub-icon-mg" aria-hidden="true"></span> <span class="mini-click-non">Leave Module</span></a>
                </li>
            </ul>
        </nav>
    </div>
</nav>
