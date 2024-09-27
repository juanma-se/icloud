<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="RegisterRequest",
 *   type="object",
 *   required={"name", "email", "password"},
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     description="Name of the user",
 *     example="John Doe"
 *   ),
 *   @OA\Property(
 *     property="email",
 *     type="string",
 *     format="email",
 *     description="Email address of the user",
 *     example="johndoe@example.com"
 *   ),
 *   @OA\Property(
 *     property="password",
 *     type="string",
 *     format="password",
 *     description="Password for the user",
 *     example="password123"
 *   ),
 *   @OA\Property(
 *     property="password_confirmation",
 *     type="string",
 *     format="password",
 *     description="Password confirmation",
 *     example="password123"
 *   )
 * )
 */
class RegisterController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or registration failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error while registering"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = $this->userRepository->register($request->validated());

        if ($user instanceof Model) {
            return $this->sendResponse([]);
        }
        return $this->sendError("Error while registering", [], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
