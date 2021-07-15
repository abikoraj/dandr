{{-- @extends('layouts.app') --}}

<!-- Page Sub Menu
============================================= -->
<div id="page-menu">
    <div id="page-menu-wrap">
        <div class="container">
            <div class="page-menu-row">
                    <img src="{{ asset('assets/logo.svg') }}" alt="" style="height: 70px;">
                <div class="page-menu-title"></div>
                {{-- <div class="page-menu-title">Nawa Durga <span>DAIRY</span></div> --}}
                <nav class="page-menu-nav one-page-menu">
                    <ul class="page-menu-container">
                        <li class="page-menu-item"><a href="#" data-href="#slider">
                                <div>Start</div>
                            </a></li>
                        <li class="page-menu-item"><a href="#" data-href="#section-about">
                                <div>About</div>
                            </a></li>
                        <li class="page-menu-item"><a href="#" data-href="#section-work">
                                <div>Work</div>
                            </a></li>
                        {{-- <li class="page-menu-item"><a href="#" data-href="#section-team">
                                <div>Team</div>
                            </a></li> --}}
                        <li class="page-menu-item"><a href="#" data-href="#section-services">
                                <div>Services</div>
                            </a></li>
                        {{-- <li class="page-menu-item"><a href="#" data-href="#section-pricing">
                                <div>Pricing</div>
                            </a></li> --}}
                        <li class="page-menu-item"><a href="#" data-href="#section-video">
                                <div>Video</div>
                            </a></li>
                        <li class="page-menu-item"><a href="#" data-href="#section-contact">
                                <div>Contact</div>
                            </a></li>
                        <li class="page-menu-item">
                            <a href="{{ url('login')}}">Login</a>
                        </li>
                    </ul>
                </nav>

                <div id="page-menu-trigger"><i class="icon-reorder"></i></div>

            </div>
        </div>
    </div>
</div><!-- #page-menu end -->
