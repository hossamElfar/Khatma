<?php

namespace App\Http\Controllers\API;

use App\Khatma;
use App\Part;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class PersonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store', 'update', 'destroy');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required',
            'date_of_death' => 'required',
            'description' => 'required',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $persons = Person::orderBy('id', 'DESC')->paginate(5);
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
            $overall_percentage = ($overall / 30) * 100;
            $person['progress'] = $overall_percentage;
        }
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['cases'] = $persons;
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails())
            return response()->json($validator->errors(), 302);
        $user = Auth::user();
        $t = $request->all();
        $t['user_id'] = $user->id;
        $person = Person::create($t);
        $khatma = new Khatma(['person_id' => $person->id, 'creator_id' => $user->id]);
        $khatma->save();
        $juzs = $this->getParts();
        foreach ($juzs as $juz) {
            $juz['khatma_id'] = $khatma->id;
            $juz['person_id'] = $person->id;
            Part::create($juz);
        }
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['case'] = $person;
        $data['data']['case']['khatma'] = $khatma;
        $data['data']['case']['khatma']['juz'] = $juzs;
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::find($id);
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['case'] = $person;
        return response()->json($data, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails())
            return response()->json($validator->errors(), 302);
        $person = Person::find($id);
        if ($person->user_id == Auth::user()->id) {
            $person->update($request->all());
            $data['statues'] = "200 Ok";
            $data['error'] = null;
            $data['data']['case'] = $person;
            return response()->json($data, 200);
        } else {
            $data['statues'] = "401 unauthorized";
            $data['error'] = "unauthorized";
            $data['data'] = null;
            return response()->json($data, 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::find($id);
        if ($person->user_id == Auth::user()->id) {
            $person->delete();
            $data['statues'] = "200 Ok";
            $data['error'] = null;
            $data['data'] = null;
            return response()->json($data, 200);
        } else {
            $data['statues'] = "401 unauthorized";
            $data['error'] = "unauthorized";
            $data['data'] = null;
            return response()->json($data, 401);
        }
    }

    /**
     * A function to get juzs info
     *
     * @return mixed
     */
    private function getParts()
    {
        $parts = file_get_contents("/home/hossam/projects/khatma/juz.json");
        $parts_decoded = json_decode($parts, true);
        return $parts_decoded;
    }
}
