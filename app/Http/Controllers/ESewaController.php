<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ESewaController extends Controller
{
    public function esewaSuccess(Request $request)
    {
        $decodedString = base64_decode($request->get('data'));
        $data = json_decode($decodedString, true);

        if (array_key_exists("status", $data)) {
            $status = $data["status"];
            if ($status === "COMPLETE") {
                dd("It is completed");
            }
        }

        dd($request->all());
    }

    public function esewaError(Request $request)
    {
        dd($request->all());
    }
}
