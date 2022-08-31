@extends('admin.layouts.app')
@section('title', 'Connect Device')
@section('head-title', 'Connect Device')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <label for="pin">Pin</label>
            <input type="text" name="pin" id="pin" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="col-md-4 pt-4">
            <button class="btn btn-primary w-100" onclick="generate();"> Generate Link</button>
        </div>
    </div>
    <div class="p-5 d-flex justify-content-center">
        <div id="qrcode"></div>
    </div>
@endsection
@section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function generate() {
            const pin = $('#pin').val();
            if (pin.length != 6) {
                alert('Pin length sould be 6');
                return;
            }
            $('#qrcode').html('');
            axios.post("{{ route('admin.barcode.index') }}", {
                    pin: pin,
                    password: $('#password').val()
                })
                .then((res) => {
                    data = res.data;
                    if (data.status) {
                        var qrcode = new QRCode(document.getElementById("qrcode"), {
                            text: data.data,
                            width: 128,
                            height: 128,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                        // if(qrcode==undefined){

                        // }else{
                        //     qrcode.clear();
                        //     qrcode.makeCode(data.data);
                        // }
                    } else {
                        alert(data.message);
                    }
                })
                .catch((err) => {
                    alert('Some error occured please try again');
                })
        }
    </script>
@endsection
