<?php

namespace App\Http\Controllers\Admin\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\Cashflow\Cashflow;
use App\Models\Cashflow\CashflowBudget;
use App\Models\Cashflow\CashflowCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CashflowBudgetController extends Controller
{
    public function store(Request $request, ?int $id = null): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'categoryId' => 'nullable|integer|exists:cashflow_categories,id',
            'budget' => 'required|numeric',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:1900,2100',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $categoryId = $id;
        $month = $request->input('month');
        $year = $request->input('year');

        $cashflowBudget = CashflowBudget::query()
            ->where('cashflow_category_id', $categoryId)
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->whereMonth('end_date', $month)
            ->whereYear('end_date', $year)
            ->first();

        if (! $cashflowBudget) {
            $cashflowBudget = new CashflowBudget;
        }

        try {
            DB::beginTransaction();

            $cashflowBudget->fill([
                'amount' => $request->input('budget'),
                'user_id' => $request->user()->id,
                'type' => 'month',
                'start_date' => date('Y-m-d', strtotime("first day of $year-$month")),
                'end_date' => date('Y-m-d', strtotime("last day of $year-$month")),
            ]);
            $cashflowBudget->cashflow_category_id = $categoryId;
            $cashflowBudget->save();

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();

            return Response::json(['errors' => $e->getMessage()], 500);
        }

        return Response::json();
    }

    public function storeTotal(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'totalBudget' => 'required|numeric|min:0',
            'months' => 'required|integer|min:1',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:1900,2100',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $totalBudget = (float) $request->input('totalBudget');
        $months = (int) $request->input('months');
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $userId = $request->user()->id;

        $rangeEnd = Carbon::create($year, $month, 1)->subDay()->endOfDay();
        $rangeStart = Carbon::create($year, $month, 1)->subMonths($months)->startOfDay();

        $categories = CashflowCategory::query()
            ->where('user_id', $userId)
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        if ($categories->isEmpty()) {
            return Response::json(['errors' => 'Nejsou k dispozici žádné kategorie.'], 422);
        }

        $categorySums = $categories->mapWithKeys(function ($category) use ($rangeStart, $rangeEnd) {
            $sum = Cashflow::query()
                ->where('cashflow_category_id', $category->id)
                ->whereBetween('date', [$rangeStart, $rangeEnd])
                ->sum('amount');

            return [$category->id => (float) $sum];
        });

        $sumAll = $categorySums->sum();
        $count = $categories->count();

        try {
            DB::beginTransaction();

            $assigned = 0.0;
            foreach ($categories->values() as $index => $category) {
                if ($index === $count - 1) {
                    $amount = round($totalBudget - $assigned, 2);
                } elseif ($sumAll > 0) {
                    $amount = round($totalBudget * ($categorySums[$category->id] / $sumAll), 2);
                } else {
                    $amount = round($totalBudget / $count, 2);
                }
                $amount = max(0.0, $amount);
                $assigned += $amount;

                $categoryBudget = CashflowBudget::query()
                    ->where('cashflow_category_id', $category->id)
                    ->whereMonth('start_date', $month)
                    ->whereYear('start_date', $year)
                    ->whereMonth('end_date', $month)
                    ->whereYear('end_date', $year)
                    ->first();

                if (! $categoryBudget) {
                    $categoryBudget = new CashflowBudget;
                }

                $categoryBudget->fill([
                    'amount' => $amount,
                    'user_id' => $userId,
                    'type' => 'month',
                    'start_date' => date('Y-m-d', strtotime("first day of $year-$month")),
                    'end_date' => date('Y-m-d', strtotime("last day of $year-$month")),
                ]);
                $categoryBudget->cashflow_category_id = $category->id;
                $categoryBudget->save();
            }

            DB::commit();
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();

            return Response::json(['errors' => $e->getMessage()], 500);
        }

        return Response::json();
    }
}
