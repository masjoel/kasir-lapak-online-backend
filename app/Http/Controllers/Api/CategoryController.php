<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashflowCategory;
use App\Models\Category;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all categories
        $categories = Category::orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Category',
            'data' => $categories
        ], 200);
    }
    public function sync(Request $request)
    {
        $categories = $request->input('categories');
        if (!$categories || !is_array($categories)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
        }
        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['uuid' => $cat['uuid']],
                [
                    'user_id' => $cat['user_id'] ?? null,
                    'name' => $cat['name'],
                    'icon_path' => $cat['icon_path'] ?? null,
                    'updated_at' => $cat['updated_at'] ?? now(),
                    'deleted_at' => $cat['deleted_at'] ?? null,
                ]
            );
        }
        return response()->json(['status' => 'success']);
    }
    public function getUpdates(Request $request)
    {
        $updatedAfter = $request->query('updated_after');
        $query = Category::query();
        if ($updatedAfter) {
            $query->where('updated_at', '>', $updatedAfter);
        }
        $categories = $query->get();
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ]);
    }

    public function syncProductUnits(Request $request)
    {
        $productUnits = $request->input('product_units');
        if (!$productUnits || !is_array($productUnits)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
        }
        foreach ($productUnits as $pu) {
            ProductUnit::updateOrCreate(
                ['uuid' => $pu['uuid']],
                [
                    'user_id' => $pu['user_id'] ?? null,
                    'name' => $pu['name'],
                    'keterangan' => $pu['keterangan'] ?? null,
                    'updated_at' => $pu['updated_at'] ?? now(),
                    'deleted_at' => $pu['deleted_at'] ?? null,
                ]
            );
        }
        return response()->json(['status' => 'success']);
    }
    public function getUpdateProductUnits(Request $request)
    {
        $updatedAfter = $request->query('updated_after');
        $query = ProductUnit::query();
        if ($updatedAfter) {
            $query->where('updated_at', '>', $updatedAfter);
        }
        $categories = $query->get();
        return response()->json([
            'status' => 'success',
            'product_units' => $categories,
        ]);
    }
    public function syncCashCategories(Request $request)
    {
        $cashCategories = $request->input('cash_categories');
        if (!$cashCategories || !is_array($cashCategories)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
        }
        foreach ($cashCategories as $cc) {
            CashflowCategory::updateOrCreate(
                ['uuid' => $cc['uuid']],
                [
                    'user_id' => $cc['user_id'],
                    'name' => $cc['name'],
                    'type' => $cc['type'],
                    'keterangan' => $cc['keterangan'] ?? null,
                    'updated_at' => $cc['updated_at'] ?? now(),
                    'deleted_at' => $cc['deleted_at'] ?? null,
                ]
            );
        }
        return response()->json(['status' => 'success']);
    }

    public function getUpdateCashCategories(Request $request)
    {
        $updatedAfter = $request->query('updated_after');
        $query = CashflowCategory::query();
        if ($updatedAfter) {
            $query->where('updated_at', '>', $updatedAfter);
        }
        $categories = $query->get();
        return response()->json([
            'status' => 'success',
            'cash_categories' => $categories,
        ]);
    }
}
