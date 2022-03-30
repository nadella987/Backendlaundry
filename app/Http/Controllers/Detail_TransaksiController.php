<?php

namespace App\Http\Controllers;

use App\Models\Detail_Transaksi;
use App\Models\Paket;
use App\Models\Transaksi;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JWTAuth;


class Detail_TransaksiController extends Controller
{
    public $user;
    public $response;
    public function __construct()
    {
        $this->response = new ResponseHelper();
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required',
            'id_paket' => 'required',
            'qty' => 'required',
            // 'subtotal' => 'required',
        ]);

        if($validator->fails()) {
            return $this->response->errorResponse($validator->fails());
        }
        
        $detail = new Detail_Transaksi();
        $detail->id_transaksi = $request->id_transaksi;
        $detail->id_paket = $request->id_paket;
        $detail->subtotal = $request->subtotal;

        //GET HARGA PAKET
        $paket = Paket::where('id_paket', '=', $detail->id_paket)->first();
        $harga = $paket->harga;

        $detail->qty = $request->qty;
        $detail->subtotal = $detail->qty * $harga;
        $detail->save();

        $transaksi = Transaksi::find($request->id_transaksi);
        $total_transaksi = Detail_Transaksi::where('id_transaksi', $detail->id_transaksi)->sum('subtotal');
        $transaksi->update(['total' => $total_transaksi]);

        $data = Detail_Transaksi::where('id_detail_transaksi', '=', $detail->id_detail_transaksi)->first();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tambah detil transaksi', 'data' => $data]);

    }

    public function getById($id)
    {
        //untuk ambil detil dari transaksi tertentu

        $data = DB::table('detail_transaksi')->join('paket', 'detail_transaksi.id_paket', 'paket.id_paket')
                                            ->select('detail_transaksi.*', 'paket.jenis')
                                            ->where('detail_transaksi.id_transaksi', '=', $id)
                                            ->get();
        return response()->json($data);                        
    }

    public function getTotal($id)
    {
        $total = Detail_Transaksi::where('id_transaksi', $id)->sum('subtotal');
        
        return response()->json([
            'total' => $total
        ]);
    }

}
