<?php

use App\Http\Controllers\Sahakari\HomeController;
use App\Http\Controllers\Sahakari\Member\MemberController;
use App\Models\Sahakari\HomeCntroller;
use Illuminate\Support\Facades\Route;

// Route::name('sahakari.')->group(function(){
//     Route::get('',[HomeController::class,'index'])->name('home');
//     Route::name('members.')->prefix('members')->group(function(){
//         Route::match(['GET','POST'],'',[MemberController::class,'index'])->name('index');

//         Route::match(['GET','POST'],'add',[MemberController::class,'add'])->name('add');
//     });
// });