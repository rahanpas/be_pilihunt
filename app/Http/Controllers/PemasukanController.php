<?php

namespace App\Http\Controllers;

use App\Models\pemasukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PemasukanController extends Controller
{
    public function simpanpemasukan(Request $request)
    {
        $storage = env('STORAGE_DISK', 'public');

        $validator = Validator::make($request->all(), [
            'infopemasukan' => 'required',
            'jumlah' => 'required',
            'nomornota' => 'required',
            'tanggalnota' => 'required',
            'fileupload' => 'required|max:3048',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $defaultfile =  date("Y") . '/' . date("m") . '/pengeluaran/';

        $filename = str_random(20) . '.' . $request->fileupload->getClientOriginalExtension();

        Storage::disk($storage)->putFileAs($defaultfile, $request->file('fileupload'), $filename, 'public');

        // dd($defaultfile);

        $save_pemasukan = new pemasukan();
        $save_pemasukan->infopemasukan  = $request->infopemasukan;
        $save_pemasukan->jumlah = $request->jumlah;
        $save_pemasukan->pathfile = $defaultfile;
        $save_pemasukan->namafile = $filename;
        $save_pemasukan->piciduser = Auth::id();
        $save_pemasukan->nomornota = $request->nomornota;
        $save_pemasukan->tanggalnota = $request->tanggalnota;
        $save_pemasukan->save();

        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'success'
        ]);
    }
}
