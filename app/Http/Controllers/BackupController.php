<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\VarDumper\Cloner\Data;

class BackupController extends Controller
{
    //
    public function index(){
        // dd(DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, "NP"));
        File::ensureDirectoryExists(backup_path());
        $data=glob(backup_path().DIRECTORY_SEPARATOR.'*.sql');
        $files=[];
        foreach ($data as $key => $file_path) {
            $_data=pathinfo($file_path);
            $_data['size']=truncate_decimals( File::size($file_path)/(1024*1024),3);
            $_data['time']=new Carbon(File::lastModified($file_path));
            // $_data['time']=str_replace('backup-','',$_data['filename']);
            array_push($files,$_data);
        }
        // dd($data,$files);
        return view('admin.backup.index',compact('files'));
    }

    public function del(Request $request){
        try {
            $path=backup_path().DIRECTORY_SEPARATOR.$request->file.'.sql';
            File::delete($path);
            return response()->json(['status'=>true]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'err'=>$th->getMessage()]);
            //throw $th;
        }
    }

    public function create(Request $request){
        try {
            Artisan::call('backup');
            return response()->json(['status'=>true]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'err'=>$th->getMessage()]);
        }
    }
}
