<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterDelivery;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Delivery\Contract\LetterDeliveryServiceInterface;
use Modules\Letter\Contract\LetterServiceInterface;
use Modules\User\Contract\UserServiceInterface;
use Throwable;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly LetterServiceInterface $letterService,
        private readonly LetterDeliveryServiceInterface $letterDeliveryService
    ) {
    }

    public function index()
    {
        try {
            // Statistics
            $totalUsers = User::count();
            $lettersThisMonth = Letter::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
            $deliveriesThisWeek = LetterDelivery::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $newUsersToday = User::whereDate('created_at', today())->count();

            // Chart Data
            $lettersPerDay = Letter::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $deliveryStatusBreakdown = LetterDelivery::select(
                DB::raw("SUM(CASE WHEN delivery_status = 'pending' AND shipped_at IS NULL THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN delivery_status = 'pending' AND shipped_at IS NOT NULL THEN 1 ELSE 0 END) as shipped"),
                DB::raw("SUM(CASE WHEN delivery_status = 'delivered' THEN 1 ELSE 0 END) as delivered")
            )->first();


            // Recent Activity
            $recentLetters = $this->letterService->getAll(
                relations: [Letter::USER],
                queryOptions: ['orderBy' => Letter::CREATED_AT, 'orderType' => 'desc', 'limit' => 5, 'noPagination' => true]
            );

            $recentShipments = $this->letterDeliveryService->getAll(
                relations: ['recipient', 'recipient.letter'],
                queryOptions: ['orderBy' => LetterDelivery::createdAt, 'orderType' => 'desc', 'limit' => 5, 'noPagination' => true]
            );

            $recentUsers = $this->userService->getAll(
                queryOptions: ['orderBy' => User::CREATED_AT, 'orderType' => 'desc', 'limit' => 5, 'noPagination' => true]
            );

            return Inertia::render('Dashboard/Dashboard', [
                'statistics' => [
                    'total_users' => $totalUsers,
                    'letters_this_month' => $lettersThisMonth,
                    'deliveries_this_week' => $deliveriesThisWeek,
                    'new_users_today' => $newUsersToday,
                ],
                'chartData' => [
                    'letters_per_day' => $lettersPerDay,
                    'delivery_status_breakdown' => $deliveryStatusBreakdown,
                ],
                'recentActivity' => [
                    'letters' => $recentLetters,
                    'shipments' => $recentShipments,
                    'users' => $recentUsers,
                ]
            ]);
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}
