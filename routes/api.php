<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\branchesController;
use App\Http\Controllers\contributionController;
use App\Http\Controllers\contypeController;
use App\Http\Controllers\parcelsController;
use App\Http\Controllers\settingsController;
use App\Http\Controllers\userController;
use App\Http\Controllers\memberController;
use App\Http\Controllers\fundController;
use App\Http\Controllers\smsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/** User **/ 
Route::get('/searchUser', [userController::class,'searchUser']);
Route::get('/getAllUser',[userController::class, 'index']);
Route::post('/login', [userController::class, 'login']);
Route::post('/register',[userController::class, 'store']);
Route::put('/updateUser/{id}',[userController::class, 'updateUser']);
Route::delete('/deleteUser/{id}',[userController::class, 'deleteUser']);


/** Branches  */
Route::get('/searchBranches', [branchesController::class,'searchBranches']);
Route::get('/getAllBranches',[branchesController::class, 'index']);
Route::post('/storeBranches',[branchesController::class, 'store']);
Route::put('/updateBranches/{id}',[branchesController::class, 'updateBranch']);
Route::delete('/deleteBranches/{id}',[branchesController::class, 'deleteBranch']);


/** Member  */
Route::post('/testSms', [memberController::class, 'testSms']);
Route::get('/countMember', [memberController::class,'countMember']);
Route::get('/searchActiveMember', [memberController::class,'searchActiveMember']);
Route::get('/searchBlockMember', [memberController::class,'searchBlockedMember']);
Route::get('/getAllMember', [memberController::class, 'allMembers']);
Route::get('/getAllActiveMember', [memberController::class, 'index']);
Route::get('/getAllDeactiveMember', [memberController::class, 'blockedList']);
Route::post('/storeMember',[memberController::class, 'store']);
Route::put('/updateMember/{id}',[memberController::class, 'updateMember']);
Route::put('/blockMember/{id}',[memberController::class, 'blockMember']);
Route::delete('/deleteMember/{id}',[memberController::class, 'deleteMember']);


/** Contribution */
Route::get('/countContribution', [contributionController::class,'countContribution']);
Route::get('/sumContribution', [contributionController::class,'sumContribution']);
Route::get('/searchContribution', [contributionController::class,'searchContribution']);
Route::get('/getMostAllContribution',  [contributionController::class,'allContribution']);
Route::get('/getAllContribution',[contributionController::class, 'index']);
Route::post('/storeContribution',[contributionController::class, 'store']);
Route::put('/updateContribution/{id}',[contributionController::class, 'updateContribution']);
Route::delete('/deleteContribution/{id}',[contributionController::class, 'deleteContribution']);


/** Contype **/
Route::get('/searchContype', [contypeController::class,'searchContype']);
Route::get('/getAllContype',[contypeController::class, 'index']);
Route::post('/storeContype',[contypeController::class, 'store']);
Route::put('/updateContype/{id}',[contypeController::class, 'updateContype']);
Route::delete('/deleteContype/{id}',[contypeController::class, 'deleteContype']);


/** Parcels **/
Route::get('/searchParcel', [parcelsController::class,'searchParcel']);
Route::get('/getAllParcel',[parcelsController::class, 'index']);
Route::post('/storeParcel',[parcelsController::class, 'store']);
Route::put('/updateParcel/{id}',[parcelsController::class, 'updateParcel']);
Route::delete('/deleteParcel/{id}',[parcelsController::class, 'deleteParcel']);


/** Funds */
Route::get('/countFund', [fundController::class,'countFund']);
Route::get('/sumFund', [fundController::class,'sumFund']);
Route::get('/searchFund', [fundController::class,'searchFund']);
Route::get('/getAllFund',[fundController::class, 'index']);
Route::post('/storeFund',[fundController::class, 'store']);
Route::put('/updateFund/{id}',[fundController::class, 'updateFund']);
Route::delete('/deleteFund/{id}',[fundController::class, 'deleteFund']);


/** Sms */
Route::get('/getAllSms',[smsController::class, 'index']);
Route::post('/sendPersonalSms',[smsController::class, 'sendPersonalSms']);
Route::post('/sendBulkSms', [smsController::class, 'sendBulkSms']);


/** Settings */
// Route::get('/searchSetting', [settingsController::class,'searchSystemSettings']);
Route::get('/getAllSetting',[settingsController::class, 'index']);
Route::post('/storeSetting',[settingsController::class, 'store']);
Route::put('/updateSetting/{id}',[settingsController::class, 'updateSystemSettings']);
Route::delete('/deleteSetting/{id}',[settingsController::class, 'deleteSystemSettings']);

Route::middleware(['auth:sanctum'])->get('/retrieve', [userController::class, 'getUserDetails']);
Route::post('/logout', [userController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




