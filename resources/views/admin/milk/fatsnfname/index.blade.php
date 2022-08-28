@extends('admin.layouts.app')
@section('title', 'Milk Data')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
    <style>
        .selectable {
            cursor: pointer;

        }

        .selectable:hover {
            background: rgb(21, 21, 228);
            color: white;
        }
    </style>
@endsection
@section('head-title', 'Milk Data')
@section('toobar')

@endsection
@section('content')
    <div class="row">
        
        <div class="col-md-12 bg-light">
            <form action="" id="milkData">
                @csrf
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" name="date" id="nepali-datepicker" class="form-control next calender"
                                data-next="center_id" placeholder="Date">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Collection Center</label>
                            <select name="center_id" id="center_id" class="form-control show-tick ms next"
                                data-next="session">
                                <option></option>
                                @foreach (\App\Models\Center::all() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Session</label>
                            <select name="type" id="type" class="form-control show-tick ms next"
                                data-next="mode">
                                <option></option>
                                <option value="0">Morning</option>
                                <option value="1">Evening</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2  mt-4">
                        <input type="button" class="btn btn-primary btn-block next" data-next="u_id"
                            onkeydown="loadData();" onclick="loadData();" id="loaddata" value="Load">
                        {{-- <span>Load</span> --}}
                        <span class="btn btn-danger d-none" onclick="resetData()" id="resetdata"> Reset</span>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5" id="milkDataBody">

                    </div>
                </div>
            </div>
        </div>
    </div>

  

@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
    <script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
    <script>
        // $('#defaultModal').modal('show');
        $('.add-section').hide();
        // $( "#x" ).prop( "disabled", true );
        initTableSearch('sid', 'farmerData', ['name']);

        var saveLock = false;

        function saveData(ele) {
           const id=ele.dataset.id;
           
           const _milk=$('#milkdata-'+id)[0].dataset.value;
           const _snf=$('#snf-'+id)[0].dataset.value;
           const _fat=$('#fat-'+id)[0].dataset.value;
           const milk=$('#milkdata-'+id).val();
           const snf=$('#snf-'+id).val();
           const fat=$('#fat-'+id).val();

           const _milkVal = parseFloat(_milk); 
           const _snfVal = parseFloat(_snf); 
           const _fatVal = parseFloat(_fat); 
           const milkVal = parseFloat(milk); 
           const snfVal = parseFloat(snf); 
           const fatVal = parseFloat(fat); 

           console.log(_milkVal,milkVal,_snfVal,snfVal,_fatVal,fatVal,'adsfsd');
           if(_milkVal!=milkVal || _snfVal!=snfVal || _fatVal!=fatVal){
                console.log("saving dataa");
                axios.post('{{route('admin.milk.milkfatsnfnameSave')}}',{
                    amount:milkVal,
                    id:id,
                    date:$('#nepali-datepicker').val(),
                    type:$('#type').val(),
                    center_id:$('#center_id').val(),
                    snf:snfVal,
                    fat:fatVal,
                })
                .then((res)=>{
                    console.log('Data saved');
                    const data=res.data;
                    const localID=data.id;
                    $('#milkdata-'+localID)[0].dataset.value=data.amount;
                    $('#milkdata-'+localID)[0].value=data.amount;

                    $('#snf-'+localID)[0].dataset.value=data.snf;
                    $('#snf-'+localID)[0].value=data.snf;

                    $('#fat-'+localID)[0].dataset.value=data.fat;
                    $('#fat-'+localID)[0].value=data.fat;
                    calculateTotal();

                })
                .catch((err)=>{
    
                });
            }

        



        }

        function del(no){
            const ele=$('#milk-'+no)[0];
            if(prompt("Please enter yes to delete")=="yes"){
                const data={
                    milkdata_id:ele.dataset.milkdata_id,
                    snffat_id:ele.dataset.snffat_id,
                    type:$('#type').val()
                };
                showProgress("Deleting Milk Data");
                axios.post('{{route('admin.milk.milkfatsnfDel')}}',data)
                .then((res)=>{
                    $('#milk-'+no).remove();
                    hideProgress();
                    calculateTotal();
                })
                .catch((err)=>{
                    hideProgress();
                    
                });
            }
        }

        function disableTopPanel(method) {
            $('#nepali-datepicker').css('pointer-events', method);
            $('#center_id').css('pointer-events', method);
            $('#type').css('pointer-events', method);
            $('#mode').css('pointer-events', method);
            $('#loaddata').css('pointer-events', method);
            if (method == 'none') {
                $('#resetdata').removeClass('d-none').addClass('d-block');
            } else {
                $('#resetdata').removeClass('d-block').addClass('d-none');

            }
        }

        function resetData() {
            $('#milkDataBody').empty();
            //    $('#milkData')[0].reset();
            disableTopPanel('auto');
            $('.add-section').hide();
            $('#loaddata').show();

        }


        function loadData() {
            disableTopPanel('none');
            $('#loaddata').hide();
            if ($('#nepali-datepicker').val() == '' || $('#center_id').val() == '' || $('#session').val() == '') {
                alert('Please fill empty field !');
                if ($('#nepali-datepicker').val() == '') {
                    $('#nepali-datepicker').focus();
                    return false;
                } else if ($('#center_id').val() == '') {
                    $('#center_id').focus();
                    return false;
                } else {
                    $('#session').focus();
                    return false;
                }
                disableTopPanel('auto');
            } else {
                var fdata = new FormData(document.getElementById('milkData'));
                // store.milk
                $('.add-section').show();
                axios({
                        method: 'post',
                        url: '{{ route('admin.milk.milkfatsnfname') }}',
                        data: fdata,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        // console.log(response.data);
                        $('#milkDataBody').empty();
                        $('#milkDataBody').append(response.data);
                        $(".next").keydown(function (event) {
                            var key = event.keyCode ? event.keyCode : event.which;
                            // console.log(key);
                            if (key == "13") {
                                event.preventDefault();

                                id = $(this).data("next");
                                $("#" + id).focus();
                                $("#" + id).select();
                                
                                console.log($(this).hasClass('save'));
                                if($(this).hasClass('save')){
                                    saveData(this);

                                }
                            }
                        });
                        calculateTotal();

                        // $(".save").keydown(function (event) {
                        //     var key = event.keyCode ? event.keyCode : event.which;
                        //     if(key=='13'){
                        //         saveData(this);
                        //     }
                        // });

                        calculateTotal();
                    })
                    .catch(function(response) {
                        //handle error
                        console.log(response);
                    });
            }
        }

        function farmerSelected(id) {
            $('#u_id').val(id);
            $('#u_id').focus();
        }


        function calculateTotal() {
            let milk = 0;

            $('.milkdata').each(function(index, element) {
                const amt = parseFloat(element.value);
                milk += isNaN(amt) ? 0 : amt;
            });

        
            $('#total').html(milk.toString());
        }

        window.onload = function() {

            $('#center_id').focus();
        };

        $('#snf').bind('keydown', 'return', function(e) {
            saveDate(e);
        });

        // load farmer data by center id

    </script>
@endsection
