<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionMessageController extends Controller
{
    // チャット画面の表示、メッセージの一覧取得
    // メッセージ送信処理
    // メッセージの編集、削除の処理

    public function show($id)
    {
        $viewData = $this->getChatViewData($id);

        return view('transactions.chat', $viewData);
    }

    public function store(MessageRequest $request, $id)
    {
        $user = Auth::user();
        $transaction = Transaction::findOrFail($id);

        Message::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'content' => $request->input('content'),
            'image' => $request->file('image') ? $request->file('image')->store('messages', 'public') : null,
            'is_read' => false,
        ]);

        return redirect()->route('transaction.chat', ['id' => $id]);
    }

    public function edit($transactionId, $messageId)
    {
        $viewData = $this->getChatViewData($transactionId);

        $editMessage = Message::findOrFail($messageId);
        $viewData['editMessage'] = $editMessage;

        return view('transactions.chat', $viewData);
    }

    public function update(MessageRequest $request, $transactionId, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->user_id !== Auth::id()) {
            return redirect()
                ->route('transaction.chat', ['id' => $transactionId])
                ->with('error', '自分のメッセージ以外は編集できません');
        }

        $message->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('transaction.chat', ['id' => $transactionId]);
    }

    public function destroy($transactionId, $messageId)
    {
        $message = Message::findOrFail($messageId);

        if ($message->user_id !== Auth::id()) {
            return redirect()
                ->route('transaction.chat', ['id' => $transactionId])
                ->with('error', '自分のメッセージ以外は削除できません');
        }

        $message->delete();

        return redirect()->route('transaction.chat', ['id' => $transactionId]);
    }

    private function getChatViewData($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $user = Auth::user();

        $partnerId = $transaction->buyer_id === $user->id
            ? $transaction->seller_id
            : $transaction->buyer_id;

        $partner = User::findOrFail($partnerId);

        // 現在の取引以外の取引中の商品がある場合、その商品名を新着メッセージ順で取得
        $otherTransactions = Transaction::with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
            ->where('id', '!=', $transactionId)
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->get()
            ->sortByDesc(function ($transaction) {
                return optional($transaction->messages->first())->created_at;
            })
            ->values();

        // メッセージを相手と自分のをわけて取得
        $userMessages = $transaction->messages()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $partnerMessages = $transaction->messages()
            ->where('user_id', $partner->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // メッセージの読み込み状態を更新
        $transaction->messages()
            ->where('user_id', $partner->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return compact(
            'transactionId',
            'transaction',
            'user',
            'partner',
            'otherTransactions',
            'userMessages',
            'partnerMessages'
        );
    }
}
