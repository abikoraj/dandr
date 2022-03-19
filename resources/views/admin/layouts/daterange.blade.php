
<div class="row">
    <div class="col-md-3 ">
        <label for="type">
            Report Duration
        </label>
        <select name="type" id="type" onchange="manageDisplay(this)" class="form-control show-tick ms ">
            <option value="-1" {{$sel_range==-1?'selected':''}}>All</option>
            <option value="0" {{$sel_range==0?'selected':''}}>Session</option>
            <option value="1" {{$sel_range==1?'selected':''}}>Daily</option>
            <option value="2" {{$sel_range==2?'selected':''}}>Weekly</option>
            <option value="3" {{$sel_range==3?'selected':''}}>Monthly</option>
            <option value="4" {{$sel_range==4?'selected':''}}>Yearly</option>
            <option value="5" {{$sel_range==5?'selected':''}}>Custom</option>
            @if (env('use_pos',false))

                <option value="6" {{$sel_range==6?'selected':''}}>Fiscal Year</option>
            @endif
        </select>

    </div>
    <div class="col-md-3 ct ct-0 ct-2 ct-3 ct-4 d-none">
        <label for="date">Year</label>
        <select name="year" id="year" class="form-control show-tick ms  load-year">
        </select>
    </div>
    <div class="col-md-3 ct ct-0  ct-2 ct-3 d-none">
        <label for="date">Month</label>
        <select name="month" id="month" class="form-control show-tick ms  load-month">
        </select>
    </div>
    <div class="col-md-3 ct ct-2 d-none">
        <label for="week">Week</label>
        <select name="week" id="week" class="form-control show-tick ms  load-week">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <div class="col-md-3 ct ct-0 d-none">
        <label for="date">Session</label>
        <select name="session" id="session" class="form-control show-tick ms  load-session">
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <div class="col-md-3 ct ct-1 ct-5 d-none">
        <label for="Date1">Date1</label>
        <input type="text" id="date1" class="form-control calender">
    </div>
    <div class="col-md-3 ct ct-5 d-none">
        <label for="date2">Date2</label>
        <input type="text" id="date2" class="form-control calender">
    </div>
    @if (env('use_pos',false))
    @php
        $ps=App\Models\PosSetting::first();

    @endphp
    <div class="col-md-3 ct ct-6 d-none">
        <label for="fy">Fiscal Year {{$ps->date}}</label>

        <select name="fiscalyear" id="fiscalyear" class="form-control show-tick ms ">
            @foreach (App\Models\FiscalYear::all() as $fy)
                <option value="{{$fy->id}}" {{$ps!=null?($fy->isCurrent($ps->date)?"selected":""):""}}>{{$fy->name}}</option>
            @endforeach
        </select>
    </div>
    @endif
</div>
