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
        $this->middleware('auth')->only('subscribe','addPage');
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
        if (!$part->taken) {
            $khatma = $part->khatma->id;
            $user->parts()->save($part);
            $part->taken = true;
            $part->save();
            $user->khatma()->attach($khatma);
            $data['statues'] = "200 Ok";
            $data['error'] = null;
            $data['data'] = null;
            return response()->json($data, 200);
        } else {
            $data['statues'] = "304 Not modified";
            $data['error'] = "Taken part";
            $data['data'] = null;
            return response()->json($data, 302);
        }

    }

    /**
     * Add pages to the current reading Part
     *
     * @param $part_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPage($part_id, Request $request)
    {
        $user = Auth::user();
        $part = Part::findOrFail($part_id);
        if ($part->user_id != $user->id) {
            $data['statues'] = "401 Unauthorized";
            $data['error'] = "Unauthorized action";
            $data['data'] = null;
            return response()->json($data, 401);
        } else {
            $start_page = $part->start_page;
            $end_page = $part->end_page;
            $current_page = $part->current_page;
            $request_data = $request->all();
            $add_page = intval($request_data['number']);
            if ($add_page > 0) {
                if ($current_page + $add_page > $end_page) {
                    $data['statues'] = "304 Not modified";
                    $data['error'] = "Cannot override the end page of this Juz";
                    $data['data'] = null;
                    return response()->json($data, 302);
                } else {
                    $part->current_page += $add_page;
                    $part->save();
                    $data['statues'] = "200 Ok";
                    $data['error'] = null;
                    $data['data'] = null;
                    return response()->json($data, 200);
                }
            } else {
                if ($current_page + $add_page < $start_page) {
                    $data['statues'] = "304 Not modified";
                    $data['error'] = "Cannot override the start page of this Juz";
                    $data['data'] = null;
                    return response()->json($data, 302);
                } else {
                    $part->current_page += $add_page;
                    $part->save();
                    $data['statues'] = "200 Ok";
                    $data['error'] = null;
                    $data['data'] = null;
                    return response()->json($data, 200);
                }
            }
        }

    }

}
