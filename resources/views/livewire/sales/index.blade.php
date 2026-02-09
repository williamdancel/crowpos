<div class="p-6 space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Sales</h1>
            <p class="text-sm text-gray-500">History of completed transactions</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search Sale # or Reference..."
                class="w-full sm:w-72 rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 text-sm
                       focus:border-gray-900 focus:bg-white focus:outline-none"
            />

            <select
                wire:model.live="paymentFilter"
                class="w-full sm:w-48 rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 text-sm
                       focus:border-gray-900 focus:bg-white focus:outline-none"
            >
                <option value="all">All Payments</option>
                <option value="cash">Cash</option>
                <option value="gcash">GCash</option>
                <option value="maya">Maya</option>
                <option value="debit_card">Debit Card</option>
            </select>

            <button
                type="button"
                wire:click="exportCsv"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Export CSV
            </button>
        </div>
    </div>

    {{-- Daily summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="text-sm text-gray-500">Today</div>
            <div class="mt-1 text-2xl font-bold text-gray-900">
                ₱{{ number_format($todayTotal, 2) }}
            </div>
            <div class="mt-1 text-sm text-gray-600">
                {{ $todayCount }} sale(s)
            </div>
        </div>

        <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5">
            <div class="text-sm font-semibold text-gray-900">Last 7 days</div>

            <div class="mt-3 overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Sales</th>
                            <th class="px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($dailyRows as $r)
                            <tr>
                                <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($r['day'])->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ $r['count_sales'] }}</td>
                                <td class="px-4 py-2 text-right font-semibold">
                                    ₱{{ number_format($r['sum_total'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sales table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Sale #</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Payment</th>
                    <th class="px-5 py-3">Reference</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-semibold text-gray-900">#{{ $sale->id }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full border px-3 py-1 text-xs font-semibold text-gray-800">
                                {{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700">{{ $sale->payment_reference ?: '—' }}</td>
                        <td class="px-5 py-3 text-right font-bold text-gray-900">
                            ₱{{ number_format((float)$sale->total, 2) }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a
                                href="{{ route('pos.receipt', $sale->id) }}"
                                class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-800 hover:bg-gray-100"
                            >
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-500">No sales found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4">
            {{ $sales->links() }}
        </div>
    </div>
</div>
