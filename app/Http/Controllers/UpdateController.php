<?php
/*
* @author: Pietro Cinaglia
* 	.website: http://linkedin.com/in/pietrocinaglia
*/
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Artisan;
use Auth;

class UpdateController extends Controller
{   
    public function index(){
        $version = $this->check();

        return view('settings.update.update_settings',compact('version'));
    }


    private $tmp_backup_dir = null;

    private function checkPermission(){

        if( config('laraupdater.allow_users_id') !== null ){

            // 1
            if( config('laraupdater.allow_users_id') === false ) return true;

            // 2
            if( in_array(Auth::User()->id, config('laraupdater.allow_users_id')) === true ) return true;
        }

        return false;
    }
    /*
    * Download and Install Update.
    */
    public function update()
    {

        if( ! $this->checkPermission() ){
            return response()->json(['error' => false, 'msg' => trans("translate.ACTION_NOT_ALLOWED.")], 400);
            exit;
        }

        $lastVersionInfo = $this->getLastVersion();

        if ( $lastVersionInfo['version'] <= $this->getCurrentVersion() ){
            exit;
        }

        try{
            $this->tmp_backup_dir = base_path().'/backup_'.date('Ymd');

            $update_path = null;
            if( ($update_path = $this->download($lastVersionInfo['archive'])) === false)
                return response()->json(['error' => false , 'msg' => trans("translate.Error_during_download.")], 400);
            $status = $this->install($lastVersionInfo['version'], $update_path, $lastVersionInfo['archive']);

            if($status){
                $this->setCurrentVersion($lastVersionInfo['version']); //update system version
            }else
                return response()->json(['error' => false , 'msg' => trans("translate.Error_during_download.")], 400);


        }catch (\Exception $e) {
            $this->restore();
            return response()->json(['error' => false , 'msg' => trans("translate.ERROR_DURING_UPDATE_(!!check_the_update_archive!!)")], 400);
        }
    }

    private function install($lastVersion, $update_path, $archive)
    {
        try{
            $execute_commands = false;
            $upgrade_cmds_filename = 'upgrade.php';
            $upgrade_cmds_path = config('laraupdater.tmp_path').'/'.$upgrade_cmds_filename;

            $zipHandle = zip_open($update_path);
            $archive = substr($archive,0, -4);

            while ($zip_item = zip_read($zipHandle) ){
                $filename = zip_entry_name($zip_item);
                $dirname = dirname($filename);

                // Exclude these cases (1/2)
                if(	substr($filename,-1,1) == '/' || dirname($filename) === $archive || substr($dirname,0,2) === '__') continue;

                //Exclude root folder (if exist)
                if( substr($dirname,0, strlen($archive)) === $archive )
                    $dirname = substr($dirname, (strlen($dirname)-strlen($archive)-1)*(-1));

                $filename = $dirname.'/'.basename($filename); //set new purify path for current file

                if ( !is_dir(base_path().'/'.$dirname) ){ //Make NEW directory (if exist also in current version continue...)
                    File::makeDirectory(base_path().'/'.$dirname, $mode = 0755, true, true);
                }

                if ( !is_dir(base_path().'/'.$filename) ){ //Overwrite a file with its last version
                    $contents = zip_entry_read($zip_item, zip_entry_filesize($zip_item));
                    $filename_ext = pathinfo($filename);
                    if (!empty($filename_ext["extension"]) && $filename_ext["extension"] != "png") {
                        $contents = str_replace("\r\n", "\n", $contents);
                    }

                    if ( strpos($filename, 'upgrade.php') !== false ) {
                        File::put($upgrade_cmds_path, $contents);
                        $execute_commands = true;

                    }else {

                        if(File::exists(base_path().'/'.$filename)) $this->backup($filename); //backup current version

                        File::put(base_path().'/'.$filename, $contents);
                        unset($contents);
                    }

                }
            }
            zip_close($zipHandle);

            File::delete($update_path); //clean TMP
            File::deleteDirectory($this->tmp_backup_dir); //remove backup temp folder

        }catch (\Exception $e) { 
            return response()->json(['error' => false]);
        }
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('migrate --force');
        return response()->json(['success' => true]);
    }

    /*
    * Download Update from $update_baseurl to $tmp_path (local folder).
    */
    private function download($update_name)
    {
        try{
            $filename_tmp = config('laraupdater.tmp_path').'/'.$update_name;

            if ( !is_file( $filename_tmp ) ) {
                $newUpdate = file_get_contents(config('laraupdater.update_baseurl').'/'.$update_name);

                $dlHandler = fopen($filename_tmp, 'w');

                if ( !fwrite($dlHandler, $newUpdate) ){
                    exit();
                }
            }

        }catch (\Exception $e) { 
            return false;
         }

        return $filename_tmp;
    }

    /*
    * Return current version (as plain text).
    */
    public function getCurrentVersion(){
        // todo: env file version
        $version = File::get(base_path().'/version.txt');
        return $version;
    }

    /*
    * Check if a new Update exist.
    */
    public function check()
    {
        $lastVersionInfo = $this->getLastVersion();
        if( version_compare($lastVersionInfo['version'], $this->getCurrentVersion(), ">") )
            return $lastVersionInfo['version'];

        return '';
    }

    private function setCurrentVersion($last){
        File::put(base_path().'/version.txt', $last); //UPDATE $current_version to last version
    }

    private function getLastVersion(){
        $content = file_get_contents(config('laraupdater.update_baseurl').'/laraupdater.json');
        $content = json_decode($content, true);
        return $content; //['version' => $v, 'archive' => 'RELEASE-$v.zip', 'description' => 'plain text...'];
    }

    private function backup($filename){
        $backup_dir = $this->tmp_backup_dir;

        if ( !is_dir($backup_dir) ) File::makeDirectory($backup_dir, $mode = 0755, true, true);
        if ( !is_dir($backup_dir.'/'.dirname($filename)) ) File::makeDirectory($backup_dir.'/'.dirname($filename), $mode = 0755, true, true);

        File::copy(base_path().'/'.$filename, $backup_dir.'/'.$filename); //to backup folder
    }

    private function restore(){
        if( !isset($this->tmp_backup_dir) )
            $this->tmp_backup_dir = base_path().'/backup_'.date('Ymd');

        try{
            $backup_dir = $this->tmp_backup_dir;
            $backup_files = File::allFiles($backup_dir);

            foreach ($backup_files as $file){
                $filename = (string)$file;
                $filename = substr($filename, (strlen($filename)-strlen($backup_dir)-1)*(-1));
                File::copy($backup_dir.'/'.$filename, base_path().'/'.$filename); //to respective folder
            }

        }catch(\Exception $e) {
            return false;
        }

        return true;
    }


    public function viewStep1(Request $request)
    {
        return view('update.viewStep1');
    }
    
    public function lastStep(Request $request)
    {
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes 

        try {
           
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            Artisan::call('migrate --force');
            
        } catch (\Exception $e) {
            
            return $e->getMessage();
            
            return 'Something went wrong';
        }
        
        return view('update.finishedUpdate');
    }



}