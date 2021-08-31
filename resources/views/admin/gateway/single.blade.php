<div class="col-md-4 " id="gateway-{{$gateway->id}}">
    <form class="shadow mb-3 p-2" id="editgateway-{{$gateway->id}}" method="POST"  action="{{route('admin.gateway.update')}}">
        @csrf
        <input type="hidden" name="id" value="{{$gateway->id}}">
        <div class="row">
            <div class="col-lg-12">
                <label for="name">Name</label>
                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control next" data-next="public_key" placeholder="Enter Bank Name" required value="{{$gateway->name}}">
                </div>
            </div>
            <div class="col-lg-12">
                <label for="public_key">Public Key</label>
                <div class="form-group">
                    <input type="text" id="public_key" name="public_key" class="form-control next" data-next="private_key"   value="{{$gateway->public_key}}">
                </div>
            </div>
            <div class="col-lg-12">
                <label for="private_key">Private Key</label>
                <div class="form-group">
                    <input type="text" id="private_key" name="private_key" class="form-control next" data-next="api_key"   value="{{$gateway->private_key}}">
                </div>
            </div>
            <div class="col-lg-12">
                <label for="api_key">Api Key</label>
                <div class="form-group">
                    <input type="text" id="api_key" name="api_key" class="form-control "   value="{{$gateway->api_key}}">
                </div>
            </div>

                  
            <div class="col-md-6">
                <button class="btn btn-primary w-100">Update</button>
            </div>
            <div class="col-md-6">
                <span class="btn btn-danger w-100" onclick="del({{$gateway->id}})">Delete</span>
            </div>
        </div>
    </form>
</div>