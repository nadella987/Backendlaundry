<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use App\Models\Detail_Transaksi;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use JWTAuth;

class TransaksiController extends Controller
{
    public $user; 
    public function __construct() 
    {
        $this-> user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[    
            'id_member' => 'required',
     
        ]);

        if($validator->fails()) {
            return Response()->json($validator->errors());

        }
        $transaksi = new Transaksi();
        $transaksi->id_member = $request->id_member;
        $transaksi->tanggal = Carbon::now(); 
        $transaksi->batas_waktu = Carbon::now()->addDays(3); 
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum_dibayar';
        $transaksi->id = $this->user->id;
        $transaksi->total = 0;
        $transaksi->save();
        $data = Transaksi::where('id_transaksi', '=' ,$transaksi->id_transaksi)->first(); 
        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil ditambahkan', 'data' => $data]);

    }
    public function cari_data($key)
    { 
        $id_user = $this->user->id;
        $data_user = User::where('id', '=', $id_user)->first();
        $data = DB::table('transaksi')
        ->join('member','transaksi.id_member','=','member.id_member')
        ->join('users', 'transaksi.id', 'users.id')
        ->select('transaksi.*','member.nama_member', 'users.name')
        ->where('users.id_outlet', $data_user->id_outlet)
        ->where('nama_member','like','%' .$key.'%')
        ->orWhere('status','like','%' .$key.'%')
        ->get();
        return response()->json($data);
    }

    public function getAll()
        {
            
            $id_user = $this->user->id;
            $data_user = User::where('id', '=', $id_user)->first();   

            $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                                            ->join('users', 'transaksi.id', 'users.id')
                                            ->select('transaksi.*', 'member.nama_member', 'users.name')
                                            // ->select('transaksi.id', 'member.nama_member', 'transaksi.tanggal', 'transaksi.status' , 'users.name')
                                            ->where('users.id_outlet', $data_user->id_outlet)
                                            ->orderBy('transaksi.tanggal', 'DESC')
                                            ->get();
                        
            return response()->json(['success' => true, 'data' => $data]);
        }

        public function getById($id)
        {
            $data = Transaksi::where('id_transaksi', '=', $id)->first();
            $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                ->select('transaksi.*', 'member.nama_member')
                ->where('transaksi.id_transaksi', '=', $id)
                ->first();
            return response()->json($data);
        }
        

    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $transaksi->status = $request->status;
        
        $transaksi->save();
        
        return response()->json(['message' => 'Status berhasil diubah']);      
        
    }


    public function bayar($id)
    {
        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $total = Detail_Transaksi::where('id_transaksi', $id)->sum('subtotal');

        $transaksi->tgl_bayar = Carbon::now();
        $transaksi->status = "diambil";
        $transaksi->dibayar = "dibayar";
        $transaksi->total = $total;

        $transaksi->save();

        return response()->json(['message' => 'Pembayaran berhasil']);
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'bulan' => 'required',
            'id_outlet' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $id_outlet = $request->id_outlet;

        $id_user = $this->user->id;
        $data_user = User::where('id', '=', $id_user)->first();

        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
            ->select('transaksi.id_transaksi', 'transaksi.tanggal', 'transaksi.tgl_bayar', 'transaksi.total', 'member.nama_member')
            ->join('users','users.id','=','transaksi.id')
            ->select('transaksi.id','transaksi.tanggal','transaksi.tgl_bayar','transaksi.total','member.nama_member','users.name')
            ->where('users.id_outlet', '=', $id_outlet)
            ->whereYear('tanggal', '=', $tahun)
            ->whereMonth('tanggal', '=', $bulan)
            ->get();

        return response()->json($data);
    }
}
