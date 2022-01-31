<?php

use App\Http\Controllers\Api\MeetingController;
use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//базовые CRUD операции:
Route::apiResources([
    'meetings' => MeetingController::class,
    'employees' => EmployeeController::class
]);

// кастомные операции:
Route::get('/getMeetings/{dayStarts}/{dayEnds}', [MeetingController::class, 'getMeetings']);
Route::get('/getOptimumMeetings/{dayStarts}/{dayEnds}', [MeetingController::class, 'getOptimumMeetings']);

