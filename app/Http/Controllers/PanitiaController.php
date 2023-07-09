<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Wdc;
use App\Models\Panitia;
use App\Models\Content;
use App\Models\Project;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\CtExcel;
use App\Exports\WdcExcel;
use App\Exports\DcExcel;
use App\Exports\CtfExcel;
use Maatwebsite\Excel\Facades\Excel;
use File;
use ZipArchive;

class PanitiaController extends Controller
{
    public function index()
    {
        Auth::user();
        return view('panitia.content.dashboard');
    }

// CT ============================================================
    // halaman utama childtalks
    public function ct(Request $request)
    {
        // MENDAPAKATKAN REQUEST SEARCH
        $search = $request->search;

        // CEK JIKA SEARCH = TRUE
        if($search){
            $ct = Ct::          
            join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->join('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $ct = Ct::          
            join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
            ->paginate(15);    
        }

        return view('panitia.chilltalk.dashct', compact(['ct']));
    }
    // Delete ct
    public function delete_ct(Request $request)
    {
        $data = Ct::findOrFail($request['id_ct']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }
    // Export Excel
    public function ctExportExcel()
	{
		return Excel::download(new CtExcel, 'Chilltalks.xlsx');
	}

// WDC ===========================================================

    // halaman wdc
    public function wdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $wdc = Wdc::          
            join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
            ->select('wdc.*', 'peserta.*', 'project.file_project')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $wdc = Wdc::          
            join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
            ->select('wdc.*', 'peserta.*', 'project.file_project')
            ->paginate(15);             
        }

        return view('panitia.wdc.dashwdc', compact(['wdc']));   
    }
    // delete wdc
    public function delete_wdc(Request $request)
    {      
        $data = Wdc::findOrFail($request['id_wdc']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }   
    // Update Wdc
    public function update_wdc(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_wdc' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Wdc::where(['id_wdc' => $request->id_wdc])->update([
                'id_wdc'=>$data['id_wdc'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }

    // Export Excel
    public function wdcExportExcel()
	{
		return Excel::download(new WdcExcel, 'Wdc.xlsx');
	}


// DC ========================================================== 

    // halaman dc
    public function dc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $dc = Dc::          
            join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
            ->select('dc.*', 'peserta.*', 'project.file_project')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate(); 
        }else{
            $dc = Dc::          
            join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
            ->select('dc.*', 'peserta.*', 'project.file_project')
            ->paginate(15);    
        }

        return view('panitia.dc.dashdc', compact(['dc']));
    }
    // menghapus
    public function delete_dc(Request $request)
    { 
        $data = Dc::findOrFail($request['id_dc']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');   
    }
    // Update Dc
    public function update_dc(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_dc' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Dc::where(['id_dc' => $request->id_dc])->update([
                'id_dc'=>$data['id_dc'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }

    // Export Excel
    public function DcExportExcel()
	{
		return Excel::download(new DcExcel, 'Dc.xlsx');
	}

// CTF =========================================================

    // halaman ctf
    public function ctf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $ctf = Ctf::          
            join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('ctf.*', 'peserta.*')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $ctf = Ctf::          
            join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('ctf.*', 'peserta.*')
            ->paginate(15);    
        }

        return view('panitia.ctf.dashctf', compact(['ctf']));
    }
    public function delete_ctf(Request $request)
    {
        $data = Ctf::findOrFail($request['id_ctf']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!'); 
    }
    // Update Ctf
    public function update_ctf(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_ctf' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Ctf::where(['id_ctf' => $request->id_ctf])->update([
                'id_ctf'=>$data['id_ctf'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }

    // Export Excel
    public function CtfExportExcel()
	{
		return Excel::download(new CtfExcel, 'Ctf.xlsx');
	}

// TRANSAKSI ===================================================

    // halaman transaksi
    public function transaksiWdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // mendapatkan data Panitia
        $user = Auth::user();
        $panitia = Panitia::where('email_panitia', $user->email)->first()->id_panitia;

        if($search){
            $transaksi = Transaksi::
            join('wdc', 'transaksi.id_transaksi', '=', 'wdc.id_transaksi')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('wdc', 'transaksi.id_transaksi', '=', 'wdc.id_transaksi')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }


        return view('panitia.transaksi.dashtransaksiwdc', compact(['transaksi', 'panitia']));
    }

    public function transaksiDc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // mendapatkan data Panitia
        $user = Auth::user();
        $panitia = Panitia::where('email_panitia', $user->email)->first()->id_panitia;

        if($search){
            $transaksi = Transaksi::
            join('dc', 'transaksi.id_transaksi', '=', 'dc.id_transaksi')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('dc', 'transaksi.id_transaksi', '=', 'dc.id_transaksi')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

        return view('panitia.transaksi.dashtransaksidc', compact(['transaksi', 'panitia']));
    }
    public function transaksiCtf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // mendapatkan data Panitia
        $user = Auth::user();
        $panitia = Panitia::where('email_panitia', $user->email)->first()->id_panitia;


        if($search){
            $transaksi = Transaksi::
            join('ctf', 'transaksi.id_transaksi', '=', 'ctf.id_transaksi')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('ctf', 'transaksi.id_transaksi', '=', 'ctf.id_transaksi')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

        return view('panitia.transaksi.dashtransaksictf', compact(['transaksi', 'panitia']));
    }
    public function transaksiCt(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // mendapatkan data Panitia
        $user = Auth::user();
        $panitia = Panitia::where('email_panitia', $user->email)->first()->id_panitia;


        if($search){
            $transaksi = Transaksi::
            join('ct', 'transaksi.id_transaksi', '=', 'ct.id_transaksi')
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('ct', 'transaksi.id_transaksi', '=', 'ct.id_transaksi')
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

        return view('panitia.transaksi.dashtransaksict', compact(['transaksi', 'panitia']));
    }
    // delete Transaksi
    public function delete_transaksi(Request $request)
    {
        $data = Transaksi::findOrFail($request['id_transaksi']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');   
    }
    // update transaksi
    public function update_transaksi(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_panitia' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Transaksi::where(['id_transaksi' => $request->id_transaksi])->update([
                'id_panitia'=>$data['id_panitia'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }

// PROJECT =====================================================

    // Manampilkan Halaman Project Lomba WDC
    public function projectWdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $projectwdc = Project::
            join('wdc', 'project.id_project', '=', 'wdc.id_project')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'WDC%')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $projectwdc = Project::
            join('wdc', 'project.id_project', '=', 'wdc.id_project')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'WDC%')
            ->paginate(15);    
        }

        return view('panitia.project.dashprojectwdc', compact(['projectwdc']));
    }

