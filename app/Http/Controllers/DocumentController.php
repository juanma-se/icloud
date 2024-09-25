<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DocumentResource;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Interfaces\DocumentRepositoryInterface;
use App\Http\Resources\DocumentResourceCollection;

/**
 * @OA\Tag(
 *     name="Documents",
 *     description="API Endpoints for Documents"
 * )
 */
class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentRepositoryInterface $documentRepository
    ) {}

    /**
     * @OA\Get(
     *     path="/documents",
     *     summary="Get all documents",
     *     tags={"Documents"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of documents",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentResourceCollection")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="relevance",
     *         in="query",
     *         description="Filter by relevance",
     *         @OA\Schema(type="string")
     *     )
     *     @OA\Parameter(
     *         name="approval_date",
     *         in="query",
     *         description="Filter by aproval date",
     *         @OA\Schema(type="string")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $documents = $this->documentRepository->getAll($request);

        return $this->sendResponse(new DocumentResourceCollection($documents), 'Showing documents');
    }

    /**
     * @OA\Get(
     *     path="/documents/{id}",
     *     summary="Get a document by ID",
     *     tags={"Documents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the document",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Document not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        $document = $this->documentRepository->find($id);

        if (!$document) {
            return $this->sendError('Document not found');
        }

        return $this->sendResponse(new DocumentResource($document), 'Document retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/documents",
     *     summary="Create a new document",
     *     tags={"Documents"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateDocumentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Document created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function create(CreateDocumentRequest $request)
    {
        $document = $this->documentRepository->create($request->all());

        if ($document) {
            return $this->sendResponse(new DocumentResource($document), 'Document created successfully');
        }
        return $this->sendError('Failed to create document', [], 400);
    }

    /**
     * @OA\Put(
     *     path="/documents/{id}",
     *     summary="Update an existing document",
     *     tags={"Documents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the document to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateDocumentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function update(int $id, UpdateDocumentRequest $request)
    {
        $document = $this->documentRepository->update($id, $request->all());

        if ($document) {
            return $this->sendResponse(new DocumentResource($document), 'Document update successfully');
        }
        return $this->sendError('Failed to update document', [], 400);

    }

    /**
     * @OA\Delete(
     *     path="/documents/{id}",
     *     summary="Delete a document by ID",
     *     tags={"Documents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the document to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        if ($this->documentRepository->delete($id)) {
            return $this->sendResponse([], 'Document deleted successfully');
        }
        return $this->sendError('Failed to delete document', [], 400);

    }
}
