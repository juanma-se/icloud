<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\DocumentRepositoryInterface;

class DocumentStatsController extends Controller
{
    public function __construct(
        private readonly DocumentRepositoryInterface $documentRepository
    ) {}

    public function relevanceStats()
    {
        $data = $this->documentRepository->relevanceStats();

        if (count($data) > 0) {
            return $this->sendResponse($data);
        }
        return $this->sendError('Document Relevance Stats not found',$data);
    }

    public function monthlyApprovals()
    {
        $data = $this->documentRepository->monthlyApprovals();

        if (count($data) > 0) {
            return $this->sendResponse($data);
        }
        return $this->sendError('Document Monthly Stats not found',$data);
    }

    public function relevanceStatsWithDocuments()
    {
        $data = $this->documentRepository->relevanceStatsWithDocuments();

        if (count($data) > 0) {
            return $this->sendResponse($data);
        }
        return $this->sendError('Document Relevance Stats with Documents not found', $data);
    }
}
