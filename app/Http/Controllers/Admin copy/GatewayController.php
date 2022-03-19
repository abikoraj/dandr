<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function index(){
        return view('admin.gateway.index',['gateways'=>PaymentGateway::all()]);
    }
    public function add(Request $request){
        $gateway=new PaymentGateway();
        $gateway->name=$request->name;
        $gateway->private_key=$request->private_key;
        $gateway->public_key=$request->public_key;
        $gateway->api_key=$request->api_key;
        $gateway->save();
        // dd($request->all());
        return view('admin.gateway.single',compact('gateway'));
    }
    public function update(Request $request){
        $gateway= PaymentGateway::find($request->id);
        $gateway->private_key=$request->private_key;
        $gateway->public_key=$request->public_key;
        $gateway->api_key=$request->api_key;
        $gateway->save();
        return redirect()->back();
    }
    public function delete(Request $request){
        $gateway= PaymentGateway::find($request->id);
        $gateway->delete();
        return response('ok');
    }
}
