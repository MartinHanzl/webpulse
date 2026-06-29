<?php

namespace App\Http\Controllers\Admin\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\Cashflow\Cashflow;
use App\Models\Currency\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CashflowController extends Controller
{
    public function store(Request $request, ?int $id = null): JsonResponse
    {
        $categoryId = $request->input('categoryId');
        $currencyId = $request->has('currencyId') ? $request->input('currencyId') : 1;
        $formattedDate = $request->input('formattedDate');
        $records = $request->input('records', []);
        $type = $request->input('type');

        $validator = Validator::make($request->all(), [
            'categoryId' => 'nullable|integer|exists:cashflow_categories,id',
            'currencyId' => 'nullable|integer|exists:currencies,id',
            'formattedDate' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'type' => 'required|in:expense,income',
            'records' => 'required|array|min:1',
            'records.*.id' => 'nullable|integer',
            'records.*.description' => 'nullable|string',
            'records.*.amount' => 'required|numeric',
            'records.*.is_repeated' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $savedIds = [];
        $errors = [];

        foreach ($records as $record) {
            $recordId = $record['id'] ?? null;
            $amount = (float) ($record['amount'] ?? 0);
            $description = $record['description'] ?? null;
            $isRepeated = (bool) ($record['is_repeated'] ?? false);

            if ($amount == 0 || $description === '0') {
                if ($recordId) {
                    Cashflow::query()
                        ->where('id', (int) $recordId)
                        ->where('user_id', $request->user()->id)
                        ->delete();
                }
                continue;
            }

            DB::beginTransaction();
            try {
                if ($recordId) {
                    $cashflow = Cashflow::query()
                        ->where('user_id', $request->user()->id)
                        ->find($recordId);
                    if (! $cashflow) {
                        throw new \Exception('Cashflow not found');
                    }
                } else {
                    $cashflow = new Cashflow;
                }

                if ($currencyId && $currencyId != 1) {
                    $currency = Currency::find($currencyId);
                    if (! $currency) {
                        throw new \Exception('Currency not found');
                    }
                    $amount = $currency->convertToBase($amount);
                }

                $cashflow->fill([
                    'amount' => $amount,
                    'description' => $description,
                    'date' => new \DateTime($formattedDate),
                    'is_repeated' => $isRepeated,
                ]);
                $cashflow->type = $type;
                $cashflow->user_id = $request->user()->id;
                if ($categoryId && $type != 'income') {
                    $cashflow->cashflow_category_id = $categoryId;
                }
                $cashflow->save();

                DB::commit();
                $savedIds[] = $cashflow->id;
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('Cashflow save failed', [
                    'error' => $e->getMessage(),
                    'record' => $record,
                    'user_id' => $request->user()->id,
                ]);
                $errors[] = $e->getMessage();
            }
        }

        if (empty($savedIds) && !empty($errors)) {
            return Response::json([
                'message' => 'Nepodařilo se uložit žádný záznam.',
                'errors' => $errors,
            ], 422);
        }

        return Response::json([
            'saved' => $savedIds,
            'errors' => $errors,
        ]);
    }
}
