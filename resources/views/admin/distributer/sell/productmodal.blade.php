
<div class="modal fade" id="itemmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg " role="document" >
    <div class="modal-content">
        <div class="modal-header pb-3">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                style="top:5px;font-size:3rem;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body pt-1">
            <input type="text" id="isid" placeholder="Search" style="width: 134px;margin-bottom:5px;">
            <table class="table">
                <tr>
                    <th>
                        No / Barcode
                    </th>
                    <th>
                        Name
                    </th>
                </tr>
                <tbody id="products">
                    @if (!env('large',false))
                        @foreach(\App\Models\Item::where('disonly',1)->select('dis_price','dis_number','number','sell_price','title')->get() as $i)
                        <tr id="item-{{ $i->dis_number??$i->number }}" data-rate="{{$i->dis_price??$i->sell_price}}" data-number="{{$i->dis_number??$i->number }}" data-name="{{ $i->title }}" onclick="itemSelected(this.dataset);">
                            <td class="p-1"><span style="cursor: pointer;">{{ $i->dis_number??$i->number }}</span></td>
                            <td class="p-1"><span style="cursor: pointer;">{{ $i->title }}</span></td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@if (env('large',false))
@section('js3')
    <script>
        function productRender(){
            html="";

            this.data.items.forEach($i => {
                html+= '<tr id="item-'+ $i.dis_number??$i.number +'" data-rate="'+$i.dis_price??$i.sell_price+'" data-number="'+$i.dis_number??$i.number +'" data-name="'+ $i.title +'" onclick="itemSelected(this.dataset);">'+
                            '<td class="p-1"><span style="cursor: pointer;">'+ $i.dis_number??$i.number +'</span></td>'+
                           ' <td class="p-1"><span style="cursor: pointer;">'+ $i.title +'</span></td>'+
                        '</tr>';
            });
            console.log(html, this.data);
            return html;
        }
        $('#isid').search({
            renderfunc:"productRender",
            rendercustom:true,
            renderele:"#products",
            url:'{{route('admin.item.product')}}'
        });
    </script>
@endsection
@endif