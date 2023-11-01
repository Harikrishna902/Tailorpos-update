<div wire:ignore>
    <header class="main-nav">
        <div class="sidebar-user">
            <div class="d-flex align-items-center">
                <img class="img-60 rounded-circle" src="{{getCompanyLogo()}}" alt="" />
                <div class="ms-3 text-left">
                    <h6 class="text-uppercase mb-1 text-primary">{{Str::limit($company_name,13)}}</h6>
                    <p class="mb-0 text-uppercase">{{Auth::user()->name}}</p>
                </div>
            </div>
        </div>
        <nav>
            <div class="main-navbar" wire:ignore.self>
                <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                <div id="mainnav">
                    <ul class="nav-menu custom-scrollbar">
                        <li class="back-btn">
                            <div class="mobile-back text-end"><span>{{ __('main.back') }} </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                        </li>
                        <li class="sidebar-main-title">
                            <div class="text-primary text-uppercase text-xs fw-bold">
                                {{ __('main.main') }}
                            </div>
                        </li>
                        @if(Auth::user()->user_type==2)
                        <li class="" wire:ignore>
                            <a class="nav-link menu-title link-nav {{ Request::is('admin') ? 'active' : '' }}" href="{{route('admin.dashboard')}}"><i data-feather="home"></i>
                                <span>{{ __('main.dashboard') }} </span>
                            </a>
                        </li>
                         @endif
                        <li class="">
                            <a href="#" class="nav-link menu-title link-nav {{ Request::is('admin/invoice*') ? 'active' : '' }}" wire:click.prevent="check"> <span  wire:ignore> <i data-feather="printer"></i></span>
                                <span>{{ __('main.invoice') }} </span>
                            </a>
                        </li>
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/sales') || Request::is('admin/sales/view*') ? 'active' : '' }}" href="{{route('admin.sales')}}"><i data-feather="trending-up"></i>
                                <span>{{ __('main.sales_list') }} </span>
                            </a>
                        </li>
                        @if(Auth::user()->user_type == 2)
                        <li  wire:ignore class="dropdown"><a class="nav-link menu-title {{ Request::is('admin/expense*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="trending-down"></i><span>{{ __('main.expense') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/expense') ? 'active' : '' }}" href="{{route('admin.expenses')}}">{{ __('main.expense_list') }}</a></li>
                                <li><a class="{{ Request::is('admin/expense/heads*') ? 'active' : '' }}" href="{{route('admin.expense_heads')}}">{{ __('main.expense_heads') }} </a></li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::user()->user_type!=2)
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/sales/returns*') ? 'active' : '' }}" href="{{route('admin.sales_return_list')}}"><i data-feather="rotate-ccw"></i>
                                <span>{{ __('main.sales_return') }} </span>
                            </a>
                        </li>
                        @endif
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/status-screen') ? 'active' : '' }}" href="{{route('admin.status_screen')}}"><i data-feather="monitor"></i>
                                <span>{{ __('main.status_screen') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-main-title"  wire:ignore>
                            <div class="text-primary text-uppercase text-xs fw-bold">
                                {{ __('main.front_end') }}
                            </div>
                        </li>
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/online-customers*') ? 'active' : '' }}" href="{{route('admin.online-customers')}}"><i data-feather="users"></i>
                                <span>{{ __('main.online_customers') }}</span>
                            </a>
                        </li>
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/online-orders*') ? 'active' : '' }}" href="{{route('admin.online-orders')}}"><i data-feather="printer"></i>
                                <span>{{ __('main.online_orders') }}</span>
                            </a>
                        </li>
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/online-appointments') ? 'active' : '' }}" href="{{route('admin.online-appointments')}}"><i data-feather="phone"></i>
                                <span>{{ __('main.appointments') }}</span>
                            </a>
                        </li>
                     
                        @if((Auth::user()->user_type==2))

                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/sliders') ? 'active' : '' }}" href="{{route('admin.sliders')}}"><i data-feather="image"></i>
                                <span>{{ __('main.sliders') }}</span>
                            </a>
                        </li>
                        <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/offers') ? 'active' : '' }}" href="{{route('admin.offers')}}"><i data-feather="award"></i>
                                <span>{{ __('main.offers') }}</span>
                            </a>
                        </li>
                       
                         @endif
                         <li class=""  wire:ignore>
                            <a class="nav-link menu-title link-nav  {{ Request::is('admin/contact-messages*') ? 'active' : '' }}" href="{{route('admin.contact-messages')}}"><i data-feather="message-square"></i>
                                <span>{{ __('main.messages') }}</span>
                            </a>
                        </li>
                        <li  wire:ignore class="dropdown"><a class="nav-link menu-title {{ Request::is('admin/pages*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="file-text"></i><span>{{ __('main.pages') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/pages/privacy-policy') ? 'active' : '' }}" href="{{route('admin.privacy-policy')}}">{{ __('main.privacy_policy') }}</a></li>
                                <li><a class="{{ Request::is('admin/pages/terms*') ? 'active' : '' }}" href="{{route('admin.terms-conditions')}}">{{ __('main.terms_conditions') }} </a></li>
                            </ul>
                        </li>
                        <li class="sidebar-main-title mb-0"  wire:ignore>
                            <div class="text-primary text-uppercase text-xs fw-bold">
                                {{ __('main.manage') }}
                            </div>
                        </li>

                        <li  wire:ignore class="dropdown {{ Request::is('admin/customer*') ? 'show' : '' }}"><a class="nav-link menu-title {{ Request::is('admin/customer*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="users"></i><span>{{ __('main.customers') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/customers*') ? 'active' : '' }}" href="{{route('admin.customers')}}">{{ __('main.customers_list') }} </a></li>
                                <li><a class="{{ Request::is('admin/customer-groups*') ? 'active' : '' }}" href="{{route('admin.customer_groups')}}">{{ __('main.customer_groups') }}</a></li> 
                            </ul>
                        </li>
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/payments*') ? 'active' : '' }}" href="{{route('admin.payments')}}"><i data-feather="dollar-sign"></i>
                                <span>{{ __('main.receipts') }} </span>
                            </a>
                        </li>
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/ledger*') ? 'active' : '' }}" href="{{route('admin.ledger')}}"><i data-feather="book"></i>
                                <span>{{ __('main.ledger') }} </span>
                            </a>
                        </li>
                        @if(Auth::user()->user_type!=2)
                        <li  wire:ignore class="dropdown"><a class="nav-link menu-title {{ Request::is('admin/expense*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="trending-down"></i><span>{{ __('main.expense') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/expense') ? 'active' : '' }}" href="{{route('admin.expenses')}}">{{ __('main.expense_list') }}</a></li>
                                <li><a class="{{ Request::is('admin/expense/heads*') ? 'active' : '' }}" href="{{route('admin.expense_heads')}}">{{ __('main.expense_heads') }} </a></li>
                            </ul>
                        </li>
                        @endif
                        @if((Auth::user()->user_type==2))
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/inventory/product') ? 'active' : '' }}" href="{{route('admin.product')}}"><i data-feather="archive"></i>
                                <span>{{ __('main.products') }} </span>
                            </a>
                         </li>
                         @endif
                       
                         @if(getBrachProductCreatePriviledge()==1)
                         @if((Auth::user()->user_type==3))
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/inventory/product') ? 'active' : '' }}" href="{{route('admin.product')}}"><i data-feather="archive"></i>
                                <span>{{ __('main.products') }} </span>
                            </a>
                         </li>
                         @endif
                         @endif
                        @if(Auth::user()->user_type==2)
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/measurements*') ? 'active' : '' }}" href="{{route('admin.measurements')}}"><i data-feather="maximize-2"></i>
                                <span>{{ __('main.measurements') }}</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->user_type==2)
                        <li  wire:ignore class="dropdown {{ Request::is('admin/purchase*') ? 'show' : '' }}"><a class="nav-link menu-title {{ Request::is('admin/purchase*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="truck"></i><span>{{ __('main.purchase') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/purchase') || Request::is('admin/purchase/view*') || Request::is('admin/purchase/edit*') || Request::is('admin/purchase/create*') ? 'active' : '' }}"  href="{{route('admin.purchases')}}">{{ __('main.purchase_list') }}</a></li>
                                <li><a class="{{ Request::is('admin/purchase/payments*') ? 'active' : '' }}" href="{{route('admin.purchase_payments')}}">{{ __('main.payments') }}</a></li>
                                <li><a class="{{ Request::is('admin/purchase/suppliers*') ? 'active' : '' }}" href="{{route('admin.suppliers')}}">{{ __('main.suppliers') }} </a></li>
                                <li><a class="{{ Request::is('admin/purchase/materials*') ? 'active' : '' }}" href="{{route('admin.materials')}}">{{ __('main.materials') }} </a></li>
                            </ul>
                        </li>
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/branch') ? 'active' : '' }}" href="{{route('admin.branch')}}"><i data-feather="git-branch"></i>
                                <span>{{ __('main.branches') }} </span>
                            </a>
                        </li>
                        @endif
                        @if(getBrachMaterialCreatePriviledge()==1)
                        @if(Auth::user()->user_type==3)
                        <li  wire:ignore class="dropdown {{ Request::is('admin/purchase*') ? 'show' : '' }}"><a class="nav-link menu-title {{ Request::is('admin/purchase*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="truck"></i><span>{{ __('main.purchase') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/purchase/materials*') ? 'active' : '' }}" href="{{route('admin.materials')}}">{{ __('main.materials') }} </a></li>
                            </ul>
                        </li>
                        @endif
                        @endif
                        @if(Auth::user()->user_type!=2)
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/staff') ? 'active' : '' }}" href="{{route('admin.staff')}}" ><i data-feather="user"></i>
                                <span>{{ __('main.staffs') }} </span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->user_type==2)
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/stock-adjustment*') ? 'active' : '' }}" href="{{route('admin.stock_adjustments')}}"><i data-feather="clipboard"></i>
                                <span>{{ __('main.stock_adjustment') }}</span>
                            </a>
                        </li>
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav {{ Request::is('admin/stock-transfer*') ? 'active' : '' }}" href="{{route('admin.stock_transfer')}}"><i data-feather="repeat"></i>
                                <span>{{ __('main.stock_transfer') }}</span>
                            </a>
                        </li>
                         @endif
                        <li  wire:ignore class="dropdown {{ Request::is('admin/reports*') ? 'show' : '' }}"><a class="nav-link menu-title {{ Request::is('admin/reports*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="bar-chart"></i><span>{{ __('main.reports') }} </span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a class="{{ Request::is('admin/reports/daily') ? 'active' : '' }}" href="{{route('admin.report.daily')}}" >{{ __('main.daily_sales_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/sales') ? 'active' : '' }}" href="{{route('admin.report.sales')}}">{{ __('main.sales_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/payments') ? 'active' : '' }}" href="{{route('admin.report.payments')}}">{{ __('main.payment_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/expense') ? 'active' : '' }}" href="{{route('admin.report.expense')}}">{{ __('main.expense_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/sales-return') ? 'active' : '' }}" href="{{route('admin.report.sales_return')}}">{{ __('main.sales_return_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/day-wise') ? 'active' : '' }}" href="{{route('admin.report.daywise')}}">{{ __('main.day_wise') }}</a></li>
                                @if(Auth::user()->user_type==2)
                                <li><a class="{{ Request::is('admin/reports/purchase') ? 'active' : '' }}" href="{{route('admin.report.purchase')}}">{{ __('main.purchase_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/purchase-payment') ? 'active' : '' }}" href="{{route('admin.report.purchase_payment')}}">{{ __('main.purchase_payments') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/stock') ? 'active' : '' }}" href="{{route('admin.report.stock')}}">{{ __('main.stock_report') }}</a></li>
                                @endif
                                <li><a class="{{ Request::is('admin/reports/stock-branch') ? 'active' : '' }}" href="{{route('admin.report.stock_branch')}}">{{ __('main.branch_stock_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/income') ? 'active' : '' }}" href="{{route('admin.report.income')}}">{{ __('main.income_report') }}</a></li>
                                <li><a class="{{ Request::is('admin/reports/staff') ? 'active' : '' }}" href="{{route('admin.report.staff')}}">{{ __('main.staff_report') }}</a></li>
                            </ul>
                        </li>
                        <!--Only available for app admin-->
                        @if(Auth::user()->user_type==2)
                        <li  wire:ignore class="dropdown"><a class="nav-link menu-title {{ Request::is('admin/settings*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="settings"></i><span>{{ __('main.administration') }}</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="{{route('admin.company')}}" class="{{ Request::is('admin/settings/company') ? 'active' : '' }}">{{ __('main.company_settings') }}</a></li>
                                <li><a href="{{route('admin.measurement_settings')}}" class="{{ Request::is('admin/settings/measurement') ? 'active' : '' }}">{{ __('main.measurement_settings') }}</a></li>
                                <li><a href="{{route('admin.invoice-settings')}}" class="{{ Request::is('admin/settings/invoice') ? 'active' : '' }}">{{ __('main.invoice_settings') }}</a></li>
                                <li><a href="{{route('admin.financial_year')}}" class="{{ Request::is('admin/settings/financial-year') ? 'active' : '' }}">{{ __('main.financial_year_settings') }}</a></li>
                                <li><a href="{{route('admin.mail-settings')}}" class="{{ Request::is('admin/settings/mail') ? 'active' : '' }}">{{ __('main.mail_settings') }}</a></li>
                                <li><a href="{{route('admin.master-settings')}}" class="{{ Request::is('admin/settings/master') ? 'active' : '' }}">{{ __('main.master_settings') }}</a></li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::user()->user_type==3)
                        <li  wire:ignore class="dropdown"><a class="nav-link menu-title {{ Request::is('admin/settings*') ? 'active' : '' }}" href="javascript:void(0)"><i data-feather="settings"></i><span>{{ __('main.settings') }}</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="{{route('branch.settings')}}" class="{{ Request::is('admin/settings/branch') ? 'active' : '' }}">{{ __('main.branch_settings') }}</a></li>
                            </ul>
                        </li>
                        @endif
                        <li  wire:ignore class="">
                            <a class="nav-link menu-title link-nav" href="{{route('admin.logout')}}"><i data-feather="log-out"></i>
                                <span>{{ __('main.logout') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    @push('js')
    <script>
        'use strict';
            (function () {
                let activebars = document.querySelectorAll('.menu-title.active');
                if(activebars.length > 0)
                {
                    if (jQuery(activebars[0]).next().is(':hidden') == true) {
                        jQuery(activebars[0]).addClass('active');
                        jQuery(activebars[0]).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                        jQuery(activebars[0]).next().slideDown(0);
                    } else {
                        jQuery(activebars[0]).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
                    }
                }
            }($))
        </script>
    @endpush
</div>