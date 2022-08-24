<style>
    .b-1 {
        border: 1px solid rgba(0, 0, 0, 1);
    }

    .searchItemHolder{
        max-height: 120px;
        padding: 5px;
        overflow-y: auto;
        position: absolute;
        top: 75px;
        width: 94%;
        /* height: 300px; */
        background: white;
        z-index: 1;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 20%);
        display: none;
    }

    .searchItemHolder.focus{
        display: block;
    }


    .sideHolder {
        display: none;
        position: fixed;
        bottom: 0px;
        right: 0px;
        top: 0px;
        width: 500px;
        background: white;
        border: 1px solid rgb(134, 134, 134);
        overflow-y:auto; 
        overflow-x:hidden;
    }
    .tables{
        cursor: pointer;
    }
</style>