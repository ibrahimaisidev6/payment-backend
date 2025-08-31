<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function index(Request $request)
    {
        $payments = Auth::user()->payments();

        if ($request->has("status")) {
            $payments->where("status", $request->status);
        }

        if ($request->has("type")) {
            $payments->where("type", $request->type);
        }

        if ($request->has("date_from")) {
            $payments->whereDate("created_at", ">=", $request->date_from);
        }

        if ($request->has("date_to")) {
            $payments->whereDate("created_at", "<=", $request->date_to);
        }

        if ($request->has("amount_min")) {
            $payments->where("amount", ">=", $request->amount_min);
        }

        if ($request->has("amount_max")) {
            $payments->where("amount", "<=", $request->amount_max);
        }

        $perPage = $request->input("per_page", 15);
        $payments = $payments->paginate($perPage);

        return response()->json([
            "success" => true,
            "data" => [
                "payments" => $payments->items(),
                "pagination" => [
                    "current_page" => $payments->currentPage(),
                    "per_page" => $payments->perPage(),
                    "total" => $payments->total(),
                    "last_page" => $payments->lastPage(),
                    "from" => $payments->firstItem(),
                    "to" => $payments->lastItem(),
                ],
            ],
        ]);
    }

    public function show(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Accès non autorisé",
            ], 403);
        }

        return response()->json([
            "success" => true,
            "data" => [
                "payment" => $payment,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "amount" => "required|numeric|min:0.01",
            "type" => "required|in:income,expense",
            "description" => "required|string|max:255",
            "reference" => "nullable|string|max:100|unique:payments",
        ]);

        $payment = Auth::user()->payments()->create($request->all());

        return response()->json([
            "success" => true,
            "message" => "Paiement créé avec succès",
            "data" => [
                "payment" => $payment,
            ],
        ], 201);
    }

    public function update(Request $request, Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Accès non autorisé",
            ], 403);
        }

        $request->validate([
            "amount" => "sometimes|required|numeric|min:0.01",
            "type" => "sometimes|required|in:income,expense",
            "status" => "sometimes|required|in:pending,completed,failed",
            "description" => "sometimes|required|string|max:255",
            "reference" => "nullable|string|max:100|unique:payments,reference," . $payment->id,
        ]);

        $payment->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Paiement modifié avec succès",
            "data" => [
                "payment" => $payment,
            ],
        ]);
    }

    public function destroy(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Accès non autorisé",
            ], 403);
        }

        $payment->delete();

        return response()->json([
            "success" => true,
            "message" => "Paiement supprimé avec succès",
        ]);
    }
}
