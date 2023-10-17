<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FunctionHelper;
use App\Models\Setting;
use App\Models\Country;
use App\Models\User;
use DB;

class SettingController extends Controller
{

    protected $title    = 'Settings';
    protected $viewPath = 'admin.settings';
    protected $route    = 'admin/settings';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page                   = collect();
        $variants               = collect();
        $user                   = auth()->user();
        $page->title            = $this->title;
        $page->link             = url($this->route);
        $page->route            = $this->route;  
        $variants->settings     = Setting::find(1);        
        $variants->currency     = DB::table('currencies')->pluck('symbol', 'id');
        $variants->country      = Country::pluck('name', 'id');
        return view($this->viewPath . '.settings', compact('page', 'variants', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCurrency(Request $request)
    {
        $setting                = Setting::find(1);
        $setting->country_id    = $request->country;
        $setting->currency_id   = $request->currency;
        $setting->save();
        return ['flagError' => false, 'message' => " Details updated successfully"];
    }
}
