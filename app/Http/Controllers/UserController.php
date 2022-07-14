<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class UserController extends Controller
{
    public function logout()
    {   
        $user = User::find(Auth::user()->id);
        $tokens =  $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked'=> true]);
        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);

        return response()->json(['status' => true, 'message' => 'User Logout'],200);
    }
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
                    $token = $user->createToken($user['name']);
                    $strToken = $token->accessToken;
                    $expToken = $token->token->expires_at->diffForHumans();
                    return response()->json([
                        'status' => true,
                        'message' => [
                            'user' => $user,
                            'token' => $strToken,
                            'expireTime' => $expToken,
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

        DB::beginTransaction();
        try {
            User::create($data);
            DB::commit();
            return response()->json(['status'=> true,'message' => 'User has been added'],200);
        } catch (\Exception $e) {
            DB::rollBack();
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
       $user = User::find($id);

       if($user) {
           return response()->json(['status' => true,'message' => $user],200);   
        }
        return response()->json(['status' => false,'message' => 'not found'],404);
    }

    public function show_all() 
    {
        $users = User::all();
        return response()->json(['status' => true, 'users' => $users],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['status' => false,'message' => $validator->errors()],422);
        }

        $user = User::find($id);

        if($user) {
            DB::beginTransaction();
            try{
                $user['name'] = $request->name;
                $user->save();
                DB::commit();
                return response()->json(['status' => true,'data' => ['message' => 'User has been updated', 'user' => $user]],200);   
            }catch (\Exception $e){
                DB::rollBack();
                return response()->json(['status' => true,'message' => $e->getMessage()],403);   
            }
        }

        return response()->json(['status' => false,'message' => 'not found'],404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $user = User::find($id);

        if($user) {
            DB::beginTransaction();
            try{
                $user->delete();
                DB::commit();
                return response()->json(['status' => true,'data' => ['message' => 'User has been deleted', 'user' => $user]],200);   
            }catch (\Exception $e){
                DB::rollBack();
                return response()->json(['status' => true,'message' => $e->getMessage()],403);   
            }
        }

        return response()->json(['status' => false,'message' => 'not found'],404);
    }
}
