<?php

namespace App\Http\Controllers\API;

use App\Part;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PartsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('subscribe');
    }

    /**
     * Show a specific part
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $part = Part::findOrFail($id);
        $end_page = $part->end_page;
        $current_page = $part->current_page;
        $key = $part->number_of_part - 1;
        if ($key == 0) {
            $percentage = 100 - ((($end_page - $current_page) / 20) * 100);
        } else {
            if ($key == 29) {
                $percentage = 100 - ((($end_page - $current_page) / 22) * 100);
            } else {
                if ($key == 24 || $key == 6) {
                    $percentage = 100 - ((($end_page - $current_page) / 20) * 100);
                } else {
                    $percentage = 100 - ((($end_page - $current_page) / 19) * 100);
                }
            }
        }
        $part['progress'] = $percentage;
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['part'] = $part;
        $data['data']['part']['person'] = $part->person;
        if ($part->user_id != null)
            $data['data']['part']['user'] = $part->user()->get();
        else
            $data['data']['part']['user'] = null;
        return response()->json($data, 200);
    }

    /**
     * Subscribe to a part and a khatma
     *
     * @param $part_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe($part_id)
    {
        $user = Auth::user();
        $part = Part::findOrFail($part_id);
        if (!$part->taken){
            $khatma = $part->khatma->id;
            $user->parts()->save($part);
            $part->taken = true;
            $part->save();
            $user->khatma()->attach($khatma);
            $data['statues'] = "200 Ok";
            $data['error'] = null;
            $data['data'] = null;
            return response()->json($data, 200);
        }else{
            $data['statues'] = "304 Not modified";
            $data['error'] = "Taken part";
            $data['data'] = null;
            return response()->json($data, 302);
        }

    }

}
