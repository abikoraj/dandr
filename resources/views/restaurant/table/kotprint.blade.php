<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table{
            width:100%;
        }
        tr,th,td{
            padding: 5px;
            border: 1px solid black;
        }
        @media screen {
            button{
                padding:10px;
                color:white;
                background: #007ACC;
                border:none;
            }
        }
        @media print  {
            button{
                display: none;
            }
        }
    </style>
    <script>
        var currentdate = new Date(); 
        const datetime= currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() ;
    </script>
</head>
<body>
    <h5 >
        <div>

            Table : {{$table->name}}
        </div>
        <div>
            <script>document.write(datetime)</script>
        </div>
    </h5>
    <table>
        <tr>
            <th>
                Order No
            </th>
            <th>
                Item
            </th>
            <th>
                Qty
            </th>
        </tr>
        @foreach ($localData as $datas)
            <tr>
                @foreach ($datas as $data)
                    <td>{{$data}}</td>
                @endforeach
            </tr>
        @endforeach
     
    </table>
    <button onclick="window.print();window.close()">
        Confirm
    </button>
   
</body>
</html>