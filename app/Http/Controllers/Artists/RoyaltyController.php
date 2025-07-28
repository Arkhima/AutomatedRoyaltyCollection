<?php

namespace App\Http\Controllers\Artists;

use App\Http\Controllers\Controller;
use App\Services\RoyaltyCalculationService;

class RoyaltyController extends Controller
{
    protected $royaltyService;

    public function __construct(RoyaltyCalculationService $royaltyService)
    {
        $this->royaltyService = $royaltyService;
    }

    public function index()
    {
        $royalties = $this->royaltyService->calculateRoyalties();
        return view('Artists.Royalties.index', compact('royalties'));
    }
}
