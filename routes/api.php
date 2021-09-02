<?php

use App\Models\PosBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('bill/{id}', function ($id) {
    // if($re)
    $bill=PosBill::find($id);
    $bill->billitems;
    return response()->json($bill);
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
$issue=Library::join('students','students.id','=','libraries.student_id')
->join('books','books.id','=','libraries.book_id')->get();

*/
