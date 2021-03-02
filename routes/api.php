<?php

use App\Http\Resources\TodayResource;
use App\Http\Resources\UpcomingResource;
use App\Models\Today;
use App\Models\Upcoming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Upcoming task
Route::get('/upcoming/list', function () {
    $upcoming = Upcoming::all();
    return UpcomingResource::collection($upcoming);
});

Route::post('/upcoming/add', function (Request $request) {
    return Upcoming::create([
        'taskid' => $request->taskid,
        'title' => $request->title,
        'completed' => $request->completed,
        'approved' => $request->approved,
        'waiting' => $request->waiting
    ]);
});

Route::delete('/upcoming/delete/{taskid}', function ($taskid) {
    DB::table('upcomings')->where('taskid', $taskid)->delete();
    return 204;
});

// Today task
Route::get('/today/list', function () {
    $today = Today::all();
    return TodayResource::collection($today);
});

Route::post('/today/add', function (Request $request) {
    return Today::create([
        'taskid' => $request->taskid,
        'title' => $request->title,
        'completed' => $request->completed,
        'approved' => $request->approved
    ]);
});

Route::delete('/today/delete/{taskid}', function ($taskid) {
    DB::table('todays')->where('taskid', $taskid)->delete();
    return 204;
});
