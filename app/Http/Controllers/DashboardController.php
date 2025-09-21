<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $streak = $user->streak;
        $now = Carbon::now();

        $clickedToday = $this->hasClickedToday($user, $now);
        $weekStreak = $this->getWeekStreak($user, $now);
        $refillBalance = $user->refillBalance ?? ['free' => 0, 'ad' => 0, 'paid' => 0];

        $weekLeaderboard = $this->getLeaderboard(Carbon::now()->subDays(6)->startOfDay());
        $monthLeaderboard = $this->getLeaderboard(Carbon::now()->startOfMonth());
        $allTimeLeaderboard = $this->getLeaderboard(); // sem data = all time

        $badges = $this->getUserBadgesWithProgress($user);
        $countries = Country::all();

        return view('dashboard', compact(
            'streak',
            'weekStreak',
            'refillBalance',
            'clickedToday',
            'weekLeaderboard',
            'monthLeaderboard',
            'allTimeLeaderboard',
            'badges',
            'countries'
        ));
    }

    private function hasClickedToday($user, $now): bool
    {
        return $user->clicks()->whereDate('clicked_at', $now->toDateString())->exists();
    }

    private function getWeekStreak($user, Carbon $now)
    {
        $today = $now->toDateString();
        $startDate = $now->copy()->subDays(6)->toDateString();
        $period = CarbonPeriod::create($startDate, $today);

        $clicks = $user->clicks()
            ->where('type', 'click')
            ->whereDate('clicked_at', '>=', $startDate)
            ->pluck('clicked_at')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        $refilClicks = $user->clicks()
            ->where('type', 'refil')
            ->whereDate('clicked_at', '>=', $startDate)
            ->pluck('clicked_at')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        $refilledDates = $user->refils()
            ->whereDate('refilled_for', '>=', $startDate)
            ->pluck('refilled_for')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        return collect($period)->map(function ($date) use ($clicks, $refilClicks, $refilledDates) {
            $dateStr = $date->toDateString();

            return [
                'day' => $date->locale(app()->getLocale())->translatedFormat('D'),
                'date' => $dateStr,
                'clicked' => in_array($dateStr, $clicks),
                'refilled' => in_array($dateStr, $refilClicks),
                'refill_requested' => in_array($dateStr, $refilledDates),
            ];
        });
    }

    private function getLeaderboard(Carbon $startDate = null)
    {
        $loggedUserId = Auth::id();

        $clicksQuery = DB::table('clicks')
            ->where('type', 'click')
            ->select('user_id', DB::raw('COUNT(*) as count'));

        if ($startDate) {
            $clicksQuery->where('clicked_at', '>=', $startDate);
        }

        // Busca todos os usuários com seus cliques (filtrados ou não)
        $clicks = $clicksQuery->groupBy('user_id')->get();

        // Mapeia para coleção com nome e contagem
        $leaderboard = $clicks
            ->map(function ($click) {
                $user = User::find($click->user_id);
                return (object)[
                    'user_id' => $click->user_id,
                    'name' => $user?->name ?? 'Usuário desconhecido',
                    'clicks_count' => $click->count,
                ];
            })
            ->sortByDesc('clicks_count')
            ->values();

        // Pega top 3
        $top3 = $leaderboard->take(3);

        // Verifica se o usuário logado já está no top 3
        $isInTop3 = $top3->contains(fn($item) => $item->user_id === $loggedUserId);

        $userData = null;

        if (! $isInTop3) {
            // Encontra a posição do usuário logado na lista completa
            $position = $leaderboard->search(fn($item) => $item->user_id === $loggedUserId);

            // Ajusta para posição humana (index 0 = posição 1)
            $positionHuman = $position === false ? null : $position + 1;

            // Pega a contagem do usuário logado para exibir junto
            $userClicksCount = $position !== false ? $leaderboard[$position]->clicks_count : 0;

            $userData = [
                'position' => $positionHuman,
                'clicks_count' => $userClicksCount,
            ];
        }

        return [
            'top3' => $top3,
            'user' => $userData, // null se estiver no top3
        ];
    }

    private function getUserBadgesWithProgress($user)
    {
        return Badge::with('requirement')->get()->map(function ($badge) use ($user) {
            $hasBadge = $user->badges->contains('id', $badge->id);
            $requirement = $badge->requirement;
            $progress = 0;

            if ($requirement) {
                switch ($requirement->type) {
                    case 'clicks':
                        $userCount = $user->clicks()->where('type', 'click')->count();
                        $progress = min(100, intval(($userCount / $requirement->target) * 100));
                        break;

                    case 'streak':
                        $userStreak = $user->getStreakCount();
                        $progress = min(100, intval(($userStreak / $requirement->target) * 100));
                        break;

                    case 'refills':
                        $refillCount = $user->refils()->count();
                        $progress = min(100, intval(($refillCount / $requirement->target) * 100));
                        break;
                }
            }

            return (object) [
                'id' => $badge->id,
                'name' => $badge->name,
                'slug' => $badge->slug,
                'hasBadge' => $hasBadge,
                'progress' => $progress,
            ];
        });
    }



}
