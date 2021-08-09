<div class="row">
    @foreach ($data['data'] as $key=>$d)
        <div class="col-md={{$d['r']}}">
            <div class="form-group">
                <label for="{{$data['p']}}{{$key}}">
                    {{$d['ti']}}
                </label>
                @if ($d['t']=='text')
                    <input type="text" name="{{$data['p']}}{{$key}}" id="{{$data['p']}}{{$key}}" class="form-control {{isset($d['ec'])?$d['ec']:''}}"     placeholder="{{isset($d['p'])?$d['p']:''}}">
                @endif
            </div>
        </div>
        

    @endforeach
</div>
<button class="validate()">vaddaate</button>
@section('js1')
    <script>
        function validate(){
            
        }
    </script>
@endsection
@section('mask')
<script>
    //mask is on
    $(document).ready(function(){
        @foreach ($data['data'] as $key=>$d)
        @if(isset($d['mask']))
            $('#{{$data['p']}}{{$key}}').mask('{{$d['mask']}}');
        @endif
        @endforeach
    });
</script>

@endsection