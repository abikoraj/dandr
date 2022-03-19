
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
                @include('admin.item.minilist')
            </div>
        </div>
    </div>
</div>

@if (env('large',false))
@section('js3')
    <script>
        function itemRender(){
            html="";

            this.data.items.forEach(item => {
                html+= '<tr id="item-'+dotSanitize(item.number)+'" data-rate="'+item.sell_price+'" data-number="'+item.number+'" data-name="'+item.title+'" onclick="itemSelected(this.dataset);">'+
                    '<td class="p-1"><span style="cursor: pointer;">'+item.number+'</span></td>'+
                    '<td class="p-1"><span style="cursor: pointer;">'+item.title +'</span></td>'+
                '</tr>';
            });
            console.log(html, this.data);
            return html;
        }
        $('#isid').search({
            rendercustom:true,
            renderele:"#itemData",
            url:'{{route('admin.item.index')}}'
        });
    </script>
@endsection
@endif