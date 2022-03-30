<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class MemberController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();   
    }

    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'nama_member' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_telp' => 'required',
    
        ]);

        if($validator->fails()) {
            return Response()->json($validator->errors());

        }

        $member = new Member;
        $member->nama_member = $request->nama_member;
        $member->alamat = $request->alamat;
        $member->jenis_kelamin = $request->jenis_kelamin;
        $member->no_telp = $request->no_telp;
        $member->save();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Tambah Member']);

    }

    public function getAll()
    {
        $data = Member::get();
        return response()->json($data);
    }

    public function getById($id)
    {
        $data = Member::where('id_member', '=', $id)->first();        
        return response()->json($data);
    }

    public function cari_data($key)
    {
        $data = Member::where('nama_member','like','%'.$key.'%')->get();
        return Response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validator= Validator::make($request->all(),[
            'nama_member' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_telp' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $member = Member::where('id_member', '=' ,$id)->first();
        $member->nama_member = $request->nama_member;
        $member->alamat = $request->alamat;
        $member->jenis_kelamin = $request->jenis_kelamin;
        $member->no_telp = $request->no_telp;
        $member->save();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Tambah Member']);
        
    }

    public function delete($id)
    {
        $delete = Member::where('id_member', '=', $id)->delete();

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