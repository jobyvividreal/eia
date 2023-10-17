<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\Project;
use App\Models\Document;
use App\Models\Permit;
use App\Models\Eia;
use App\Models\Country;
use App\Models\User;
use Validator;
use Auth;
use Hash;
use DB;

class HomeController extends Controller
{
    protected $title    = 'Dashboard';
    protected $viewPath = 'admin';
    protected $route    = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page                   = collect();
        $variants               = collect();
        $user                   = auth()->user();
        $page->title            = "Dashboard";
        $page->route            = "profile";
        $variants->projects     = Project::get();
        $variants->eia          = Eia::get();
        $variants->documents    = Document::get();
        $variants->permits      = Permit::whereActive(1)->get();
        return view($this->viewPath . '.home', compact('page', 'variants', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        
        $page                   = collect();
        $variants               = collect();
        $user                   = auth()->user();
        $page->title            = "Profile";
        $page->route            = "profile";
        $page->form_url         = url($this->route . '/' . $user->id);
        $page->form_method      = 'PUT';
        $variants->phonecode    = Country::select("id", DB::raw('CONCAT(" +", phonecode, " (", name, ")") AS phone_code'))->pluck('phone_code', 'id');
        return view($this->viewPath . '.profile', compact('page', 'variants', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
                    'name' => 'required', 
                    'email' => 'required|email|unique:users,email,'.$request->user_id,
                    'mobile' => 'nullable|min:3|max:15|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,mobile,'.$request->user_id,
        ]);
                    
        if ($validator->passes()) {
            $user               = auth()->user();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->mobile       = $request->mobile;
            $user->phone_code   = $request->phone_code;
            $user->save();
            return ['flagError' => false, 'message' => "Profile updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_password_confirmation' => ['same:new_password'],
        ]);
        if ($validator->passes()) {

            if(strcmp($request->get('old_password'), $request->get('new_password')) == 0){
                $errors = array('Old and new passwords cannot be same !');
                return ['flagError' => true, 'message' => "Errors Occurred. Please check!",  'error'=>$errors];
            }

            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            return ['flagError' => false, 'message' => "Password updated successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check!",  'error'=> $validator->errors()->all()];
    }
}
