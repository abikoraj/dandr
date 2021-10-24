@extends('admin.layouts.app')
@section('title','Database Backup')
@section('head-title','Database Backup')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect mr-2" onclick="newBackup()">Create Backup</button>
@endsection
@section('content')
    <div id="backup-main">
        <table class="table table-bordered">
            <tr>
                <th>Backup</th>
                <th>created</th>
                <th>Size</th>
                <th>Action</th>
            </tr>
            <tbody id="backup-data">
                @foreach ($files as $file)
                    <tr>
                        <td>
                            {{$file['filename']}}
                        </td>
                        <td>
                            {{$file['time']}}
                        </td>
                        <td>
                            {{$file['size']}} MB
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger waves-effect mr-2" onclick="del('{{$file['filename']}}')">Delete</button>
                            <a href="{{asset('backup/'.$file['basename'])}}" class="btn btn-success waves-effect mr-2"  download="{{$file['basename']}}">Download</a>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('js')
<script>

    lock=false;
    function newBackup(){
        if(lock){
            return;
        }
        if(prompt('Enter YES TO BACKUP').toLowerCase()=='yes'){
            showProgress('Creating Backup');
            lock=true;
            axios.get('{{route('admin.backup.create')}}')
            .then((res)=>{
                if(res.data.status){
                    location.reload();
                }else{
                    alert(res.data.err);
                }
                lock=false;
                hideProgress();
            })
            .catch((err)=>{
                lock=false;
                alert('some error occured please try again')
                console.log(err);
                hideProgress();

            });
        }
    }

    function del(file){
        if(lock){
            return;
        }
        if(prompt('Enter YES TO BACKUP').toLowerCase()=='yes'){
            showProgress('Deleting Backup');
            lock=true;
            axios.post('{{route('admin.backup.del')}}',{"file":file})
            .then((res)=>{
                if(res.data.status){
                    location.reload();
                }else{
                    alert(res.data.err);
                }
                lock=false;
                hideProgress();
            })
            .catch((err)=>{
                lock=false;
                alert('some error occured please try again')
                console.log(err);
                hideProgress();

            });
        }
    }
</script>
@endsection
