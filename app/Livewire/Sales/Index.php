<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $paymentFilter = 'all'; // all|cash|gcash|maya|debit_card
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'paymentFilter' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingPaymentFilter(): void { $this->resetPage(); }

    private function baseQuery()
    {
        return Sale::query()
            ->when($this->search !== '', function ($q) {
                $term = trim($this->search);

                $q->where(function ($qq) use ($term) {
                    if (ctype_digit($term)) {
                        $qq->orWhere('id', (int) $term);
                    }
                    $qq->orWhere('payment_reference', 'like', "%{$term}%");
                });
            })
            ->when($this->paymentFilter !== 'all', fn ($q) => $q->where('payment_method', $this->paymentFilter));
    }

    public function exportCsv()
    {
        // Export current filtered results (no pagination)
        $rows = $this->baseQuery()
            ->latest()
            ->get(['id', 'created_at', 'payment_method', 'payment_reference', 'subtotal', 'total', 'amount_paid', 'change']);

        $filename = 'sales_export_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            // Header
            fputcsv($out, [
                'Sale ID', 'Date/Time', 'Payment Method', 'Reference',
                'Subtotal', 'Total', 'Amount Paid', 'Change'
            ]);

            foreach ($rows as $s) {
                fputcsv($out, [
                    $s->id,
                    $s->created_at->format('Y-m-d H:i:s'),
                    $s->payment_method,
                    $s->payment_reference ?? '',
                    (string) $s->subtotal,
                    (string) $s->total,
                    (string) $s->amount_paid,
                    (string) $s->change,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        $sales = $this->baseQuery()
            ->latest()
            ->paginate($this->perPage);

        // Daily totals (last 7 days including today)
        $start = now()->startOfDay()->subDays(6);
        $end = now()->endOfDay();

        // Works on SQLite + MySQL: date(created_at)
        $daily = Sale::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw("date(created_at) as day, count(*) as count_sales, sum(total) as sum_total")
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get()
            ->keyBy('day');

        // Fill missing days with zeros
        $dailyRows = collect();
        for ($i = 0; $i < 7; $i++) {
            $d = $start->copy()->addDays($i)->format('Y-m-d');
            $row = $daily->get($d);

            $dailyRows->push([
                'day' => $d,
                'count_sales' => (int) ($row->count_sales ?? 0),
                'sum_total' => (float) ($row->sum_total ?? 0),
            ]);
        }

        // Today totals (all payments)
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $today = Sale::query()
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->selectRaw('count(*) as count_sales, sum(total) as sum_total')
            ->first();

        return view('livewire.sales.index', [
            'sales' => $sales,
            'todayCount' => (int) ($today->count_sales ?? 0),
            'todayTotal' => (float) ($today->sum_total ?? 0),
            'dailyRows' => $dailyRows,
        ]);
    }
}
