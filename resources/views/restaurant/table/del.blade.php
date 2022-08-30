<script>
    function initDel(id,table_id){
        const password=prompt("Please enter your phone no");
        axios.post('{{route('restaurant.kotDel')}}',{
            id:id,
            table_id:table_id,
            password:password
        })
        .then((res)=>{
            const data=res.data;
            const index=currentData.findIndex(o=>o.table_id==data.table_id);
            localData=currentData[index];
            if(data.nodel){
                currentData[index].items=JSON.parse(data.data);
            }else{
                currentData.splice(index,1);
            }
            save();
            renderSide(2);
            console.log(res);
        })
        .catch((err)=>{
            console.log(err);
        });
    }
</script>