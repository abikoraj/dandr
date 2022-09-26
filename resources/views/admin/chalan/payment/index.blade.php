@foreach ($payments as $payment)
   @include('admin.chalan.payment.single',['payment'=>$payment])
@endforeach
