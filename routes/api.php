<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Detail_TransaksiController;


Route::post('login', [UserController::class, 'login']);
Route::post('user', [UserController::class, 'store']);

Route::group(['middleware' => ['jwt.verify:admin,kasir,owner']], function() {
    Route::get('login/check', [UserController::class, 'loginCheck']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('getuser', [UserController::class, 'getUser']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::post('report', [TransaksiController::class, 'report']);
    
});


//Route khusus admin
Route::group(['middleware' => ['jwt.verify:admin']], function() {
    
    //OUTLET
    Route::get('outlet', [OutletController::class, 'getAll']);
    Route::get('outlet/{id}', [OutletController::class, 'getById']);
    Route::post('outlet', [OutletController::class, 'store']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    
    //PAKET
    Route::get('paket', [PaketController::class, 'getAll']);
    Route::get('paket/{id_paket}', [PaketController::class, 'getById']);
    Route::post('paket', [PaketController::class, 'store']);
    Route::put('paket/{id_paket}', [PaketController::class, 'update']);
    Route::delete('paket/{id_paket}', [PaketController::class, 'delete']);
    
    //USER
    
    Route::get('user', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'delete']);

});


//Route khusus admin & kasir
Route::group(['middleware' => ['jwt.verify:admin,kasir']], function() {
    //MEMBER
    Route::post('member', [MemberController::class, 'store']);
    Route::get('member', [MemberController::class, 'getAll']);
    Route::get('member/{id_member}', [MemberController::class, 'getById']);
    Route::put('member/{id_member}', [MemberController::class, 'update']);
    Route::delete('member/{id_member}', [MemberController::class, 'delete']);
    Route::get('get_member/{id_member}',[MemberController::class, 'cari_data']);
    
    //TRANSAKSI
    Route::post('transaksi', [TransaksiController::class, 'store']);
    Route::get('get_transaksi/{id_transaksi}',[TransaksiController::class, 'cari_data']);
    Route::get('transaksi/{id_transaksi}', [TransaksiController::class, 'getById']);
    Route::get('transaksi/member/{id_transaksi}', [TransaksiController::class, 'getMemberID']);
    Route::get('transaksi', [TransaksiController::class, 'getAll']);

    //DETAIL TRANSAKSI
    Route::post('transaksi/detail/tambah', [Detail_TransaksiController::class, 'store']);
    Route::get('transaksi/detail/{id_transaksi}', [Detail_TransaksiController::class, 'getById']);
    Route::post('transaksi/status/{id_transaksi}', [TransaksiController::class, 'changeStatus']);
    Route::post('transaksi/bayar/{id_transaksi}', [TransaksiController::class, 'bayar']);
    Route::get('transaksi/total/{id_detail_transaksi}', [Detail_TransaksiController::class, 'getTotal']);    
});
