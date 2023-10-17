<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Document;
use App\Models\Permit;
use App\Models\Eia;

class HomeController extends Controller
{
    protected $title    = 'Dashboard';
    protected $viewPath = '';
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
        $page->route            = "home";
        $variants->projects     = Project::get();
        $variants->eia          = Eia::get();
        $variants->documents    = Document::get();
        $variants->permits      = Permit::whereActive(1)->get();
        return view('home', compact('page', 'variants', 'user'));
    }

    public function mapLayer(Request $request)
    {
        $mapMarkers     = array(); 
        $projects       = Project::get();
        $eias           = Eia::where('is_permit', 0)->get();
        if ($request->map_view_layer == 1) {
            foreach($projects->sortByDesc('id') as $key => $project) {
                $mapMarkers[] = array($project->latitude, $project->longitude, $project->name . '<br><a href="'.url('projects/'.$project->id).'" target="_blank">View</a>');
            }
        } else {
            foreach($eias->sortByDesc('id')->take(10) as $key => $eia) {
                $mapMarkers[] = array($eia->latitude, $eia->longitude, $eia->code_id . '<br><a href="'.url('eias/'.$eia->id).'" target="_blank">View</a>');
            }
        }
        return ['flagError' => false, 'mapMarkers' => $mapMarkers];
    }   
}