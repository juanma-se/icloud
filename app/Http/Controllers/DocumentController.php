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
     *     description="API Documents Controller"
     * ),
     * @OA\Info(title="API for Icloud", version="0.0.1")
     * @OAS\SecurityScheme(
     *      securityScheme="bearer_token",
     *      type="http",
     *      scheme="bearer"
     * )
     * @OA\Schema(
     *   schema="DocumentResource",
     *   type="object",
     *   description="A single document resource with metadata",
     *   @OA\Property(
     *     property="data",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=100),
     *     @OA\Property(property="name", type="string", example="Amet debitis qui enim."),
     *     @OA\Property(property="description", type="string", example="Placeat eveniet et facilis similique. Quo quas quo cum dignissimos possimus et dolores."),
     *     @OA\Property(property="relevance", type="string", example="media"),
     *     @OA\Property(property="approval_date", type="string", format="date-time", example="05-10-2022 00:00:00"),
     *     @OA\Property(property="upload_date", type="string", format="date-time", example="30-07-2024 00:00:00"),
     *     @OA\Property(property="pdf_path", type="string", example="documents/slHq5FuhBb.pdf")
     *   )
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
     *     security={{"bearer_token":{}}},
     *     tags={"Documents"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of documents",
     *     ),
     *     @OA\Parameter(
     *         name="filter[relevance]",
     *         in="query",
     *         description="Filter by relevance. Posible filters are 'alta' 'media' and 'baja'",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[starts_before]",
     *         in="query",
     *         description="Filter by aproval date. Date must be in YYYY-MM-DD format",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[starts_after]",
     *         in="query",
     *         description="Filter by aproval date. Date must be in YYYY-MM-DD format",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sorting documents by id, name, relevance or approval date. Adding - indicates descending order of results. Ex.: -id",
     *         @OA\Schema(type="string")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $documents = $this->documentRepository->getAll($request);
        if ($documents->isEmpty()) {
            return $this->sendResponse([], 'No documents found');
        }
        return $this->sendResponse(new DocumentResourceCollection($documents), 'Showing documents');
    }

    /**
     * @OA\Get(
     *     path="/documents/{id}",
     *     summary="Get a document by ID",
     *     security={{"bearer_token":{}}},
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
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/DocumentResource"
     *             ),
     *             @OA\Property(property="message", type="string", example="Docuement retrieve successfully"),
     *         )
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
     *     security={{"bearer_token":{}}},
     *     tags={"Documents"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="Name of the document"),
     *             @OA\Property(property="description", type="string", description="Description of the document"),
     *             @OA\Property(property="relevance", type="string", enum={"alta", "media", "baja"}, description="Relevance of the document"),
     *             @OA\Property(property="approval_date", type="string", format="date", description="Approval date of the document"),
     *             @OA\Property(property="upload_date", type="string", format="date", description="Upload date of the document"),
     *             @OA\Property(property="pdf_path", type="string", description="Path to the PDF file")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Document created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     )
     * )
     */
    public function create(CreateDocumentRequest $request)
    {
        $this->updloadFile($request);
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
     *     security={{"bearer_token":{}}},
     *     tags={"Documents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the document to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Document updated successfully"
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
     *     security={{"bearer_token":{}}},
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

    private function updloadFile(&$request)
    {
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = bin2hex(openssl_random_pseudo_bytes(8));
            $extension = $file->extension();
            $file_store = $filename. '.'. $extension;

            $request->merge([
                'pdf_path' => $file->storeAs('pdf-docs', $file_store, 'public'),
            ]);
        }
    }
}
