<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Resources\UserResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request)
    {
        $data = $this->userRepository->getAll($request);

        return $this->sendResponse(new UserResourceCollection($data), 'Showing users');
    }
}
