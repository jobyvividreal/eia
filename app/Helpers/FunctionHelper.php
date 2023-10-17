<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\Project;
use App\Models\Eia;
use App\Models\Setting;
use Auth;
use Carbon;

class FunctionHelper
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function projectCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Project::where("project_code", "=", $code)->first());
  
        return $code;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function EIACode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Eia::where("code", "=", $code)->first());
  
        return $code;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function documentCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Document::where("code", "=", $code)->first());
  
        return $code;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function documentName($name)
    {
        do {
            $code       = Str::upper(Str::random(20));
            $document   = $code.'_'.$name;
        } while (DocumentFile::where("original_name", "=", $document)->first());
  
        return $document;
    }

    /**
     * Return Application Timezone
     *
     * @return response()
     */
    public static function getTimezone()
    {
        return Setting::value('timezone');
    }

    public static function dateToUTC($date, $format = null)
    {
        $format = ($format != null)? $format : 'Y-m-d h:i:s';
    $timezone = self::getTimezone();
        return Carbon\Carbon::parse($date, $timezone)->setTimezone('UTC')->format($format);
    }

    public static function dateToTimeZone($date, $format)
    {
        $format = ($format != null)? $format : 'Y-m-d h:i:s';
        $timezone           = self::getTimezone();
        return Carbon\Carbon::parse($date)->timezone($timezone)->format($format);
    }

    public static function dateToTimeFormat($date)
    {
        $time_format           = self::getTimeFormat();
        if($time_format === 'h'){
            return date("d-m-Y H:i:s", strtotime($date));
        }else{
            $date =  str_replace(" AM","",$date);
            $date =  str_replace(" PM","",$date);
            return $date;
        } 
    }

    public static function currency()
    {
        return Setting::join('currencies', 'currencies.id', '=', 'settings.currency_id')->value('currencies.symbol');
    }
    
    
    
    public static function storeImage($file, $path, $slug)
    {
        $path = 'public/' . $path;
        $slug = Str::slug($slug);
        Storage::makeDirectory($path);
        $extension = $file->getClientOriginalExtension();
        $file_name = $slug . '-' . time() . '.' . $extension;
        Storage::putFileAs($path, $file, $file_name);
        return $file_name;
    }

    public static function moveCropped($image, $new_path, $slug)
    {
        $image_name = '';
        if ($image != '') {
            $path = 'public/' . $new_path;
            $temp = 'public/temp/' . Session::get('temp_url') . '/';
            Storage::makeDirectory($path);
            $result = explode('.', $image);
            $extension = $result[1];
            $image_name = $slug . '-' . time() . '.' . $extension;
            Storage::move($temp . $image, $path . $image_name);
            Storage::deleteDirectory($temp);
            Session::forget('temp_url');
        }
        return $image_name;
    }

    public static function cropAndStore($image, $path, $slug)
    {
        $image_name = '';

        if ($image != '') {

            $input['imagename'] = $slug . '-' . time().'.'.$image->extension();         
            $destinationPath    = public_path('/thumbnail');

            // Create storage folder
            $store_path = 'public/' . $path;
            Storage::makeDirectory($store_path);



            // $img = Image::make($image->path());
            // $img->resize(100, 100, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPath.'/'.$input['imagename']);


                $resize = Image::make($image)->resize(215, 215, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');


                $hash           = md5($resize->__toString());
                $image_name     = $hash.".jpg";
                $save           = Storage::put($store_path.'/'.$image_name, $resize->__toString());
                return $image_name;
        }

        return $image_name;
    }
}

