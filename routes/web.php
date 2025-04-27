<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/view-pdf/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"'
    ]);
});
