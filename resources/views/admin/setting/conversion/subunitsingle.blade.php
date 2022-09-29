
<div class=" pt-2 pl-2  pb-0  conversion sub" id="conversion-{{$conversion->id}}">

    <div class="mb-0" >
        <form action="{{route('admin.setting.conversion.update.sub')}}" method="post" id="updateSubUnitForm-{{$conversion->id}}">
        @csrf
        <input type="hidden" name="id" value="{{$conversion->id}}" >
        <div class="row">
            <div class="col-md-4 ">

                <input type="text" value="{{$conversion->name}}" name="name" id="name-{{$conversion->id}}" class="form-control">

            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-6">
                        <input class="form-control" type="number" value="{{(float)$conversion->local}}" name="local" id="local-{{$conversion->id}}">
                    </div>
                    <div class="col-6">
                        <input class="form-control" type="number" value="{{(float)$conversion->main}}" name="main" id="main-{{$conversion->id}}">
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-right">
                {{-- <span class="btn btn-success" onclick="initAddSubUnit({{$conversion->id}},'{{$conversion->name}}')">
                    <i class="zmdi zmdi-plus"></i>
                </span> --}}
                <span class="btn btn-primary" onclick="updateSubUnitData({{$conversion->id}})">
                    <i class="zmdi zmdi-edit"></i>
                </span>
                @if ($conversion->used==0)
                    <span class="btn btn-danger" onclick="deleteData({{$conversion->id}})">
                        <i class="zmdi zmdi-hc-fw"></i>
                    </span>
                @endif
            </div>
        </div>
        </form>
    </div>
    <div id="conversion-{{$conversion->id}}-child" class="pt-2">
        @if (isset($old))
            @foreach ($conversions->where('parent_id',$conversion->id) as $item)
                @include('admin.setting.conversion.subunitsingle',['conversion'=>$item,'baseUnit'=>$conversion->name,'old'=>true])
            @endforeach
        @endif
    </div>
</div>
