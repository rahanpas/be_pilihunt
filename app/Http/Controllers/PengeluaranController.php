<?php

namespace App\Http\Controllers;

use App\Models\pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends Controller
{
    public function simpanpengeluaran(Request $request)
    {
        $storage = env('STORAGE_DISK', 'public');

        $validator = Validator::make($request->all(), [
            'infopengeluaran' => 'required',
            'jumlah' => 'required',
            'tanggalbon' => 'required',
            'fileupload' => 'required|max:3048',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $defaultfile =  date("Y") . '/' . date("m") . '/pengeluaran/';

        $filename = str_random(20) . '.' . $request->fileupload->getClientOriginalExtension();

        Storage::disk($storage)->putFileAs($defaultfile, $request->file('fileupload'), $filename, 'public');

        // dd($defaultfile);

        $save_pengeluaran = new pengeluaran();
        $save_pengeluaran->infopengeluaran  = $request->infopengeluaran;
        $save_pengeluaran->jumlah=$request->jumlah;
        $save_pengeluaran->pathfile =$defaultfile;
        $save_pengeluaran->namafile = $filename;
        $save_pengeluaran->piciduser =Auth::id();
        $save_pengeluaran->tanggalbon= $request->tanggalbon;
        $save_pengeluaran->save();

        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'success'
        ]);
    }
}
