<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;

Route::get('/test-filtering', function() {
    $controller = new ListingController();
    $request = new Illuminate\Http\Request();
    $result = $controller->index($request);
    
    // Get the data
    $data = $result->getData();
    
    echo "<h1>Filtering Debug Test</h1>";
    echo "<h2>Brands Data:</h2>";
    echo "<pre>";
    print_r($data['brands']);
    echo "</pre>";
    
    echo "<h2>Categories Data:</h2>";
    echo "<pre>";
    print_r($data['categories']);
    echo "</pre>";
    
    echo "<h2>Sample Brand Dropdown HTML:</h2>";
    echo "<select>";
    echo "<option value='all'>All Brands</option>";
    foreach($data['brands'] as $brand) {
        echo "<option value='{$brand}'>{$brand}</option>";
    }
    echo "</select>";
    
    echo "<h2>Sample Category Dropdown HTML:</h2>";
    echo "<select>";
    echo "<option value='all'>All Categories</option>";
    foreach($data['categories'] as $category) {
        echo "<option value='{$category}'>{$category}</option>";
    }
    echo "</select>";
});
