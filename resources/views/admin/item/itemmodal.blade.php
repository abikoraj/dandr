
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
