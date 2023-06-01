<?php
// register method (Request $request)
$validator = Validator::make($request->all(),[
    'name' => 'required|string',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6'
]);
if($validator->fails()) {
    return response()->json($validator->messages()->add('status', 'fails'));
}
$data = $validator->validated();
$data['password'] = Hash::make($request->password);
$data = User::create($data);
return response()->json(['status' => 'passes', 'data' => $data]);


// login method (Request $request)
$validator = Validator::make($request->all(),[
    'email' => 'required|email',
    'password' => 'required|min:6'
]);
if($validator->fails()) {
    return response()->json($validator->messages()->add('status', 'fails'));
}
if(auth()->attempt($validator->validated())) {

    $user = auth()->user();
    $token = $user->createToken('pos')->plainTextToken;

    return response()->json(['status' => 'passes', 'user' => $user, 'token' => $token]);
}
return response()->json(['status' => Response::HTTP_UNAUTHORIZED]);


// getAuthUser method ()
return response()->json(['status' => 'passes', 'data' => auth()->user()]);

// logout (Request $request)
$deleted = $request->user()->currentAccessToken()->delete();
return response()->json(['status' => $deleted ? 'passes' : 'fails']);
