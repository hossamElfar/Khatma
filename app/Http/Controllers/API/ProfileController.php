<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('show');
    }

    /**
     * Show the profile of the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
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

    /**
     * View The profile of a specific user
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewProfile($id)
    {
        $user = User::findOrFail($id);
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
