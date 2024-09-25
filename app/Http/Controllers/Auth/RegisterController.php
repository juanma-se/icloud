<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class RegisterController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user = $this->userRepository->register($request->validated());

        if ($user instanceof Model) {
            return $this->sendResponse($user);
        }
        return $this->sendError("Error while registering", [], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
