<div class="p-6 max-w-2xl mx-auto">
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Receipt</h1>
                <p class="text-sm text-gray-500">
                    Sale #{{ $sale->id }} • {{ $sale->created_at->format('M d, Y h:i A') }}
                </p>
            </div>

            <a href="{{ route('pos.index') }}"
               class="rounded-lg border bg-green-500 text-white border-gray-200 px-3 py-2 text-sm font-semibold hover:bg-green-600">
                New Sale
            </a>
        </div>

        <div class="mt-6 space-y-3">
            @foreach($sale->items as $line)
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $line->item_name }}</div>
                        <div class="text-xs text-gray-500">
                            ₱{{ number_format((float)$line->unit_price, 2) }} × {{ $line->qty }}
                        </div>
                    </div>

                    <div class="font-semibold text-gray-900">
                        ₱{{ number_format((float)$line->line_total, 2) }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 border-t pt-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold text-gray-900">₱{{ number_format((float)$sale->subtotal, 2) }}</span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total</span>
                <span class="font-bold text-gray-900">₱{{ number_format((float)$sale->total, 2) }}</span>
            </div>

            <div class="mt-3 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Payment</span>
                    <span class="font-semibold text-gray-900">
                        {{ strtoupper(str_replace('_', ' ', $sale->payment_method)) }}
                    </span>
                </div>

                @if($sale->payment_reference)
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-600">Reference</span>
                        <span class="font-semibold text-gray-900">{{ $sale->payment_reference }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-600">Paid</span>
                    <span class="font-semibold text-gray-900">₱{{ number_format((float)$sale->amount_paid, 2) }}</span>
                </div>

                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-600">Change</span>
                    <span class="font-semibold text-gray-900">₱{{ number_format((float)$sale->change, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-2 justify-end">
            <button onclick="window.print()"
                    class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                Print
            </button>
        </div>
    </div>

    {{-- Print styling --}}
    <style>
        @media print {
            body { background: white !important; }
            a, button { display: none !important; }
            .max-w-2xl { max-width: 100% !important; }
            .border { border: none !important; }
            .rounded-2xl { border-radius: 0 !important; }
        }
    </style>
</div>
