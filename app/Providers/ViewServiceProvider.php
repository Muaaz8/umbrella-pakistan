<?php

namespace App\Providers;
use App\Models\ProductsSubCategory;
use App\Models\TestCategory;
use App\Models\Category;
use App\Models\ProductCategory;

use Illuminate\Support\ServiceProvider;
use View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['product_tests.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_tests.fields'], function ($view) {
            $products_sub_categoryItems = ProductsSubCategory::pluck('name','id')->toArray();
            $view->with('products_sub_categoryItems', $products_sub_categoryItems);
        });
        View::composer(['product_tests.fields'], function ($view) {
            $products_sub_categoryItems = ProductsSubCategory::pluck('title','id')->toArray();
            $view->with('products_sub_categoryItems', $products_sub_categoryItems);
        });
        View::composer(['product_tests.fields'], function ($view) {
            $products_sub_categoryItems = ProductsSubCategory::pluck('name','id')->toArray();
            $view->with('products_sub_categoryItems', $products_sub_categoryItems);
        });
        View::composer(['product_tests.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['products.fields'], function ($view) {
            $products_sub_categoryItems = ProductsSubCategory::pluck('name','id')->toArray();
            $view->with('products_sub_categoryItems', $products_sub_categoryItems);
        });
        View::composer(['products.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['products_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['products_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['my_test_cats.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_cat3s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_cat2s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_cats.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_post2s.fields'], function ($view) {
            $test_categoryItems = TestCategory::pluck('name','id')->toArray();
            $view->with('test_categoryItems', $test_categoryItems);
        });
        View::composer(['test_post2s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_post2s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_post2s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_post2s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_post2s.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['test_posts.fields'], function ($view) {
            $test_categoryItems = TestCategory::pluck('name','id')->toArray();
            $view->with('test_categoryItems', $test_categoryItems);
        });
        View::composer(['posts.fields'], function ($view) {
            $categoryItems = Category::pluck('name','id')->toArray();
            $view->with('categoryItems', $categoryItems);
        });
        View::composer(['posts.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['posts.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['posts.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('title','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        View::composer(['posts.fields'], function ($view) {
            $categoryItems = Category::pluck('name','id')->toArray();
            $view->with('categoryItems', $categoryItems);
        });
        View::composer(['product_sub_categories.fields'], function ($view) {
            $product_categoryItems = ProductCategory::pluck('name','id')->toArray();
            $view->with('product_categoryItems', $product_categoryItems);
        });
        //
    }
}