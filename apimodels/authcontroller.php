public function register(RegisterRequest $req){
    $u = User::create([
        'name'=>$req->name,
        'email'=>$req->email,
        'password'=>Hash::make($req->password)
    ]);
    $token = $u->createToken('api-token')->plainTextToken;
    return response()->json(['user'=>$u,'token'=>$token],201);
}

public function login(LoginRequest $req){
    $user = User::where('email',$req->email)->first();
    if(!$user || !Hash::check($req->password,$user->password)) {
        return response()->json(['message'=>'Invalid credentials'],401);
    }
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['user'=>$user,'token'=>$token]);
}
