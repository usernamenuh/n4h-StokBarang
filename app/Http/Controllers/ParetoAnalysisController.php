<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\ParetoAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParetoAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', Carbon::now()->format('Y-m'));
        $type = $request->get('type', 'customer');
        $category = $request->get('category');

        $analysisResults = $this->getAnalysisResults($type, $period, $category);
        $availablePeriods = $this->getAvailablePeriods();

        return view('pareto.index', compact('analysisResults', 'availablePeriods', 'period', 'type', 'category'));
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'type' => 'required|in:customer,barang',
            'period' => 'nullable|string'
        ]);

        $type = $request->input('type');
        $period = $request->input('period', Carbon::now()->format('Y-m'));

        try {
            if ($type === 'customer') {
                $result = $this->analyzeCustomers($period);
            } else {
                $result = $this->analyzeBarang($period);
            }

            return redirect()->back()->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Analysis failed: ' . $e->getMessage());
        }
    }

    public function analyzeCustomers($period = null)
    {
        $period = $period ?? Carbon::now()->format('Y-m');
        
        // Clear existing analysis for this period
        ParetoAnalysis::where('analysis_period', $period)
            ->where('item_type', 'customer')
            ->delete();

        // Get customer data from transaksi table
        $customers = Transaksi::select('customer as customer_name', 
                DB::raw('SUM(total) as total_value'),
                DB::raw('COUNT(*) as transaction_count'))
            ->when($period, function($query) use ($period) {
                $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$period]);
            })
            ->groupBy('customer')
            ->orderBy('total_value', 'desc')
            ->get();

        if ($customers->isEmpty()) {
            return ['message' => 'No customer transaction data found for analysis in period ' . $period];
        }

        $totalValue = $customers->sum('total_value');
        $cumulativeValue = 0;
        $analysisData = [];

        foreach ($customers as $index => $customer) {
            $cumulativeValue += $customer->total_value;
            $percentage = ($customer->total_value / $totalValue) * 100;
            $cumulativePercentage = ($cumulativeValue / $totalValue) * 100;
            
            $abcCategory = $this->determineABCCategory($cumulativePercentage);
            
            $analysisData[] = [
                'analysis_period' => $period,
                'item_type' => 'customer',
                'item_id' => $index + 1,
                'item_name' => $customer->customer_name,
                'total_value' => $customer->total_value,
                'percentage' => round($percentage, 2),
                'cumulative_percentage' => round($cumulativePercentage, 2),
                'abc_category' => $abcCategory,
                'rank_position' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        ParetoAnalysis::insert($analysisData);

        return [
            'message' => 'Customer Pareto analysis completed successfully',
            'period' => $period,
            'total_customers' => count($analysisData),
            'categories' => $this->getCategorySummary($analysisData)
        ];
    }

    public function analyzeBarang($period = null)
    {
        $period = $period ?? Carbon::now()->format('Y-m');
        
        // Clear existing analysis for this period
        ParetoAnalysis::where('analysis_period', $period)
            ->where('item_type', 'barang')
            ->delete();

        // Use barang hbeli as value since we don't have detailed transaction items
        $barang = Barang::select('id', 'nama', 'hbeli as total_value', 'kode')
            ->orderBy('total_value', 'desc')
            ->get();

        if ($barang->isEmpty()) {
            return ['message' => 'No barang data found for analysis'];
        }

        $totalValue = $barang->sum('total_value');
        $cumulativeValue = 0;
        $analysisData = [];

        foreach ($barang as $index => $item) {
            $cumulativeValue += $item->total_value;
            $percentage = ($item->total_value / $totalValue) * 100;
            $cumulativePercentage = ($cumulativeValue / $totalValue) * 100;
            
            $abcCategory = $this->determineABCCategory($cumulativePercentage);
            
            $analysisData[] = [
                'analysis_period' => $period,
                'item_type' => 'barang',
                'item_id' => $item->id,
                'item_name' => $item->nama . ' (' . $item->kode . ')',
                'total_value' => $item->total_value,
                'percentage' => round($percentage, 2),
                'cumulative_percentage' => round($cumulativePercentage, 2),
                'abc_category' => $abcCategory,
                'rank_position' => $index + 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        ParetoAnalysis::insert($analysisData);

        return [
            'message' => 'Barang Pareto analysis completed successfully',
            'period' => $period,
            'total_barang' => count($analysisData),
            'categories' => $this->getCategorySummary($analysisData)
        ];
    }

    private function determineABCCategory($cumulativePercentage)
    {
        if ($cumulativePercentage <= 80) {
            return 'A';
        } elseif ($cumulativePercentage <= 95) {
            return 'B';
        } else {
            return 'C';
        }
    }

    private function getCategorySummary($analysisData)
    {
        $categories = ['A' => 0, 'B' => 0, 'C' => 0];
        
        foreach ($analysisData as $item) {
            $categories[$item['abc_category']]++;
        }
        
        return $categories;
    }

    public function getAnalysisResults($type, $period = null, $category = null)
    {
        $query = ParetoAnalysis::where('item_type', $type);
        
        if ($period) {
            $query->where('analysis_period', $period);
        }
        
        if ($category) {
            $query->where('abc_category', $category);
        }
        
        return $query->orderBy('rank_position')->get();
    }

    public function getAvailablePeriods($type = null)
    {
        $query = ParetoAnalysis::select('analysis_period')->distinct();
            
        if ($type) {
            $query->where('item_type', $type);
        }
        
        return $query->orderBy('analysis_period', 'desc')->pluck('analysis_period');
    }
}
