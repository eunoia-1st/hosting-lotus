<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\EmployeeShift;
use App\Models\QuestionCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. rentang waktu (default 30 hari terakhir)
        $start = $request->get('start_date')
            ? Carbon::parse($request->get('start_date'))->startOfDay()
            : Carbon::now()->subDays(29)->startOfDay(); // 30 hari termasuk hari ini
        $end = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        if ($start->gt($end)) {
            // swap jika user salah input
            [$start, $end] = [$end, $start];
        }

        // summary cards
        $totalFeedback = Feedback::count();
        $totalCategory = QuestionCategory::count();
        $totalCustomer = Customer::count();

        // feedback masuk hari ini (untuk tabel kecil)
        $todayFeedback = Feedback::with(['customer', 'seat'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Feedback per kategori dalam rentang waktu (include kategori tanpa feedback)
        // left join dipakai agar kategori yg tidak punya feedback tetap tampil dengan total = 0
        $categoryCounts = DB::table('question_categories as qc')
            ->leftJoin('questions as q', 'q.question_category_id', '=', 'qc.id')
            ->leftJoin('answers as a', 'a.question_id', '=', 'q.id')
            ->leftJoin('feedbacks as f', function ($join) use ($start, $end) {
                $join->on('f.id', '=', 'a.feedback_id')
                    ->whereBetween('f.created_at', [$start, $end]);
            })
            ->select('qc.id', 'qc.name', DB::raw('COUNT(DISTINCT f.id) as total'))
            ->groupBy('qc.id', 'qc.name')
            ->orderByDesc('total')
            ->get();

        // 3. label tanggal (array tanggal string Y-m-d)
        $labels = [];
        $dt = $start->copy();
        while ($dt->lte($end)) {
            $labels[] = $dt->format('Y-m-d');
            $dt->addDay();
        }

        // 4. ambil counts per kategori per tanggal
        $rows = DB::table('question_categories as qc')
            ->leftJoin('questions as q', 'q.question_category_id', '=', 'qc.id')
            ->leftJoin('answers as a', 'a.question_id', '=', 'q.id')
            ->leftJoin('feedbacks as f', function ($join) use ($start, $end) {
                $join->on('f.id', '=', 'a.feedback_id')
                    ->whereBetween('f.created_at', [$start, $end]);
            })
            ->select('qc.id', 'qc.name', DB::raw('DATE(f.created_at) as date'), DB::raw('COUNT(DISTINCT f.id) as total'))
            ->groupBy('qc.id', 'qc.name', DB::raw('DATE(f.created_at)'))
            ->orderBy('qc.name')
            ->get();

        // transform rows -> series: [ 'Kategori A' => [counts per date], ... ]
        $series = [];
        // init series with zeros for all categories present in categoryCounts
        foreach ($categoryCounts as $cat) {
            $series[$cat->name] = array_fill(0, count($labels), 0);
        }

        // fill series using rows
        foreach ($rows as $r) {
            if ($r->date === null) continue; // no feedback that date
            $catName = $r->name;
            $idx = array_search($r->date, $labels);
            if ($idx !== false && isset($series[$catName])) {
                $series[$catName][$idx] = (int) $r->total;
            }
        }

        // 5. pilih top N kategori untuk line chart agar tidak overload (misal top 6)
        $topN = 6;
        $topCategoryNames = $categoryCounts->pluck('name')->take($topN)->toArray();

        // buat datasets untuk Chart.js (line)
        $palette = [
            '#3A7BD5',
            '#FF7A59',
            '#A33336',
            '#6C8E6B',
            '#F2C94C',
            '#9B51E0',
            '#56CCF2',
            '#FF9F40',
            '#2D9CDB',
            '#EB5757'
        ];
        $lineDatasets = [];
        $i = 0;
        foreach ($topCategoryNames as $name) {
            $lineDatasets[] = [
                'label' => $name,
                'data' => $series[$name] ?? array_fill(0, count($labels), 0),
                'borderColor' => $palette[$i % count($palette)],
                'backgroundColor' => 'transparent',
                'tension' => 0.2,
                'fill' => false,
            ];
            $i++;
        }

        // datasets untuk bar chart (per kategori total)
        $barLabels = $categoryCounts->pluck('name')->toArray();
        $barData = $categoryCounts->pluck('total')->map(function ($v) {
            return (int) $v;
        })->toArray();

        // top / bottom categories (for quick badges)
        $topCategory = $categoryCounts->first();
        $bottomCategory = $categoryCounts->sortBy('total')->first();

        // 7. Waktu sekarang
        $now = Carbon::now();
        $currentTime = $now->format('d-m-Y H:i:s');
        $today = strtolower($now->format('l')); // misal: monday

        // 8. Karyawan aktif sekarang (nama, posisi, shift type)
        $activeEmployees = EmployeeShift::with('employee')
            ->where('day', $today)
            ->where(function ($q) use ($now) {
                $q->where(function ($sub) use ($now) {
                    $sub->where('start_time', '<=', $now->format('H:i:s'))
                        ->where('end_time', '>=', $now->format('H:i:s'));
                })
                    ->orWhere(function ($sub) use ($now) {
                        $sub->whereNotNull('start_time_2')
                            ->whereNotNull('end_time_2')
                            ->where('start_time_2', '<=', $now->format('H:i:s'))
                            ->where('end_time_2', '>=', $now->format('H:i:s'));
                    });
            })
            ->get();


        return view('dashboard.main', compact(
            'totalFeedback',
            'totalCategory',
            'totalCustomer',
            'todayFeedback',
            'categoryCounts',
            'labels',
            'lineDatasets',
            'barLabels',
            'barData',
            'topCategory',
            'bottomCategory',
            'start',
            'end',
            'currentTime',
            'activeEmployees',
        ));
    }
}
