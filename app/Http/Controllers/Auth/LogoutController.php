<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * @OA\Post(
     *   path="/logout",
     *   summary="Logout the authenticated user",
     *   tags={"Authentication"},
     *   security={{"bearer_token":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="User logout successfully",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="User logout successfully.")
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Unauthorized")
     *     )
     *   )
     * )
     */
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user **/
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'User logout successfully.');
    }
}
