<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Interfaces\DocumentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * Retrieve all documents from the database, paginated and sorted by the specified order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated collection of document models.
     */
    public function getAll(Request $request): Collection
    {
        return QueryBuilder::for(Document::class)
                ->allowedFilters([
                    'relevance',
                    'approval_date',
                    AllowedFilter::scope('starts_before'),
                    AllowedFilter::scope('starts_after'),
                ])
                ->defaultSort('id')
                ->allowedSorts('id', 'name', 'relevance', 'approval_date')
                ->get();
    }

    /**
     * Retrieve a single document by its ID.
     *
     * @param int $id The unique identifier of the document to retrieve.
     *
     * @return ?Document The document model with the specified ID, or null if not found.
     */
    public function find(int $id): ?Document
    {
        return Document::find($id);
    }

    /**
     * Update or create a document in the database.
     *
     * @param int $id The unique identifier of the document to update or create.
     * @param array $data An associative array of key-value pairs representing the document's attributes.
     *
     * @return Document The updated or newly created document model.
     */
    public function create(array $data): Document
    {
        $data['upload_date'] = now()->format('d-m-Y H:i:s');
        return Document::create($data);
    }

    public function update(int $id, array $data): Document
    {
        Document::where(['id' =>  $id])
                ->update($data);

        return Document::find($id);
    }

    /**
     * Delete a document from the database by its unique identifier.
     *
     * @param int $id The unique identifier of the document to delete.
     *
     * @return bool True if the document was successfully deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        return Document::destroy($id);
    }

    /**
     * Retrieve the statistics of document relevance.
     *
     * This method returns the count of documents grouped by their relevance score.
     * The results are ordered by relevance score in ascending order.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of objects, each representing the count of documents with the same relevance score.
     */
    public function relevanceStats()
    {
        return Document::select('relevance', DB::raw('count(*) as total'))
                ->groupBy('relevance')
                ->orderBy('relevance')
                ->get();
    }

    /**
     * Retrieve the monthly approvals statistics of documents.
     *
     * This method returns the count of documents approved in each month of the current year,
     * grouped by their approval month and year. The results are ordered by year in ascending order,
     * then by month in ascending order.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of objects, each representing the count of documents approved in a specific month and year.
     */
    public function monthlyApprovals()
    {
        return Document::select(
                    DB::raw('YEAR(approval_date) as year'),
                    DB::raw('MONTH(approval_date) as month'),
                    DB::raw('count(*) as total')
                )
                ->whereYear('approval_date', '=', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
    }

    /**
     * Retrieve the statistics of document relevance, along with the associated documents.
     *
     * This method returns an array of objects, where each object represents the count of documents with the same relevance score,
     * along with the list of documents that have that relevance score.
     *
     * @return array An array of objects, where each object has the following properties:
     *  - relevance: The relevance score of the documents in the group.
     *  - total: The total count of documents with the same relevance score.
     *  - documents: An array of objects, where each object represents a document with the following properties:
     *      - id: The unique identifier of the document.
     *      - name: The name of the document.
     *      - description: The description of the document.
     *      - relevance: The relevance score of the document.
     *      - approval_date: The date when the document was approved, formatted as 'd-m-Y H:i:s'.
     *      - upload_date: The date when the document was uploaded, formatted as 'd-m-Y H:i:s'.
     *      - pdf_path: The path to the PDF file of the document.
     */
    public function relevanceStatsWithDocuments()
    {
        $relevances = $this->getRelevances();
        $data = [];

        foreach ($relevances as $relevance) {
            $documents = Document::where('relevance', $relevance)->get();

            $data[] = [
                'relevance' => $relevance,
                'total' => $documents->count(),
                'documents' => $documents->map(function ($doc) {
                    return [
                        'id'            => $doc->id,
                        'name'          => $doc->name,
                        'description'   => $doc->description,
                        'relevance'     => $doc->relevance,
                        'approval_date' => $doc->approval_date->format('d-m-Y H:i:s'),
                        'upload_date'   => $doc->upload_date->format('d-m-Y H:i:s'),
                        'pdf_path'      => $doc->pdf_path,
                    ];
                }),
            ];
        }

        return $data;
    }

    /**
     * Retrieve the distinct relevance scores of documents in ascending order.
     *
     * This method returns an array of distinct relevance scores found in the 'relevance' column of the 'Document' model,
     * ordered in ascending order.
     *
     * @return array An array of distinct relevance scores, sorted in ascending order.
     */
    private function getRelevances()
    {
        return Document::select('relevance')
                ->orderBy('relevance', 'asc')
                ->distinct()
                ->pluck('relevance');
    }
}
