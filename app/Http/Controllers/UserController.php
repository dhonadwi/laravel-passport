<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => true, 'message' => 'v1'],200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => false,'message' => $validator->errors()],422);
        }

        try {
            $user = User::where('email', $request->email)->first();
            if($user) {
                if(Hash::check($request->password,$user->password)) {
                    $token = $user->createToken($request->name)->accessToken;
                    return response()->json([
                        'status' => true,
                        'message' => [
                            'user' => $user,
                            'token' => $token
                        ],
                        ]);
                     }
            }
            return response()->json(['status' => false, 'message' => 'Email / Password failed'], 403);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()],403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json(['status' => false,'message' => $validator->errors()],422);
        }
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        try {
            User::create($data);
            return response()->json(['status'=> true,'message' => 'User has been added'],200);
        } catch (\Exception $e) {
            return response()->json(['status'=> false,'message' => $e->getMessage()],403);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
