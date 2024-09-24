<?php

namespace App\Repositories;

use App\Models\Document;
use App\Interfaces\DocumentRepositoryInterface;

class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * Retrieve all documents from the database, paginated and sorted by the specified order.
     *
     * @param int $page The page number of the results to retrieve. Defaults to 1.
     * @param int $per_page The number of documents per page. Defaults to 25.
     * @param string $order The sorting order. Defaults to 'DESC' (descending).
     *
     * @return \Illuminate\Pagination\Paginator The paginated collection of document models.
     */
    public function getAll($page = 1, $per_page = 25, $order = 'ASC')
    {
        return Document::orderBy('id', $order)
                ->paginate(
                    $per_page,
                    ['*'],
                    'documents',
                    $page
                );
    }

    /**
     * Retrieve a specific document from the database by its ID.
     *
     * @param int $id The unique identifier of the document to retrieve.
     *
     * @return \Illuminate\Database\Eloquent\Model|null The document model with the specified ID, or null if not found.
     *
     */
    public function getById($id)
    {
        return Document::find($id);
    }

    /**
     * Store a new document in the database.
     *
     * @param array $data The data to be stored in the document model.
     *
     * @return \Illuminate\Database\Eloquent\Model The newly created document model.
     */
    public function store(array $data)
    {
        return Document::create($data);
    }

    /**
     * Update an existing document in the database.
     *
     * @param int $id The unique identifier of the document to update.
     * @param array $data The data to be updated in the document model.
     *
     * @return bool|null True if the update was successful, false if the document with the specified ID does not exist, or null if an error occurred.
     */
    public function update(array $data, $id)
    {
        return Document::find($id)->update($data);
    }

    /**
     * Delete an existing document from the database by its ID.
     *
     * @param int $id The unique identifier of the document to delete.
     *
     * @return bool|null True if the deletion was successful, false if the document with the specified ID does not exist, or null if an error occurred.
     */
    public function destroy($id)
    {
        return Document::destroy($id);
    }
}
