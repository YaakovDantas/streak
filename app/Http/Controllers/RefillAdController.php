<?php

namespace App\Http\Controllers;

use App\Models\UserRefillBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefillAdController extends Controller
{
    /**
     * Incrementa o saldo de refis por anúncio.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $balance = $this->getOrCreateRefillBalance($user->id);

        $balance->ad += 1;
        $balance->save();

        return response()->json(['message' => 'Refil de anúncio adicionado com sucesso!']);
    }

    /**
     * Retorna ou cria saldo inicial de refis do usuário.
     */
    private function getOrCreateRefillBalance(int $userId): UserRefillBalance
    {
        return UserRefillBalance::firstOrCreate(
            ['user_id' => $userId],
            ['free' => 0, 'ad' => 0, 'paid' => 0]
        );
    }
}
