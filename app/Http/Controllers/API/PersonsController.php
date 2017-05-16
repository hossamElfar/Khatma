<?php

namespace App\Http\Controllers\API;

use App\Khatma;
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
        $khatma = Khatma::create(['person_id'=>$person->id,'creator_id'=>$user->id]);
        //TO DO add 30 parts foreach khatma
        $data['statues'] = "200 Ok";
        $data['error'] = null;
        $data['data']['case'] = $person;
        $data['data']['case']['khatma'] = $khatma;
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
}
