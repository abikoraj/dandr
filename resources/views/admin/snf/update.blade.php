<div class="modal fade" id="snf_change" tabindex="-1" role="dialog" aria-labelledby="snf_changemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Fat Snf</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="snf_title">

            </div>
            <hr>
            <div class="row">
                <input type="hidden"  id="update_snffat_id">
                <div class="col-md-3">
                    <label for="fat">Fat</label>
                    <input type="number" name="update_fat" id="update_fat" class="form-control" step="0.01" >
                </div>

                <div class="col-md-3">
                    <label for="snf">Snf</label>
                    <input type="number" name="update_snf" id="update_snf" class="form-control" step="0.01" >
                </div>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveSnfFatUpdate();">Save changes</button>
        </div>
      </div>
    </div>
  </div>

<script>
    snflock=false;
    function showSnfFatUpdateNew(id){
        const txt = $('#snffat-' + id).html();
        const snfdata = JSON.parse(txt);
        console.log(txt, snfdata);
        $('#update_snf').val(snfdata.snf);
        $('#update_snffat_id').val(snfdata.id);
        $('#update_fat').val(snfdata.fat);
        $('#snf_change').modal('show');
    }
    function showSnfFatUpdate(id){
        snfdata=$(ele).data('snffat');
        $('#update_snf').val(snfdata.snf);
        $('#update_snffat_id').val(snfdata.id);
        $('#update_fat').val(snfdata.fat);
        $('#snf_change').modal('show');
    }

    function delSnfFat(ele){

        if(confirm("Do You Want TO delete Snf Record??"))
        {
            // console.log(ele.dataset.)
            d={id:$(ele).data('snffat').id};
            axios.post('{{route("admin.snf-fat.delete")}}',d)
            .then(function(reponse){
                snfDeleted(d);

            })
            .catch(function(err){
                showNotification('bg-danger', 'You hove no authority!');
            });
        }

    }

    function saveSnfFatUpdate(){
        if(!snflock){
            snflock=true;
            d={
                snf:$('#update_snf').val(),
                fat:$('#update_fat').val(),
                id: $('#update_snffat_id').val()
            }
        axios.post('{{route("admin.snf-fat.update")}}',d)
        .then(function(reponse){
            snflock=false;
            $('#snf_change').modal('hide');
            console.log(d);
            snfUpdated(d);

        })
        .catch(function(err){

            snflock=false;
            showNotification('bg-danger', 'You hove no authority!');

        })
        }
    }
</script>
