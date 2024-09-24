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

    public function index(Request $request) {
        $page       = $request->input('page', 1);
        $per_page   = $request->input('per_page', 10);
        $order      = $request->input('order', 'ASC');

        $data = $this->userRepository->getAll($page, $per_page, $order);

        return $this->sendResponse(new UserResourceCollection($data), 'Showing users');
    }
}
