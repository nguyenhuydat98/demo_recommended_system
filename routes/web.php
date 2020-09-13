<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $products        = json_decode(file_get_contents(storage_path('data/products-data.json')));
    $selectedId      = intval(app('request')->input('id') ?? '8');
    $selectedProduct = $products[0];

    $selectedProducts = array_filter($products, function ($product) use ($selectedId) { 
        return $product->id === $selectedId;
    });

    if (count($selectedProducts)) {
        $selectedProduct = $selectedProducts[array_keys($selectedProducts)[0]];
    }

    $productSimilarity = new App\ProductSimilarity($products);
    $similarityMatrix  = $productSimilarity->calculateSimilarityMatrix();
    $products          = $productSimilarity->getProductsSortedBySimularity($selectedId, $similarityMatrix);
    return view('welcome', compact('selectedId', 'selectedProduct', 'products'));

});