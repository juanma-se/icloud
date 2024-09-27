<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\DocumentRepositoryInterface;

    /**
     * @OA\Tag(
     *     name="Document Stats",
     *     description="API DocumentsStats Controller"
     * ),
     *
     * @OAS\SecurityScheme(
     *      securityScheme="bearer_token",
     *      type="http",
     *      scheme="bearer"
     * )
     */
class DocumentStatsController extends Controller
{
    public function __construct(
        private readonly DocumentRepositoryInterface $documentRepository
    ) {}

    /**
     * @OA\Get(
     *   path="/documents/stats/relevance",
     *   summary="Get document relevance statistics",
     *   tags={"Document Stats"},
     *   @OA\Response(
     *     response=200,
     *     description="Document Relevance Stats retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *       @OA\Property(property="message", type="string", example="Document Relevance Stats retrieved successfully.")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Document Relevance Stats not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Document Relevance Stats not found")
     *     )
     *   )
     * )
     */
    public function relevanceStats()
    {
        $data = $this->documentRepository->relevanceStats();

        if (count($data) > 0) {
            return $this->sendResponse($data);
        }
        return $this->sendError('Document Relevance Stats not found',$data);
    }

    /**
     * @OA\Get(
     *   path="/documents/stats/monthly-approvals",
     *   summary="Get document monthly approvals statistics",
     *   tags={"Document Stats"},
     *   @OA\Response(
     *     response=200,
     *     description="Document Monthly Stats retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *       @OA\Property(property="message", type="string", example="Document Monthly Stats retrieved successfully.")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Document Monthly Stats not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Document Monthly Stats not found")
     *     )
     *   )
     * )
     */
    public function monthlyApprovals()
    {
        $data = $this->documentRepository->monthlyApprovals();

        if (count($data) > 0) {
            return $this->sendResponse($data);
        }
        return $this->sendError('Document Monthly Stats not found',$data);
    }

    /**
     * @OA\Get(
     *   path="/documents/stats/relevance-with-documents",
     *   summary="Get document relevance statistics with document details",
     *   tags={"Document Stats"},
     *   @OA\Response(
     *     response=200,
     *     description="Document Relevance Stats with Documents retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="success", type="boolean", example=true),
     *       @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *       @OA\Property(property="message", type="string", example="Document Relevance Stats with Documents retrieved successfully.")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Document Relevance Stats with Documents not found",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Document Relevance Stats with Documents not found")
     *     )
     *   )
     * )
     */
    public function relevanceStatsWithDocuments()
    {
        $data = $this->documentRepository->relevanceStatsWithDocuments();

        if (count($data) > 0) {
            return $this->sendResponse($data);
        }
        return $this->sendError('Document Relevance Stats with Documents not found', $data);
    }
}
