<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = $this->getDashboardStats();
        $recentSales = $this->getRecentSales();
        $barangs = $this->getBarangs();
        $transaksis = $this->getTransaksis();
        $salesData = $this->getMonthlySalesData();
        $categoryData = $this->getCategoryData();
        
        return view('dashboard.index', [
            'totalBarang' => $stats['totalBarang'],
            'totalTransaksi' => $stats['totalTransaksi'],
            'totalRevenue' => $stats['totalRevenue'],
            'stokMenipis' => $stats['stokMenipis'],
            'recentSales' => $recentSales,
            'recentSalesCount' => count($recentSales),
            'barangs' => $barangs,
            'transaksis' => $transaksis,
            'salesData' => $salesData,
            'categoryData' => $categoryData
        ]);
    }

    private function getDashboardStats()
    {
        try {
            // Total Barang
            $totalBarang = Barang::count() ?? 0;
            
            // Total Transaksi
            $totalTransaksi = Transaksi::count() ?? 0;
            
            // Total Revenue (dari total transaksi)
            $totalRevenue = Transaksi::sum('total') ?? 0;
            
            // Stok Menipis (barang dengan does_pcs < 10)
            $stokMenipis = Barang::where('does_pcs', '<', 10)->count() ?? 0;
            
            return [
                'totalBarang' => $totalBarang,
                'totalTransaksi' => $totalTransaksi,
                'totalRevenue' => $totalRevenue,
                'stokMenipis' => $stokMenipis,
            ];
        } catch (\Exception $e) {
            return [
                'totalBarang' => 0,
                'totalTransaksi' => 0,
                'totalRevenue' => 0,
                'stokMenipis' => 0,
            ];
        }
    }

    private function getMonthlySalesData()
    {
        try {
            $currentYear = Carbon::now()->year;
            $salesData = [];
            
            // Get sales data for each month of current year
            for ($month = 1; $month <= 12; $month++) {
                $monthlyTotal = Transaksi::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->sum('total') ?? 0;
                
                $salesData[] = $monthlyTotal;
            }
            
            return $salesData;
        } catch (\Exception $e) {
            // Return sample data if error
            return [12000000, 19000000, 15000000, 25000000, 22000000, 30000000, 28000000, 35000000, 32000000, 40000000, 38000000, 45000000];
        }
    }

    private function getCategoryData()
    {
        try {
            $categories = Barang::select('golongan', DB::raw('count(*) as total'))
                ->groupBy('golongan')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            $totalItems = Barang::count();
            $categoryData = [];
            $colors = ['bg-blue-500', 'bg-purple-500', 'bg-green-500', 'bg-orange-500', 'bg-gray-500'];
            
            foreach ($categories as $index => $category) {
                $percentage = $totalItems > 0 ? round(($category->total / $totalItems) * 100) : 0;
                
                $categoryData[] = [
                    'name' => $category->golongan ?: 'Tidak Berkategori',
                    'count' => $category->total,
                    'percentage' => $percentage,
                    'color' => $colors[$index] ?? 'bg-gray-500'
                ];
            }
            
            return $categoryData;
        } catch (\Exception $e) {
            // Return sample data if error
            return [
                ['name' => 'Elektronik', 'count' => 45, 'percentage' => 35, 'color' => 'bg-blue-500'],
                ['name' => 'Fashion', 'count' => 32, 'percentage' => 25, 'color' => 'bg-purple-500'],
                ['name' => 'Makanan', 'count' => 28, 'percentage' => 22, 'color' => 'bg-green-500'],
                ['name' => 'Kesehatan', 'count' => 15, 'percentage' => 12, 'color' => 'bg-orange-500'],
                ['name' => 'Lainnya', 'count' => 8, 'percentage' => 6, 'color' => 'bg-gray-500']
            ];
        }
    }

    private function getRecentSales()
    {
        try {
            // Get recent transactions with customer data
            $recentTransactions = Transaksi::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            $sales = [];
            foreach ($recentTransactions as $transaction) {
                $sales[] = [
                    'name' => $transaction->customer ?: ($transaction->user->name ?? 'Unknown Customer'),
                    'email' => $transaction->user->email ?? 'no-email@example.com',
                    'amount' => $transaction->total,
                    'date' => $transaction->created_at->format('d M Y')
                ];
            }

            // If no transactions, get recent barang data as fallback
            if (empty($sales)) {
                $recentData = Barang::with('user')
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();

                foreach ($recentData as $data) {
                    $sales[] = [
                        'name' => $data->user->name ?? 'Unknown User',
                        'email' => $data->user->email ?? 'no-email@example.com',
                        'amount' => $data->hbeli,
                        'item' => $data->nama,
                        'date' => $data->created_at->format('d M Y')
                    ];
                }
            }

            // If still no data, return sample data
            if (empty($sales)) {
                return [
                    [
                        'name' => 'Olivia Martin',
                        'email' => 'olivia.martin@email.com',
                        'amount' => 1999000,
                        'date' => Carbon::now()->format('d M Y')
                    ],
                    [
                        'name' => 'Jackson Lee',
                        'email' => 'jackson.lee@email.com',
                        'amount' => 39000,
                        'date' => Carbon::now()->subDay()->format('d M Y')
                    ],
                    [
                        'name' => 'Isabella Nguyen',
                        'email' => 'isabella.nguyen@email.com',
                        'amount' => 299000,
                        'date' => Carbon::now()->subDays(2)->format('d M Y')
                    ],
                    [
                        'name' => 'William Kim',
                        'email' => 'will@email.com',
                        'amount' => 99000,
                        'date' => Carbon::now()->subDays(3)->format('d M Y')
                    ]
                ];
            }

            return $sales;
        } catch (\Exception $e) {
            // Return sample data on error
            return [
                [
                    'name' => 'Olivia Martin',
                    'email' => 'olivia.martin@email.com',
                    'amount' => 1999000,
                    'date' => Carbon::now()->format('d M Y')
                ],
                [
                    'name' => 'Jackson Lee',
                    'email' => 'jackson.lee@email.com',
                    'amount' => 39000,
                    'date' => Carbon::now()->subDay()->format('d M Y')
                ],
                [
                    'name' => 'Isabella Nguyen',
                    'email' => 'isabella.nguyen@email.com',
                    'amount' => 299000,
                    'date' => Carbon::now()->subDays(2)->format('d M Y')
                ],
                [
                    'name' => 'William Kim',
                    'email' => 'will@email.com',
                    'amount' => 99000,
                    'date' => Carbon::now()->subDays(3)->format('d M Y')
                ]
            ];
        }
    }

    private function getBarangs()
    {
        try {
            return Barang::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getTransaksis()
    {
        try {
            return Transaksi::with(['barang', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function getStats()
    {
        $stats = $this->getDashboardStats();
        return response()->json($stats);
    }

    public function getSalesData()
    {
        $salesData = $this->getMonthlySalesData();
        return response()->json($salesData);
    }

    public function getCategoryStats()
    {
        $categoryData = $this->getCategoryData();
        return response()->json($categoryData);
    }
}
