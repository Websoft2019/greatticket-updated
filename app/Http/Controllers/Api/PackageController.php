<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackageController extends BaseApiController
{
    public function index($e_id)
    {
        $now = now();
        try {
            $packages = Package::where('status', true)->where('event_id', $e_id)->where('capacity', '>','consumed_seat')->paginate(10);
            $packages = [
                'packages' => PackageResource::collection($packages),
                'pagination' => [
                    'total' => $packages->total(),
                    'per_page' => $packages->perPage(),
                    'current_page' => $packages->currentPage(),
                    'last_page' => $packages->lastPage(),
                    'from' => $packages->firstItem(),
                    'to' => $packages->lastItem()
                ]
            ];
            return $this->sendResponse($packages, "Events fetched successfully", 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage(), "Cannot fetch event", 500);
        }
    }

    public function show($e_id, $id) {
        try {
            $package = Package::where('status', true)->where('id',$id)->where('event_id',$e_id)->first();
            $package = new PackageResource($package);
            return $this->sendResponse($package, 'Package fetched successfully', 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage(), 'Cannot fetch package', 500);
        }
    }
}
