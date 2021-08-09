<datalist id="datalist-member">
    @foreach ($members as $member)
        <option value="{{$member->member_no}}">{{$member->name}}</option>
    @endforeach
</datalist>