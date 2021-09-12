<div class="row">
    <div class="col-md-6">
        <div >
            @if (env('large',false))
            <div class="row">
                <div class="col-5 pr-1">
                    <input type="text" class="form-control" id="keyword" placeholder="Search Items">
                </div>
                <div class="col-5 px-1">
                    <input type="text" class="form-control" id="category" placeholder="Search Category">
                </div>
                <div class="col-2 pl-1">
                    <button class="btn btn-primary">Load</button>
                </div>
            </div>
            @endif
            @if (env('large',false))
            @else
            <div class="items">
                <table class="table table-bordered table-striped ">
                    <tr>
                        <th>
                            Name
                        </th>
                        <td></td>
                    </tr>
                    @foreach (App\Models\Item::select('id','title','number')->get() as $item)
                        @include('admin.offer.detail.singleitem',['item'=>$item])
                    @endforeach
                </table>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
@section('js2')
    <script>
    </script>
@endsection