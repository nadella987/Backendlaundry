<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class PaketController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();   
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'jenis' => 'required',
            'harga' => 'required',
    
        ]);

        if($validator->fails()) {
            return Response()->json($validator->errors());

        }

        $paket = new Paket;
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
        $paket->save();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Tambah Paket']);

    }


    public function getAll()
    {
        $data = Paket::get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        $data = Paket::where('id_paket', '=', $id)->first();        
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validator= Validator::make($request->all(),[
            'jenis' => 'required',
            'harga' => 'required',

        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $paket = Paket::where('id_paket', '=' ,$id)->first();
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
        $paket->save();
        return response()->json([
            'success' => true,
            'message' => 'Data Paket Berhasil update'
        ]);
        
    }

    public function delete($id)
    {
        $delete = Paket::where('id_paket', '=', $id)->delete();
        
        if($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Data member berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Data member gagal dihapus'
            ]);            
        }
    }
 
}