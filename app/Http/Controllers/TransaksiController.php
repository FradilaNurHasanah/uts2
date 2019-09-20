<?php

namespace App\Http\Controllers;
use App\Transaksi;
use App\User;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class TransaksiController extends Controller
{
    //

    public function index(){
        $data = Transaksi::all();
        return $data;
    }

    public function create(Request $request){
// try {
      if(! $akun = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['user_not_found'], 404);
      }
      $user = User::where('id',$akun['id'])->first();
       if($request->jenis!='debit' && $request->jenis!='kredit' ){
        return response()->json(['error'=>'jenis_salah'], 400);
      }
      $data = new Transaksi();
      $data->username = $user->username;
      $data->jenis = $request->input('jenis');
      $data->nama_transaksi =$request->input('nama_transaksi');
      $data->jumlah = $request->input('jumlah');
      $data->save();
      if($request->input('jenis')=='debit'){
        $user->jumlah_saldo =$user->jumlah_saldo + $request->input('jumlah');
      } else{
        $user->jumlah_saldo =$user->jumlah_saldo - $request->input('jumlah');
      } 
      
      $user->save();
      return response()->json(compact('data','user'));

    } 
    // catch(\Exception $e) {
    //   return response()->json([
    //       'status' => '0', 'message' => 'gagal menambah']);
    // }












public function update(Request $request)
{
  try{
    if(! $akun = JWTAuth::parseToken()->authenticate) {
      return response()->json(['user_not_found'], 404);
    }
    $user = User::where('id',$akun['id'])->first();
    $data = transaksi::where('id', $id);
    $data->username = $akun['username'];
    $data->jenis=$request->input('jenis');
    $data->nama_transaksi=$request->input('nama_transaksi');
    $data->jumlah=$request->input('jumlah');
    $user->save();
    $user->saldo=$user->saldo = $request->input('jumlah');
    $user->save();
    return response()->json(compact('data','user'));
  } catch(\Exception $e){
    return response()->json ([
        'status'=> '0', 'message' => 'gagal menambah']);
    }
  }
}