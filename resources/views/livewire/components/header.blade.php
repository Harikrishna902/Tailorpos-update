<div>
    <div class="page-main-header">
        <div class="main-header-right row m-0">
            <div class="main-header-left">
                @if (Auth::user()->user_type == 1)
                    <div class="logo-wrapper"><a href="{{ route('superadmin.master') }}"><img class="img-fluid"
                                src="{{ getApplicationLogo() }}" alt="" /></a></div>
                @elseif(Auth::user()->user_type == 2)
                    <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid"
                                src="{{ getApplicationLogo() }}" alt="" /></a></div>
                @elseif(Auth::user()->user_type == 3)
                    <div class="logo-wrapper"><a href="{{ route('admin.invoice') }}"><img class="img-fluid"
                                src="{{ getApplicationLogo() }}" alt="" /></a></div>
                @endif
                <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                        id="sidebar-toggle"></i></div>
            </div>
            <div class="nav-right col pull-right right-menu p-0">
                <ul class="nav-menus">
                    @if (Auth::user()->user_type == 3)
                        <li><a class="text-dark" href="#" wire:click.prevent="check"><i
                                    data-feather="printer"></i></a></li>
                        <li><a class="text-dark" href="{{ url('admin/payments') }}"><i
                                    data-feather="dollar-sign"></i></a></li>
                    @endif
                    @if (Auth::user()->user_type != 1)
                        <li><a class="text-dark" href="{{ url('admin/reports/daily') }}"><i
                                    data-feather="bar-chart"></i></a></li>
                    @endif
                    <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                                data-feather="maximize"></i></a></li>
                    @if (count($languages) > 0)
                        <li class="onhover-dropdown p-0">
                            <button class="btn btn-primary-light" type="button">{{$lang ? (isset($languages[$lang]) ? $languages[$lang] : 'English') : __('main.english') }}</button>
                            <div class="bookmark-dropdown onhover-show-div">
                                <ul class="m-t-5">
                                    @foreach ($languages as $key => $item)
                                        <li class="" wire:click.prevent="changeLanguage('{{ $key }}')"> {{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
        </div>
    </div>
</div>