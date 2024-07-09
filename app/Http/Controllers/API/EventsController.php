<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CategoryTranslation;
use App\Models\CountryTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function categories(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $categories = CategoryTranslation::query()
            ->where('locale', app()->getLocale());

        if ($search) {
            $categories->where('name', 'like', '%' . $search . '%');
        }

        $res = [];

        foreach ($categories->with('category')->limit(20)->get() as $category) {
            $res[] = [
                'id' => $category->category->id,
                'name' => $category->name,
            ];
        }

        return response()->json($res);
    }

    public function country(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $countries = CountryTranslation::query()
            ->where('locale', app()->getLocale());

        if ($search) {
            $countries->where('name', 'like', '%' . $search . '%');
        }

        $res = [];

        foreach ($countries->with('country')->limit(20)->get() as $country) {
            $res[] = [
                'id' => $country->country->id,
                'name' => $country->name,
            ];
        }

        return response()->json($res);
    }

}
