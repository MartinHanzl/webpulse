<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserMenuOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MenuOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $rows = UserMenuOrder::query()
            ->where('user_id', $request->user()->id)
            ->where('site_id', $siteId)
            ->orderBy('section_key')
            ->orderBy('item_key')
            ->get(['section_key', 'item_key', 'position']);

        return Response::json(['data' => $rows]);
    }

    public function store(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        $validated = $request->validate([
            'rows' => 'required|array',
            'rows.*.section_key' => 'required|string|max:64',
            'rows.*.item_key' => 'nullable|string|max:64',
            'rows.*.position' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $request, $siteId) {
            UserMenuOrder::query()
                ->where('user_id', $request->user()->id)
                ->where('site_id', $siteId)
                ->delete();

            foreach ($validated['rows'] as $row) {
                UserMenuOrder::create([
                    'user_id' => $request->user()->id,
                    'site_id' => $siteId,
                    'section_key' => $row['section_key'],
                    'item_key' => $row['item_key'] ?? '',
                    'position' => $row['position'],
                ]);
            }
        });

        $rows = UserMenuOrder::query()
            ->where('user_id', $request->user()->id)
            ->where('site_id', $siteId)
            ->orderBy('section_key')
            ->orderBy('item_key')
            ->get(['section_key', 'item_key', 'position']);

        return Response::json(['data' => $rows]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $siteId = $this->handleSite($request->header('X-Site-Hash'));

        UserMenuOrder::query()
            ->where('user_id', $request->user()->id)
            ->where('site_id', $siteId)
            ->delete();

        return Response::json([]);
    }
}
