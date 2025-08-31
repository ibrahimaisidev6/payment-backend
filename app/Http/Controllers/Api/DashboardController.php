<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function stats(Request $request)
    {
        $user = Auth::user();
        $period = $request->input("period", "month");

        $startDate = Carbon::now();
        switch ($period) {
            case "today":
                $startDate = Carbon::today();
                break;
            case "week":
                $startDate = Carbon::now()->startOfWeek();
                break;
            case "month":
                $startDate = Carbon::now()->startOfMonth();
                break;
            case "year":
                $startDate = Carbon::now()->startOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                break;
        }

        $payments = $user->payments()->where("created_at", ">=", $startDate)->get();

        $totalIncome = $payments->where("type", "income")->sum("amount");
        $totalExpense = $payments->where("type", "expense")->sum("amount");
        $netBalance = $totalIncome - $totalExpense;

        $totalPayments = $payments->count();
        $pendingPayments = $payments->where("status", "pending")->count();
        $completedPayments = $payments->where("status", "completed")->count();
        $failedPayments = $payments->where("status", "failed")->count();

        $recentPayments = $user->payments()
            ->orderBy("created_at", "desc")
            ->take(5)
            ->get();

        // Monthly chart data (example for current year)
        $monthlyChart = [];
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::now()->startOfYear()->addMonths($i);
            $income = $user->payments()
                ->where("type", "income")
                ->whereYear("created_at", $month->year)
                ->whereMonth("created_at", $month->month)
                ->sum("amount");
            $expense = $user->payments()
                ->where("type", "expense")
                ->whereYear("created_at", $month->year)
                ->whereMonth("created_at", $month->month)
                ->sum("amount");
            $monthlyChart[] = [
                "month" => $month->format("Y-m"),
                "income" => $income,
                "expense" => $expense,
            ];
        }

        return response()->json([
            "success" => true,
            "data" => [
                "stats" => [
                    "total_income" => $totalIncome,
                    "total_expense" => $totalExpense,
                    "net_balance" => $netBalance,
                    "total_payments" => $totalPayments,
                    "pending_payments" => $pendingPayments,
                    "completed_payments" => $completedPayments,
                    "failed_payments" => $failedPayments,
                    "period" => $period,
                ],
                "recent_payments" => $recentPayments,
                "monthly_chart" => $monthlyChart,
            ],
        ]);
    }
}
