<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\Admin\HomeController as AdminHome;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ProjectTypeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EiaController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\ChildDocumentController;
use App\Http\Controllers\TaskAssignController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();
Route::get('lang/{lang}', [LocalizationController::class, 'switchLang'])->name('lang.switch');
// Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::get('create-password/{token}', [ForgotPasswordController::class, 'showCreatePasswordForm'])->name('create.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::post('create-password', [ForgotPasswordController::class, 'submitCreatePasswordForm'])->name('create.password.post');

Route::group(['middleware' => ['auth', 'is_user']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    Route::get('/home/get-map-layer', [HomeController::class, 'mapLayer']);
    
    // User Profile
    Route::resource('profile', ProfileController::class);
    Route::post('update-password', [ProfileController::class, 'updatePassword']);
    Route::post('update-profile-photo', [ProfileController::class, 'updateProfilePhoto']);

    // Companies Routes
    $companies = 'companies';
    Route::resource($companies, CompanyController::class)->except(['show']);
    Route::get($companies.'/lists', [CompanyController::class, 'lists']);
    Route::post($companies.'/update-status', [CompanyController::class, 'updateStatus']);
    Route::post($companies.'/restore/{id}', [CompanyController::class, 'restore']);

    // Project Routes
    $projectsRoute = 'projects';
    Route::resource($projectsRoute, ProjectController::class);
    Route::post($projectsRoute.'/restore/{id}', [ProjectController::class, 'restore']);

    // EIA Routes
    Route::resource('eias', EiaController::class);
    Route::get('eias/{id}/details', [EiaController::class, 'details']);
    Route::get('projects/{projectId}/eias/create', [EiaController::class, 'create']);
    Route::get('projects/{projectId}/eias/lists', [EiaController::class, 'lists']);
    // Route::get('projects/{projectId}/eias/{id}/edit', [EiaController::class, 'edit']);

    // Document Routes
    Route::resource('documents', DocumentController::class);
    Route::post('documents/update-status', [DocumentController::class, 'updateStatus']);
    Route::get('eias/{eiaId}/documents/create', [DocumentController::class, 'create']);
    Route::get('eias/{eiaId}/documents/lists', [DocumentController::class, 'lists']);
    Route::get('eias/{eiaId}/documents/{id}/edit', [DocumentController::class, 'edit']);
    Route::post('documents/archive/{id}', [DocumentController::class, 'archive']);
    Route::get('documents/archived/{id}', [DocumentController::class, 'viewArchived']);
    Route::post('documents/delete-version/{id}', [DocumentController::class, 'deleteVersion']);
    Route::post('documents/file/upload', [DocumentController::class, 'uploadDocument'])->name('documents.file.upload');
    Route::post('documents/file/remove', [DocumentController::class, 'fileRemove'])->name('documents.file.remove');
    Route::get('documents/file/list', [DocumentController::class, 'fileList'])->name('documents.file.list');
    Route::get('documents/image-download/{file}', [DocumentController::class, 'downloadFile'])->name('documents.file.download');
    Route::get('documents/pdf-download/{file}', [DocumentController::class, 'downloadPDfFile'])->name('documents.file.download');
    Route::get('documents/download/{file}', [DocumentController::class, 'downloadFile'])->name('documents.download');
    Route::resource('task-assign', TaskAssignController::class);
    // Route::post('documents/task/assign', [TaskAssignController::class, 'assign']);
    // Route::get('document/file/view/{document}', [DocumentController::class, 'viewDocumentFile'])->name('document.file.stream');
    // Route::get('document/autocomplete', [DocumentController::class, 'autocomplete'])->name('document.autocomplete');
    // Child document routes
    // Route::resource('documents', DocumentController::class);
    Route::get('documents/{documentId}/create', [ChildDocumentController::class, 'create']);
    Route::get('sub-documents/list', [ChildDocumentController::class, 'index']);
    Route::post('documents/{documentId}/store', [ChildDocumentController::class, 'store']);
    Route::post('sub-documents/file/upload', [ChildDocumentController::class, 'uploadDocument'])->name('child.documents.file.upload');
    // Route::resource('comments', CommentController::class);
    Route::post('comments/store', [CommentController::class, 'store']);
    Route::resource('notification', NotificationController::class);
    Route::resource('permits', PermitController::class);
    Route::get('permits/documents/list/{id}', [PermitController::class, 'listDocuments']);
    Route::get('permits/{permitID}/documents/create', [PermitController::class, 'createDocument']);
});

// Super Admin Routes
Route::prefix('admin/')->name('admin.')->group(function () {
    Route::group(['middleware' => ['auth', 'admin']], function () {
        // Roles Routes
        Route::resource('roles', RoleController::class);
        // Admin Dashboard Routes
        Route::get('dashboard', [AdminHome::class, 'index'])->name('dashboard');
        Route::get('profile', [AdminHome::class, 'edit'])->name('profile');
        Route::put('profile/{id}', [AdminHome::class, 'update']);
        Route::post('update-password', [AdminHome::class, 'updatePassword']);
        // User Routes
        $userRoute = 'users';
        Route::resource($userRoute, AdminUser::class)->except(['show']);
        Route::get($userRoute.'/lists', [AdminUser::class, 'lists']);
        Route::post($userRoute.'/update-status', [AdminUser::class, 'updateStatus']);
        Route::post($userRoute.'/restore/{id}', [AdminUser::class, 'restore']);
        // Project type Routes
        $projectTypes = 'project-types';
        Route::resource($projectTypes, ProjectTypeController::class)->except(['show']);
        Route::get($projectTypes.'/lists', [ProjectTypeController::class, 'lists']);
        Route::post($projectTypes.'/update-status', [ProjectTypeController::class, 'updateStatus']);
        // Project type Routes
        $designations = 'designations';
        Route::resource($designations, DesignationController::class)->except(['show']);
        Route::get($designations.'/lists', [DesignationController::class, 'lists']);
        Route::post($designations.'/update-status', [DesignationController::class, 'updateStatus']);
        // Departments Routes
        $departments = 'departments';
        Route::resource($departments, DepartmentController::class)->except(['show']);
        Route::get($departments.'/lists', [DepartmentController::class, 'lists']);
        Route::post($departments.'/update-status', [DepartmentController::class, 'updateStatus']);
        // Settings
        Route::get('settings', [SettingController::class, 'index']);
        Route::post('settings/currency', [SettingController::class, 'storeCurrency']);
        $link = 'common';
        Route::post($link . '/is-unique-email', [CommonController::class, 'isUniqueEmail']);
        Route::post($link . '/is-unique-mobile', [CommonController::class, 'isUniqueMobile']);
    });
});

$link = 'common';
Route::post($link . '/is-unique-email', [CommonController::class, 'isUniqueEmail']);
Route::post($link . '/is-unique-mobile', [CommonController::class, 'isUniqueMobile']);
Route::post($link . '/get-more-details', [CommonController::class, 'getMoredetails']);
Route::post($link . '/get-eia-of-project', [CommonController::class, 'getEiaOfProject']);
Route::post($link . '/get-currency', [CommonController::class, 'getCurrency']);
Route::post($link . '/project-types', [CommonController::class, 'getProjectTypes']);