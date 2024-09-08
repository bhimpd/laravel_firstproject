<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');

    $collection = collect([1, 2, 4]);
    dd($collection->all()); // This will output the collection and stop further execution

});
