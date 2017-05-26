<?php

namespace App\Http\Controllers\API;

use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('progress');
    }

    /**
     * Get the top latest cases
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        $persons = Person::latest()->take(5)->get();
        foreach ($persons as $key_1 => $person) {
            $khatma = $person->khatma()->get()[0];
            $person['khatma_id'] = $khatma->id;
            $overall = 0;
            foreach ($khatma->parts as $key => $part) {
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
            $overall_percentage = ($overall / 30);
            $person['progress'] = $overall_percentage;
        }
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['cases'] = $persons;
        return response()->json($data, 200);
    }

    /**
     * View the progress of the user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function progress()
    {
        $user = Auth::user();
        $parts = $user->parts()->get();
        foreach ($parts as $part) {
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
        }
        $khatmas = $user->khatma()->get();
        foreach ($khatmas as $khatma) {
            $khatma_parts = $khatma->parts()->get();
            $overall = 0;
            foreach ($khatma_parts as $part) {
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
                $overall += $percentage;
            }
            $khatma['progress'] = $overall / 30;
        }
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['user'] = $user;
        $data['data']['user']['parts'] = $parts;
        $data['data']['user']['khatmas'] = $khatmas;
        return response()->json($data, 200);
    }
}
