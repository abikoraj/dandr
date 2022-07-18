@php
    $data=modalMenuData();
@endphp
<div class="row">
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.farmer.list')}}">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text">
            Farmers ({{ $data->farmers }})
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.farmer.advance')}}">
        <span class="icon">
            <i class="zmdi zmdi-money"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Farmer <br> Advance
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.center.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-pin"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Collection <br> Center
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.milk.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-dns"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Milk <br> Collection
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.snf-fat.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-dns"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Snf & Fats
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.item.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-view-module"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Items
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.sell.item.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-view-compact"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Sell Item
        </span>
    </div>
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.manufacture.product.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-drink"></i>

        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Products ( {{\App\Models\ManufacturedProduct::count()}} )
        </span>
    </div>
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.manufacture.process.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-memory"></i>


        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Production Process ( {{\App\Models\ManufactureProcess::where('stage',2)->count()}} )
        </span>
    </div>
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.distributer.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-truck"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Distributors ( {{\App\Models\Distributer::count()}} )
        </span>
    </div>
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.distributer.sell')}}">
        <span class="icon">
            <i class="zmdi zmdi-assignment"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Distributors <br> Sell
        </span>
    </div>
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.expense.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-balance-wallet"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Expenses
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.supplier.index')}}">
        <span class="icon">
            <i class="zmdi zmdi-accounts"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Suppliers ({{ \App\Models\Supplier::count()}})
        </span>
    </div>

    <div class="col-md-2 section section_1 href" data-target="{{route('admin.supplier.bill')}}">
        <span class="icon">
            <i class="zmdi zmdi-book"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Supplier <br> Bills
        </span>
    </div>
   
    <div class="col-md-2 section section_1 href" data-target="{{route('admin.report.home')}}">
        <span class="icon">
            <i class="zmdi zmdi-markunread-mailbox"></i>
        </span>
        <span class="divider"></span>
        <span class="text text-center">
            Reports
        </span>
    </div>
</div>
