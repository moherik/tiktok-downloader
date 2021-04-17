<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdsController extends Controller
{
    public function index()
    {
        $ads = \App\Models\AdsCode::orderBy('id', 'ASC')->get();
        return response()->json($ads);
    }

    public function getActiveAds()
    {
        $ads = \App\Models\AdsCode::where('isEnable', 1)->select(['placement', 'code'])->get();
        return response()->json($ads);
    }

    public function store(Request $request)
    {
        $request->validate(['items' => 'required']);
        try {
            foreach ($request->items as $item) {
                \App\Models\AdsCode::updateOrCreate([
                    'id' => $item['id'],
                ],[
                    'placement' => $item['placement'],
                    'type' => $item['type'],
                    'code' => $item['code'],
                    'isEnable' => $item['isEnable'],
                ]);
            }
            return response()->json(['status' => 'OK', 'message' => 'Berhasil menyimpan data']);
        } catch (HttpException $ex) {
            return response()->json([
                'status' => $ex->getStatusCode(),
                'message' => $ex->getMessage(),
            ], $ex->getStatusCode());
        }
    }
}
