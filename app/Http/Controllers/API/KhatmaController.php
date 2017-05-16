<?php

namespace App\Http\Controllers\API;

use App\Khatma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KhatmaController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth')->only('store', 'update', 'destroy');
    }

    /**
     * Get a list of all khatmas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $khatmas = Khatma::orderBy('id', 'DESC')->paginate(5);
        foreach ($khatmas as $key_1 => $khatma) {
            $overall = 0;
            foreach ($khatma->parts()->get() as $key => $part) {
                $end_page = $part->end_page;
                $current_page = $part->current_page;
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
                $overall += $percentage;
            }
            $overall_percentage = ($overall / 30) * 100;
            $khatma['progress'] = $overall_percentage;
            $khatma['person'] = $khatma->person()->get();
            $khatma['created_by'] = $khatma->creator()->get();
        }
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['katmas'] = $khatmas;
        return response()->json($data, 200);
    }


    /**
     * Show a specific khatma
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $khatma = Khatma::find($id);
        $parts = $khatma->parts()->get();
        foreach ($parts as $key => $part) {
            $end_page = $part->end_page;
            $current_page = $part->current_page;
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
        }
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['khatma'] = $khatma;
        $data['data']['khatma']['person'] = $khatma->person;
        $data['data']['khatma']['created_by'] = $khatma->creator;
        $data['data']['khatma']['parts'] = $parts;
        return response()->json($data, 200);
    }
}
