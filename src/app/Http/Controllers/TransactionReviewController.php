<?php

namespace App\Http\Controllers;

use App\Mail\ReviewCompletedMail;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransactionReviewController extends Controller
{
    public function store(Request $request, $transactionId)
    {
        $request->validate([
            'rating' => 'required',
        ]);

        $transaction = Transaction::findOrFail($transactionId);
        $user = Auth::user();

        if ($transaction->buyer_id === $user->id) {
            if ($transaction->status !== Transaction::STATUS_STARTED) {
                return redirect()->route('transaction.chat', ['id' => $transactionId])->with('error', '現在この取引に対するレビューはできません');
            }

            Review::create([
                'transaction_id' => $transaction->id,
                'reviewer_id' => $user->id,
                'reviewee_id' => $transaction->seller_id,
                'rating' => $request->input('rating'),
            ]);

            $transaction->update(['status' => Transaction::STATUS_BUYER_REVIEWED]);

            $seller = $transaction->seller;
            Mail::to($seller->email)->send(new ReviewCompletedMail($transaction, $user));
        } elseif ($transaction->seller_id === $user->id) {
            if ($transaction->status !== Transaction::STATUS_BUYER_REVIEWED) {
                return redirect()->route('transaction.chat', ['id' => $transactionId])->with('error', '購入者のレビューが完了していません');
            }

            Review::create([
                'transaction_id' => $transaction->id,
                'reviewer_id' => $user->id,
                'reviewee_id' => $transaction->buyer_id,
                'rating' => $request->input('rating'),
            ]);

            $transaction->update(['status' => Transaction::STATUS_COMPLETED]);
        } else {
            return redirect()->route('transaction.chat', ['id' => $transactionId])->with('error', 'この取引に対するレビューはできません');
        }

        return redirect()->route('home');
    }
}
