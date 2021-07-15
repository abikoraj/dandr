var tablecount=0;
    
function initTableSearch(sid,id,data=[]){
    tablecount+=1;
    elements=document.querySelectorAll("#"+id +" tr");
    console.log(elements);
    console.log(data);
    $("#"+sid).keyup(function(){
        parameter=$('#'+sid).val().toLowerCase();
        console.log(parameter);
        elements=document.querySelectorAll("#"+id +" tr");
        elements.forEach(element => {
            done=false;
            data.forEach(d => {
                s_para=(""+$(element).data(d)+"").toLowerCase();
                if(!done){
                    if(s_para.includes(parameter)){
                        $(element).show();
                        done=true;
                    }else{
                        $(element).hide();
                    }
                }
            });
        });
    });
    
}