<?php

use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/download', function (\App\Actions\GetTextExportAction $action) {
        return response($action->execute(auth()->user()), 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="export.txt"'
        ]);
    })->name('download');
    Route::post('/dashboard', function (\Illuminate\Http\Request $request, \App\Actions\CreateTaskAction $action) {
        collect(explode("\n", $request->get('urls')))
            ->map(fn($url) => trim($url))
            ->filter()
            ->filter(fn($url) => \Illuminate\Support\Facades\Validator::make(['url' => $url], ['url' => 'required|url'])->passes())
            ->each(fn($url) => $action->execute($request->user(), $url));

        return redirect()->back();
    })->name('dashboard');
});
