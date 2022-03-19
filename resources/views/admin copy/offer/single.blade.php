<hr class="my-1">
<form method="POST" action="{{route('admin.offers.update')}}">
    @csrf
    
    <input type="hidden" name="id" value="{{$offer->id}}">
    <div class="row">
        <div class="col-md-2">
            @php
                $in=0;
            @endphp
            @if ($offer->hasItems())
                
            <span>
                {{getOffers()[$offer->type]}}
            </span>
            @else
            <select type="text" name="type" id="type" class="form-control ms" >
                @foreach (getOffers() as $item)
                    <option {{$offer->type==$in?"selected":""}} value="{{$in++}}">{{$item}}</option>
                @endforeach
            </select>
            @endif
        </div>
        <div class="col-md-3">
            
            <input type="text" name="name"  class="form-control" value="{{$offer->name}}" >
        </div>
        <div class="col-md-2">
            
            <input type="text" name="start_date" id="start_date_{{$offer->id}}"  class="form-control calender"  value="{{_nepalidate($offer->start_date)}}" >
        </div>
        <div class="col-md-2">
            <input type="text" name="end_date"  id="end_date_{{$offer->id}}" class="form-control calender"  value="{{_nepalidate($offer->end_date)}}" >
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Update</button>
            <span>

                <div class="dropdown d-inline">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      #
                    </button>
                    <div class="dropdown-menu p-2" aria-labelledby="dropdownMenuButton">
                            <a  href="{{route('admin.offers.detail',['offer'=>$offer])}}" target="_blank">Details</a>
                            <hr>
                        @if ($offer->active==0)
                            <a  href="{{route('admin.offers.del',['offer'=>$offer])}}">Del</a>
                            <br>
                            <a  href="{{route('admin.offers.activate',['offer'=>$offer])}}">Activate</a>
                        @else
                            <a  href="{{route('admin.offers.del',['offer'=>$offer])}}">Deactivate</a>
    
                        @endif
                    </div>
                </div>
            </span>
            {{-- <a class="btn btn-danger" href="{{route('admin.offers.del',['offer'=>$offer])}}">Del</a> --}}
            
        </div>
    </div>
</form>