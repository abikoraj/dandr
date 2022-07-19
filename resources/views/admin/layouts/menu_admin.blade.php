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

            @endphp
            <li class="active open"><a href="{{ route('admin.dashboard')}}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
            @if (env('use_farmer',false))

                <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-apps"></i><span>Farmer</span></a>
                    <ul class="ml-menu">
                        <li><a href="{{ route('admin.farmer.list') }}" class="waves-effect waves-block">Farmer List</a></li>
                        <li><a href="{{ route('admin.farmer.advance') }}" class="waves-effect waves-block">Advance</a></li>
                        <li><a href="{{ route('admin.farmer.due') }}" class="waves-effect waves-block">Payment From Farmer</a></li>
                        <li><a href="{{ route('admin.farmer.due.add.list') }}" class="waves-effect waves-block">Account Opening</a></li>
                        <li><a href="{{ route('admin.farmer.milk.payment.index') }}" class="waves-effect waves-block">Milk Payment</a></li>
                        <li><a href="{{ route('admin.sell.item.index') }}" class="waves-effect waves-block">Farmer Sell</a></li>
                    </ul>
                </li>
            @endif
            @if (env('use_pos',false))
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Branch</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.center.index') }}" class="waves-effect waves-block">Manage Branches</a></li>

                </ul>
            </li>
            @endif
            @if (env('use_milk',false))
                <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Milk Collection</span></a>
                    <ul class="ml-menu">
                        <li><a href="{{ route('admin.center.index') }}" class="waves-effect waves-block">Manage Collection Center</a></li>
                        <li><a href="{{ route('admin.milk.index') }}" class="waves-effect waves-block"> Milk Collection</a></li>
                        <li><a href="{{ route('admin.snf-fat.index') }}" class="waves-effect waves-block">Add Fat & Snf</a></li>
                    </ul>
                </li>
            @endif

            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Items</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.item.index') }}" class="waves-effect waves-block">Items</a></li>
                    <li><a href="{{ route('admin.item.stockout-list') }}" class="waves-effect waves-block">Stock Out</a></li>
                    <li><a href="{{ route('admin.item.items-center-stock') }}" class="waves-effect waves-block">Branch Stock </a></li>
                    <li><a href="{{ route('admin.item.packaging.index') }}" class="waves-effect waves-block">Repackaging</a></li>
                    <li><a href="{{ route('admin.wastage.index') }}" class="waves-effect waves-block">Wastage</a></li>
                </ul>
            </li>
            {{-- @if (env('tier',1)==1) --}}
            @if (env('use_distributer',false))
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Distributers</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.distributer.index') }}" class="waves-effect waves-block">Distributer List</a></li>
                    <li><a href="{{ route('admin.distributer.sell') }}" class="waves-effect waves-block">Distributer Sell</a></li>
                    <li><a href="{{ route('admin.distributer.payemnt') }}" class="waves-effect waves-block">payment</a></li>
                    <li><a href="{{ route('admin.distributer.detail.opening') }}" class="waves-effect waves-block">Account Opening</a></li>
                    <li><a href="{{ route('admin.distributer.request') }}" class="waves-effect waves-block">Distributor Request</a></li>
                    <li><a href="{{ route('admin.distributer.credit.list') }}" class="waves-effect waves-block"> Credit List</a></li>
                    @if (env('dis_snffat',0)==1)
                    <li><a href="{{ route('admin.distributer.snffat.index') }}" class="waves-effect waves-block">SNF FAT</a></li>
                    <li><a href="{{ route('admin.distributer.MilkData.index') }}" class="waves-effect waves-block">Milk Data</a></li>
                    @endif
                </ul>
            </li>
            @endif
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Manage Expense</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.expense.category') }}" class="waves-effect waves-block"><span>Expense Categories</span></a></li>
                    <li><a href="{{ route('admin.expense.index') }}" class="waves-effect waves-block"><span>Expenses</span></a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Suppliers</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.supplier.index') }}" class="waves-effect waves-block">Supplier List</a></li>
                    <li><a href="{{ route('admin.supplier.bill') }}" class="waves-effect waves-block">Purchase Bill</a></li>
                    <li><a href="{{ route('admin.supplier.pay') }}" class="waves-effect waves-block">Supplier Payment</a></li>
                    <li><a href="{{ route('admin.supplier.previous.balance') }}" class="waves-effect waves-block">Opening Blance</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Staff Manage</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.employee.index') }}" class="waves-effect waves-block">Employees </a></li>
                    <li><a href="{{ route('admin.employee.account.index') }}" class="waves-effect waves-block">Account Opening</a></li>
                    <li><a href="{{ route('admin.employee.advance') }}" class="waves-effect waves-block">Advance</a></li>
                    <li><a href="{{ route('admin.salary.pay') }}" class="waves-effect waves-block">Salary Pay</a></li>
                    <li><a href="{{ route('admin.employee.ret') }}" class="waves-effect waves-block">Return Advance</a></li>
                </ul>
            </li>

            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Customers</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.customer.home') }}" class="waves-effect waves-block">List </a></li>
                    <li><a href="{{ route('admin.customer.payment.index') }}" class="waves-effect waves-block">Payment</a></li>
                    <li><a href="{{ route('admin.customer.credit-list.index') }}" class="waves-effect waves-block">Due  SMS</a></li>
                    <li><a href="{{ route('admin.customer.promo') }}" class="waves-effect waves-block">Promo  SMS</a></li>
                </ul>
            </li>
            @if (env('use_manufacture',false))
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Manufacture</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.manufacture.product.index') }}" class="waves-effect waves-block">Manage Products</a></li>
                    <li><a href="{{ route('admin.manufacture.process.index') }}" class="waves-effect waves-block">Manage Process</a></li>

                </ul>
            </li>
            @endif
            @if (env('use_pos',false))
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>POS</span></a>
                <ul class="ml-menu">
                    @if (env('pos_interface',"new")=="old")
                    <li><a href="{{ route('admin.billing.home') }}" class="waves-effect waves-block">POS Interface </a></li>
                    @else
                    <li><a href="{{ route('pos.index') }}" class="waves-effect waves-block">POS Interface</a></li>
                    <li><a href="{{ route('admin.pos.billing.index') }}" class="waves-effect waves-block">Search Bills</a></li>
                    <li><a href="{{ route('admin.pos.billing.print') }}" class="waves-effect waves-block">Reprint Bills</a></li>
                    <li><a href="{{ route('admin.pos.billing.return') }}" class="waves-effect waves-block">Sales Return</a></li>
                    @endif
                </ul>
            </li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>POS Setting</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.counter.day.index') }}" class="waves-effect waves-block">Day Management </a></li>
                    <li><a href="{{ route('admin.counter.home') }}" class="waves-effect waves-block">Counters </a></li>
                    <li><a href="{{ route('admin.offers.index') }}" class="waves-effect waves-block">Offers </a></li>
                    <li><a href="{{ route('admin.point.index') }}" class="waves-effect waves-block">Reward Points </a></li>
                </ul>
            </li>
            {{-- <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Payment Setting</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.bank.index') }}" class="waves-effect waves-block">Banks </a></li>
                    <li><a href="{{ route('admin.gateway.index') }}" class="waves-effect waves-block">Payment Gateways</a></li>
                </ul>
            </li> --}}
            @endif
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span> Setting</span></a>
                <ul class="ml-menu">
                    <li><a href="{{ route('admin.setting.conversion.index') }}" class="waves-effect waves-block">Units </a></li>
                </ul>
            </li>
            <li><a href="{{route('admin.report.home')}}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Reports</span></a></li>
            <li><a href="{{route('admin.accounting.index')}}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Accounting</span></a></li>
            <li><a href="{{route('admin.user.users')}}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Users</span></a></li>
            <li><a href="{{route('admin.backup.index')}}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Backup</span></a></li>

            {{--




            <li><a href="{{route('product.home')}}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Products</span></a></li>
            @endif

            @if(env('tierlevel',1)==1)
               <li><a href="{{route('purchase.home')}}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Purchase Product</span></a></li>
            @endif
            <li><a href="{{ route('report.home') }}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Report</span></a></li>
            <li><a href="{{ route('user.users') }}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Users</span></a></li>
            @if (env('tierlevel',1)==1)
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Manufacture</span></a>
                <ul class="ml-menu">
                   <li><a href="{{ route('manufacture.index') }}" class="waves-effect waves-block"><span>Manufacture</span></a></li>
                   <li><a href="{{ route('manufacture.list') }}" class="waves-effect waves-block"><span>Manufactured List</span></a></li>
                </ul>
            </li>
            <li><a href="{{ url('admin/billing') }}" class="waves-effect waves-block"><i class="zmdi zmdi-shopping-cart"></i><span>Billing</span></a></li>
            @endif
            @if(env('tier',1) == 1)
            <li><a href="javascript:void(0);" class="waves-effect waves-block menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Home Page Setting</span></a>
                <ul class="ml-menu">
                   <li><a href="{{ route('setting.about') }}" class="waves-effect waves-block"><span>AboutUs</span></a></li>
                   <li><a href="{{ route('setting.sliders') }}" class="waves-effect waves-block"><span>Sliders</span></a></li>
                   <li><a href="{{ route('setting.gallery') }}" class="waves-effect waves-block"><span>Gallery</span></a></li>
                </ul>
            </li>
            @endif
            --}}
            <li><a href="{{ route('logout') }}" class="waves-effect waves-block" target="_top"><i class="zmdi zmdi-power"></i><span>Sign Out</span></a></li>
        </ul>
    </div>
</aside>
