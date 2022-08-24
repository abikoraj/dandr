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


    .sideBar-{{csrf_token()}}{
        display: none;
        position: fixed;
        bottom: 0px;
        right: 0px;
        top: 0px;
        left:0px;
        background: rgba(0, 0, 0, 0.1);
        border: 1px solid rgb(134, 134, 134);
        overflow-y:auto; 
        overflow-x:hidden;
    }
    .sideHolder-{{csrf_token()}} {
        position: fixed;
        bottom: 0px;
        right: 0px;
        top: 0px;
        width: 500px;
        background: white;
        border: 1px solid rgb(134, 134, 134,0.2);
        overflow-y:auto; 
        overflow-x:hidden;
    }
    .orders{
        height: 100px;
        overflow-y: scroll;
        overflow-x: hidden; 
        font-size: 13px;
    }
    .tables{
        cursor: pointer;
    }

    /* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>