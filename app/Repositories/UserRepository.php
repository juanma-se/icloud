<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Retrieve all users from the database, paginated and sorted by the specified order.
     *
     * @param int $page The page number of the results to retrieve. Defaults to 1.
     * @param int $per_page The number of users per page. Defaults to 25.
     * @param string $order The sorting order. Defaults to 'DESC' (descending).
     *
     * @return \Illuminate\Pagination\Paginator The paginated collection of user models.
     */
    public function getAll($page = 1, $per_page = 25, $order = 'ASC')
    {
        return User::orderBy('id', $order)
                ->paginate(
                    $per_page,
                    ['*'],
                    'users',
                    $page
                );
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
     * Store a new user in the database.
     *
     * @param array $data The data to be stored in the user model.
     *
     * @return \Illuminate\Database\Eloquent\Model The newly created user model.
     */
    public function store(array $data)
    {
        return User::create($data);
    }

    /**
     * Update an existing user in the database.
     *
     * @param int $id The unique identifier of the user to update.
     * @param array $data The data to be updated in the user model.
     *
     * @return bool|null True if the update was successful, false if the user with the specified ID does not exist, or null if an error occurred.
     */
    public function update(array $data, $id)
    {
        return User::find($id)->update($data);
    }

    /**
     * Delete an existing user from the database by its ID.
     *
     * @param int $id The unique identifier of the user to delete.
     *
     * @return bool|null True if the deletion was successful, false if the user with the specified ID does not exist, or null if an error occurred.
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }

    public function register(array $data)
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
