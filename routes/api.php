<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\AccountSettingController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\GroupController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth'], function () {
  Route::post('login', [AuthController::class, 'login'])->name('auth.login');
  Route::post('register', [AuthController::class, 'register'])->name('auth.register');
  Route::post('password/forgot', [AuthController::class, 'forgotPassword'])->name('password.email');
  ROute::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

  Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('user', [AuthController::class, 'user'])->name('auth.user');
    Route::post('2fa', [AuthController::class, 'verifyTwoFactor'])->name('twofactor.verify');
    Route::get('2fa/resend', [AuthController::class, 'resendTwoFactor'])->name('twofactor.resend');
  });

  Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('email-verification.verify');
});

// User routes
Route::group([
  'middleware' => ['auth:sanctum', 'verified'],
  'prefix' => 'client'
], function () {
  Route::get('staff', [StaffController::class, 'index'])->name('client.staff.index');
  Route::get('staffList', [StaffController::class, 'getStaffList'])->name('client.staff.list');
  Route::post('staff', [StaffController::class, 'store'])->name('client.staff.store');
  Route::get('staff/{id}', [StaffController::class, 'show'])->name('client.staff.show');
  Route::put('staff/{id}', [StaffController::class, 'update'])->name('client.staff.update');
  Route::delete('staff/{id}', [StaffController::class, 'destroy'])->name('client.staff.delete');
  Route::delete('staff', [StaffController::class, 'destroyMultiple'])->name('client.staff.deleteMultiple');
  Route::resource('groups', GroupController::class);

  Route::post('account-setting/data', [AccountSettingController::class, 'getData']);
  Route::post('account-setting/save_change', [AccountSettingController::class, 'saveChange']);
  Route::post('setting-password/save_change', [AccountSettingController::class, 'passSaveChange']);
  Route::post('setting-address/getaddress', [AccountSettingController::class, 'getAddress']);
  Route::post('setting-address/save_change', [AccountSettingController::class, 'addressSaveChange']);
  Route::post('setting-location/addlocation', [AccountSettingController::class, 'addLocation']);
  Route::post('setting-location/getlocation', [AccountSettingController::class, 'getLocation']);
  Route::post('setting-location/deletelocation', [AccountSettingController::class, 'deleteLocation']);
  Route::post('setting-location/changestatuslocation', [AccountSettingController::class, 'changeStatusLocation']);
  Route::post('setting-location/updatelocation', [AccountSettingController::class, 'updateLocation']);
  Route::post('setting-location/getconvention', [AccountSettingController::class, 'getConvention']);
  Route::post('setting-location/getape', [AccountSettingController::class, 'getCode']);
  
  Route::post('account-setting/download_image', [AccountSettingController::class, 'downloadImage']);
  Route::post('setting-shift/addshift', [AccountSettingController::class, 'addShift']);
  Route::post('setting-shift/loadshift', [AccountSettingController::class, 'loadShift']);
  Route::post('setting-shift/deleteshift', [AccountSettingController::class, 'deleteShift']);
  Route::post('setting-shift/editshift', [AccountSettingController::class, 'editShift']);
  Route::post('setting-shift/updateshift', [AccountSettingController::class, 'updateShift']);

 
  //account setting router
});


Route::post('client/account-setting/data', [AccountSettingController::class, 'getData']);

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
  Route::get('apps/todo/tasks', [TodoController::class, 'index'])->name('fetch-tasks');
  Route::post('apps/todo/tasks', [TodoController::class, 'store'])->name('store-tasks');
  Route::post('apps/todo/tasks/xyxy/{todo}', [TodoController::class, 'update'])->name('update-tasks');
  Route::delete('apps/todo/tasks/xyxy/{todo}', [TodoController::class, 'destroy'])->name('remove-tasks');

  Route::post('expenses', [ExpensesController::class, 'expenses']);
  Route::post('analyse', [ExpensesController::class, 'analyse']);
  Route::any('expenses/status/{id}', [ExpensesController::class, 'updateStaffStatus']);
  Route::any('expenses/download/{id}', [ExpensesController::class, 'expenses_download']);
  Route::post('query/expenses', [ExpensesController::class, 'query_expenses']);
  Route::post('add-expenses', [ExpensesController::class, 'add_expenses']);
  Route::post('expenses/delete', [ExpensesController::class, 'expenses_delete']);
});
