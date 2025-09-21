<?php

namespace App\Http\Controllers;

use App\Models\Refill;
use App\Models\UserRefillBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefillController extends Controller
{
    /**
     * Ganhar refil por assistir anúncio.
     */
    public function earnAd(Request $request)
    {
        $user = Auth::user();
        $balance = $this->getOrCreateRefillBalance($user->id);

        $balance->ad += 1;
        $balance->save();

        return back()->with('message', 'Refil de anúncio adicionado!');
    }

    /**
     * Comprar refil (simulação sem gateway de pagamento).
     */
    public function purchase(Request $request)
    {
        $user = Auth::user();
        $balance = $this->getOrCreateRefillBalance($user->id);

        // Aqui entraria a lógica com Stripe ou outro gateway.
        $balance->paid += 1;
        $balance->save();

        return back()->with('message', 'Refil comprado com sucesso!');
    }

    /**
     * Usar refil para cobrir um dia perdido.
     */
    public function use(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $user = Auth::user();
        $date = $request->input('date');

        // Verifica se já usou refil para esse dia
        if (Refill::where('user_id', $user->id)->where('refilled_for', $date)->exists()) {
            return back()->with('message', 'Já foi usado um refil para esse dia.');
        }

        $balance = $this->getOrCreateRefillBalance($user->id);
        $type = null;

        if ($balance->free > 0) {
            $balance->free--;
            $type = 'free';
        } elseif ($balance->ad > 0) {
            $balance->ad--;
            $type = 'ad';
        } elseif ($balance->paid > 0) {
            $balance->paid--;
            $type = 'paid';
        } else {
            return back()->with('message', 'Você não tem refis disponíveis.');
        }

        $balance->save();

        Refill::create([
            'user_id' => $user->id,
            'type' => $type,
            'refilled_for' => $date,
            'used_at' => now(),
        ]);

        return back()->with('message', "Refil usado com sucesso para o dia $date.");
    }

    /**
     * Cria ou retorna o saldo de refis do usuário.
     */
    private function getOrCreateRefillBalance(int $userId): UserRefillBalance
    {
        return UserRefillBalance::firstOrCreate(
            ['user_id' => $userId],
            ['free' => 0, 'ad' => 0, 'paid' => 0]
        );
    }
}
