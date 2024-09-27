<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Retrieve all users from the database, paginated and sorted by the specified order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated collection of user models.
     */
    public function getAll(Request $request): Collection
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                'name',
                'email',
            ])
            ->defaultSort('id')
            ->allowedSorts('id', 'name', 'email')
            ->get();
    }

    /**
     * Retrieve a specific user from the database by its ID.
     *
     * @param int $id The unique identifier of the user to retrieve.
     *
     * @return \Illuminate\Database\Eloquent\Model|null The user model with the specified ID, or null if not found.
     *
     */
    public function getById($id)
    {
        return User::find($id);
    }

    /**
     * Registers a new user in the database.
     *
     * This function creates a new user record in the database using the provided data.
     * It also assigns a default role to the newly created user.
     *
     * @param array $data The data to be stored in the user model. The array should contain the necessary fields required for user registration.
     *
     * @return \Illuminate\Database\Eloquent\Model|bool The newly created user model if registration is successful, or false if an error occurs.
     */
    public function register(array $data): \Illuminate\Database\Eloquent\Model | Bool
    {
        $user = User::create($data);
        //By default the user is rol ASSGIN
        $user->assignRole(User::ASSIGN_ROLE);
        if ($user) {
            return $user;
        }
        return false;
    }
}
