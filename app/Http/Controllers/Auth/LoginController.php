<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            $request->authenticate();
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $user->getAllPermissions();
            $data['token']  = $user->createToken('MyApp')->plainTextToken;
            $data['user']   = new UserResource($user);

            return $this->sendResponse($data, 'User login successfully.');
        } catch (\Throwable $th) {
            return $this->sendError(
                'Incorrect credentials.',
                ['error'=> $th->getMessage()],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
