@extends('admin.layouts.app')
@section('title', 'Import Supplier')
@section('head-title', 'Import Supplier')
@section('toobar')

@endsection
@section('content')
<form action="{{route('admin.import.supplier')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <input type="file" name="csv" accept=".csv" id="csv" class="form-control mb-2" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" onclick="o">Load Data</button>
        </div>
    </div>
</form>

@endsection
@section('js')
    <script>
        var data=[];
        var fileInput = document.getElementById('csv');
        console.log(fileInput);

        function LoadCsv() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    const text = e.target.result;
                    data = csvToArray(text);
                    console.log(data);
                };
                reader.readAsText(file);
            }else{
                alert("Please load a data file");
            }
        }

        function csvToArray(str, delimiter = ",") {
            const headers = str.slice(0, str.indexOf("\n")).split(delimiter);
            const rows = str.slice(str.indexOf("\n") + 1).split("\n");
            const arr = rows.map(function(row) {
                const values = row.split(delimiter);
                const el = headers.reduce(function(object, header, index) {
                    const value=values[index];
                    if(value!=undefined){

                        object[header] = value.trim();
                    }
                    return object;
                }, {});
                return el;
            });
            return arr;
        }
    </script>
@endsection
