<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartIndex extends Component
{
    public array $chartReceita = [];
    public array $chartStaff = [];
    public array $chartSpace = [];
    public array $chartServicosMes = [];

    public float $totalGanho = 0;
    public float $totalComissao = 0;

    public function mount()
    {
        $this->loadReceitaChart();
        $this->loadStaffChart();
        $this->loadSpaceChart();
        $this->loadServicosMesChart();
        $this->loadTotals();
    }

    public function loadReceitaChart()
    {
        $results = DB::table('appointment_service_items as asi')
            ->join('services as s', 'asi.service_id', '=', 's.id')
            ->select('asi.service_id', 's.name as service_name', DB::raw('SUM(asi.price_charged) as total'))
            ->groupBy('asi.service_id', 's.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $labels = $results->pluck('service_name')->toArray();
        $data = $results->pluck('total')->toArray();
        $colors = ['#3b82f6', '#f97316', '#10b981', '#facc15', '#ef4444'];

        $this->chartReceita = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Receita por Serviço (€)',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                ]],
            ],
            'options' => [
                'responsive' => true,
                'scales' => [
                    'y' => ['beginAtZero' => true],
                ],
                'plugins' => [
                    'legend' => ['display' => true, 'position' => 'top'],
                    'tooltip' => ['enabled' => true],
                ],
            ],
        ];
    }

    public function loadStaffChart()
    {
        $results = DB::table('appointment_service_items as asi')
            ->join('staff as st', 'asi.staff_id', '=', 'st.id')
            ->select('asi.staff_id', 'st.name as staff_name', DB::raw('COUNT(*) as total_servicos'))
            ->groupBy('asi.staff_id', 'st.name')
            ->orderBy('total_servicos', 'desc')
            ->limit(5)
            ->get();

        $labels = $results->pluck('staff_name')->toArray();
        $data = $results->pluck('total_servicos')->toArray();
        $colors = ['#ef4444', '#facc15', '#10b981', '#3b82f6', '#f97316'];

        $this->chartStaff = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Serviços realizados por Staff',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                ]],
            ],
            'options' => [
                'responsive' => true,
                'scales' => [
                    'y' => ['beginAtZero' => true],
                ],
                'plugins' => [
                    'legend' => ['display' => true, 'position' => 'top'],
                    'tooltip' => ['enabled' => true],
                ],
            ],
        ];
    }

    public function loadSpaceChart()
    {
        $results = DB::table('appointment_service_items as asi')
            ->join('appointments as a', 'asi.appointment_id', '=', 'a.id')
            ->join('spaces as sp', 'a.space_id', '=', 'sp.id')
            ->select('a.space_id', 'sp.name as space_name', DB::raw('COUNT(*) as total_servicos'))
            ->groupBy('a.space_id', 'sp.name')
            ->orderBy('total_servicos', 'desc')
            ->limit(5)
            ->get();

        $labels = $results->pluck('space_name')->toArray();
        $data = $results->pluck('total_servicos')->toArray();
        $colors = ['#10b981', '#3b82f6', '#ef4444', '#facc15', '#f97316'];

        $this->chartSpace = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Serviços por Espaço',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                ]],
            ],
            'options' => [
                'responsive' => true,
                'scales' => [
                    'y' => ['beginAtZero' => true],
                ],
                'plugins' => [
                    'legend' => ['display' => true, 'position' => 'top'],
                    'tooltip' => ['enabled' => true],
                ],
            ],
        ];
    }

    public function loadServicosMesChart()
{
    $months = collect();
    for ($i = 11; $i >= 0; $i--) {
        $months->push(Carbon::now()->subMonths($i)->format('Y-m'));
    }

    $results = DB::table('appointment_service_items')
    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
    ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
    ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m') asc")
    ->get()
    ->keyBy('ym');


    $labels = $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->format('M Y'))->toArray();
    $data = $months->map(fn($m) => $results->has($m) ? $results->get($m)->total : 0)->toArray();

    $this->chartServicosMes = [
        'type' => 'line',
        'data' => [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Total de Serviços por Mês',
                'data' => $data,
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                'fill' => true,
                'tension' => 0.3,
            ]],
        ],
        'options' => [
            'responsive' => true,
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
            'plugins' => [
                'legend' => ['display' => true],
                'tooltip' => ['enabled' => true],
            ],
        ],
    ];
}


    public function loadTotals()
    {
        $totals = DB::table('appointment_service_items')
            ->select(
                DB::raw('COALESCE(SUM(price_charged), 0) as total_ganho'),
                DB::raw('COALESCE(SUM(commission_value), 0) as total_comissao')
            )->first();

        $this->totalGanho = round($totals->total_ganho, 2);
        $this->totalComissao = round($totals->total_comissao, 2);
    }

    public function render()
    {
        return view('livewire.dashboard.chart-index');
    }
}
