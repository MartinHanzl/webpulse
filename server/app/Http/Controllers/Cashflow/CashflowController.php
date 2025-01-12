<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\Cashflow\Cashflow;
use App\Models\Cashflow\CashflowCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CashflowController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id = null): JsonResponse
    {
        $categoryId = $request->input('categoryId');
        $formattedDate = $request->input('formattedDate');
        $records = $request->input('records');
        $type = $request->input('type');

        $validator = Validator::make($request->all(), [
            'categoryId' => 'nullable|integer|exists:cashflow_categories,id',
            'formattedDate' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'type' => 'required|in:expense,income',
            'records' => 'required|array',
            'records.*.description' => 'nullable|string',
            'records.*.amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        foreach ($records as $record) {
            if ($record['amount'] == 0) {
                Cashflow::query()
                    ->where('id', (int)$record['id'])
                    ->delete();
            }

            try {
                DB::beginTransaction();

                if ($record['id']) {
                    $cashflow = Cashflow::query()
                        ->where('user_id', $request->user()->id)
                        ->find($record['id']);
                    if (!$cashflow) {
                        throw new \Exception('Cashflow not found');
                    }
                } else {
                    $cashflow = new Cashflow();
                }

                $cashflow->fill([
                    'amount' => $record['amount'],
                    'description' => $record['description'],
                    'type' => $type,
                    'date' => new \DateTime($formattedDate),
                ]);
                $cashflow->user_id = $request->user()->id;
                if ($categoryId) {
                    $cashflow->cashflow_category_id = $categoryId;
                }
                $cashflow->save();

                DB::commit();
            } catch (\Throwable|\Exception $e) {
                DB::rollBack();
                continue;
            }
        }

        return Response::json();
    }
}