    public function projectDc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $projectdc = Project::
            join('dc', 'project.id_project', '=', 'dc.id_project')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'DC%')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $projectdc = Project::
            join('dc', 'project.id_project', '=', 'dc.id_project')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'DC%')
            ->paginate(15);    
        }

        return view('panitia.project.dashprojectdc', compact(['projectdc']));
    }

    // DOWNLOAD PROJECT WDC SATU SATU
    function downloadProjectWDC($file_name){
        $file = Storage::download("public/Project/wdc/".$file_name);  
        return $file;
    }

    // DOWNLOAD PROJECT DC SATU SATU
    function downloadProjectDC($file_name){
        $file = Storage::download("public/Project/dc/".$file_name);  
        return $file;
    }

     // DOWNLOAD SEMUA PROJECT LOMBA WDC
     function downloadAllProjectWDC()
     {
         // memanggil object zip archive dari laravel yang disimpan ke variabel
         $zip = new ZipArchive;
     
         // membuat nama file yang nantinya akan di download
         $fileName = 'ProjectWDC.zip';
      
         // mendeklarasikan path yang akan di download
         $path = public_path('storage/Project/wdc');
 
         // cek jika variabel yang berisi object filearchive tadi berjalan dan membuat file zip
         if ($zip->open(public_path('storage/'.$fileName), ZipArchive::CREATE) === TRUE)
         {
             // mengambil file file yang ada di path
             $files = File::files($path);
 
             // perulangan untuk mengambil setiap file yang ada di path
             foreach ($files as $key => $value) {
                 // mengambil nama file dari path lengkap filenya
                 $relativeNameInZipFile = basename($value);
                 // menambah file ke dalam zip
                 $zip->addFile($value, $relativeNameInZipFile);
             }
                
             $zip->close();
         }
         
         // fucntion mereturn response yang mendownload zip tadi
         return response()->download(public_path('storage/'.$fileName));
     }
     
     /// DOWNLOAD SEMUA PROJECT LOMBA DC
     function downloadAllProjectDC()
     {
         // memanggil object zip archive dari laravel yang disimpan ke variabel
         $zip = new ZipArchive;
     
         // membuat nama file yang nantinya akan di download
         $fileName = 'ProjectDC.zip';
         
         // mendeklarasikan path yang akan di download
         $path = public_path('storage/Project/dc');
 
         // cek jika variabel yang berisi object filearchive tadi berjalan dan membuat file zip
         if ($zip->open(public_path('storage/'.$fileName), ZipArchive::CREATE) === TRUE)
         {
             // mengambil file file yang ada di path
             $files = File::files($path);
 
             // perulangan untuk mengambil setiap file yang ada di path
             foreach ($files as $key => $value) {
                 // mengambil nama file dari path lengkap filenya
                 $relativeNameInZipFile = basename($value);
                 // menambah file ke dalam zip
                 $zip->addFile($value, $relativeNameInZipFile);
             }
                 
             $zip->close();
         }
         
         // fucntion mereturn response yang mendownload zip tadi
         return response()->download(public_path('storage/'.$fileName));
     }
 
     // DOWNLOAD SEMUA PROJECT LOMBA CTF
    //  function downloadAllProjectCTF()
    //  {
    //      // memanggil object zip archive dari laravel yang disimpan ke variabel
    //      $zip = new ZipArchive;
     
    //      // membuat nama file yang nantinya akan di download
    //      $fileName = 'ProjectCTF.zip';
      
    //      // mendeklarasikan path yang akan di download
    //      $path = public_path('storage/Project/CTF');
 
    //      // cek jika variabel yang berisi object filearchive tadi berjalan dan membuat file zip
    //      if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
    //      {
    //          // mengambil file file yang ada di path
    //          $files = File::files($path);
 
    //          // perulangan untuk mengambil setiap file yang ada di path
    //          foreach ($files as $key => $value) {
    //              // mengambil nama file dari path lengkap filenya
    //              $relativeNameInZipFile = basename($value);
    //              // menambah file ke dalam zip
    //              $zip->addFile($value, $relativeNameInZipFile);
    //          }
                
    //          $zip->close();
    //      }
         
    //      // fucntion mereturn response yang mendownload zip tadi
    //      return response()->download(public_path($fileName));
    //  }
     

}