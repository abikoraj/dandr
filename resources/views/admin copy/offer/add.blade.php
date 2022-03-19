<form method="POST" action="{{route('admin.offers.add')}}">
    @csrf
    <div class="row">
        <div class="col-md-2">
            <label for="type">Type</label>
            @php
                $in=0;
            @endphp
            <select type="text" name="type" id="type" class="form-control ms" >
                @foreach (getOffers() as $item)
                    <option value="{{$in++}}">{{$item}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" >
        </div>
        <div class="col-md-2">
            <label for="start_date">Start Date</label>
            <input type="text" name="start_date" id="start_date" class="form-control calender" >
        </div>
        <div class="col-md-2">
            <label for="end_date">End Date</label>
            <input type="text" name="end_date" id="end_date" class="form-control calender" >
        </div>
        <div class="col-md-2 pt-4">
            <button class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
<hr>