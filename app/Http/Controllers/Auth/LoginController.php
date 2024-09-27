<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

    /**
     *
     * @OA\Tag(
     *     name="Authentication",
     *     description="API Auth Controllers"
     * ),
     *
     * @OAS\SecurityScheme(
     *      securityScheme="bearer_token",
     *      type="http",
     *      scheme="bearer"
     * )
     *
     * @OA\Schema(
     *   schema="UserResource",
     *   type="object",
     *   description="User resource with permissions and roles",
     *   @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="User ID",
     *     example=1
     *   ),
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     description="User name",
     *     example="John Doe"
     *   ),
     *   @OA\Property(
     *     property="email",
     *     type="string",
     *     format="email",
     *     description="User email",
     *     example="john@example.com"
     *   ),
     *   @OA\Property(
     *     property="email_verified_at",
     *     type="string",
     *     format="date-time",
     *     description="Date and time of email verification",
     *     example="2024-09-23T10:00:00Z"
     *   ),
     *   @OA\Property(
     *     property="privilege",
     *     type="array",
     *     description="Array of roles and permissions",
     *     @OA\Items(
     *       type="object",
     *       @OA\Property(
     *         property="role",
     *         type="string",
     *         description="Role name",
     *         example="administrator"
     *       ),
     *       @OA\Property(
     *         property="permissions",
     *         type="array",
     *         @OA\Items(
     *           type="string",
     *           description="Permission name",
     *           example="edit_documents"
     *         )
     *       )
     *     )
     *   )
     * )
     */
class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *     description="Authenticate a user and return a token and user data",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             required={"email", "password"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJhbGciOiJIUzI1NiIsInR5..."
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 ref="#/components/schemas/UserResource"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Incorrect credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Incorrect credentials."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="error", type="string", example="Invalid login attempt.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="error", type="string", example="Something went wrong.")
     *             )
     *         )
     *     )
     * )
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
