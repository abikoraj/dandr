@extends('admin.layouts.app')
@section('title', 'Items')
@section('head-title')
<a href="{{route('admin.item.index')}}">Items</a> / {{$item->title}} / Categories
@endsection

@section('toobar')
@if (auth_has_per('03.01'))
    <button type="button" class="btn btn-primary waves-effect m-r-20" onclick="win.showGet('Add New Item Category','{{route('admin.item.categories.add',['id'=>$item->id])}}')" >Create
        New category</button>
@endif
@endsection
@section('content')
<div id="cats">

    @foreach ($cats as $category)
            @include('admin.item.category.single',['category'=>$category])
    @endforeach
</div>
@endsection
@section('js')
        <script>
            function save(ele,e){
                showProgress('Adding Item Category');
                e.preventDefault();
                axios.post(ele.action,new FormData(ele))
                .then((res)=>{
                    $('#cats').append(res.data);
                    showNotification('bg-success','Item category added sucessfully.');
                    win.hide();
                    hideProgress();
                    // window.location.reload();
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger','Item category Cannot be added. Please try again.');
                })

            }

            function update(ele,e){
                showProgress('Updating Item Category');
                e.preventDefault();
                axios.post(ele.action,new FormData(ele))
                .then((res)=>{
                    showNotification('bg-success','Item category updated sucessfully.');
                    hideProgress();
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger','Item category Cannot be updated. Please try again.');
                })

            }

            function del(url){
                if(prompt('Enter yes to continue','')){
                    axios.post(url,{})
                    .then((res)=>{
                        if(res.data.status){
                            showNotification('bg-success','Item category deleted sucessfully.');
                            $('#category-'+res.data.id).remove();
                        }else{
                            showNotification('bg-danger',res.data.message);

                        }
                    })
                    .catch((err)=>{
                        showNotification('bg-danger','Item category Cannot be deleted. Please try again.');

                    })
                }
            }
        </script>
@endsection
