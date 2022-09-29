<script >
    var win={
        id:"#window",
        shown:false,
        show:(title,text)=>{
            $('#wt').html(title);
            $('#wc').html(text);
            $(win.id).addClass('shown');
            win.shown=true;
            console.log(win);
        },
        showGet:(title,URL,callback)=>{
            $('#wt').html(title);
            axios.get(URL)
            .then((res)=>{
                $('#wc').html(res.data);
                $(win.id).addClass('shown');
                win.shown=true;
                if(callback!=undefined){
                    callback();
                }
            });

            console.log(win);
        },
        showPost:(title,URL,data,callback)=>{
            $('#wt').html(title);
            data._token="{{ csrf_token() }}";
            axios.post(URL,data)
            .then((res)=>{
                $('#wc').html(res.data);
                $(win.id).addClass('shown');
                win.shown=true;
                if(callback!=undefined){
                    callback();
                }
            });

            console.log(win);
        },
        hide:()=>{
            $('#wc').html("");
            $(win.id).removeClass('shown');
            win.shown=false;
            console.log(win);
        }
    };
    lock=false;
    var ledgerCallBack;
    function initEditLedger(title, id,callback){
        ledgerCallBack=callback;
        win.showPost("Edit Ledger - "+title,"{{route('admin.ledger.edit')}}",{"id":id},addEXPayHandle);

    }

    function s_calculate(){
        $('#s_amount').val($('#s_rate').val()*$('#s_qty').val());
    }

    function updateLedger(){
        if(!lock){
            if(!expayVerifyData()){
                return;
            }
            if(confirm('Do You Want TO Update Ledger ')){

                data=new FormData(document.getElementById('xp_1'));
                lock=true;
                axios.post("{{route('admin.ledger.update')}}",data)
                .then(function(response){
                    console.log(response);
                    lock=false;
                    win.hide();
                    if(ledgerCallBack!=undefined){
                        ledgerCallBack();
                    }else{
                        loadData();
                    }

                })
                .catch(function(err){
                    lock=false;
                    console.log(err);
                    alert('Ledger Cannot be Updated');
                })
            }
        }
    }

    function deleteLedger(id,callback=null){
        if(!lock){
            if(confirm('Do You Want To Delete Ledger ')){

                data={"id":id};
                lock=true;
                axios.post("{{route('admin.ledger.del')}}",data)
                .then(function(response){
                    lock=false;
                    if (callback && typeof(callback) === "function") {
                        callback(id);
                    }
                })
                .catch(function(err){
                    lock=false;
                    alert('Ledger Cannot be Updated');
                })
            }
        }
    }


    function errAlert($err,$msg=""){
        hideProgress();
        $msg+=" "+ ($err.response?$err.response.data.message:'Please Try again');
        showNotification('bg-danger',$msg)
    }
    function successAlert($msg){
        hideProgress();
        showNotification('bg-success',$msg)
    }

    function yes(msg=''){
        if(msg==''){
            msg='Enter yes to continue';
        }
        return prompt(msg)=='yes';
    }
</script>
