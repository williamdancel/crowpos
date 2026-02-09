<div class="p-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
        {{-- LEFT: Items --}}
        <div class="w-full lg:w-2/3 space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">POS</h1>
                    <p class="text-sm text-gray-500">Pick items → checkout</p>
                </div>

                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search items..."
                    class="w-full sm:w-80 rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2
                           focus:border-gray-900 focus:bg-white focus:outline-none"
                />
            </div>

            {{-- Category tabs --}}
            <div class="flex flex-wrap gap-2">
                <button
                    wire:click="setCategory(null)"
                    class="rounded-full border px-4 py-2 text-sm font-semibold
                           {{ $activeCategoryId === null ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-200 hover:bg-gray-50' }}">
                    All
                </button>

                @foreach($categories as $cat)
                    <button
                        wire:click="setCategory({{ $cat->id }})"
                        class="rounded-full border px-4 py-2 text-sm font-semibold
                               {{ $activeCategoryId === $cat->id ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-800 border-gray-200 hover:bg-gray-50' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- Items grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                @forelse($items as $item)
                    <button
                        type="button"
                        wire:click="addToCart({{ $item->id }})"
                        class="group rounded-xl border border-gray-200 bg-white p-4 text-left hover:border-gray-300 hover:shadow-sm"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-900 truncate">
                                    {{ $item->name }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ ucfirst($item->type) }} • {{ $item->category?->name ?? '—' }}
                                </div>
                            </div>

                            <div class="text-sm font-bold text-gray-900">
                                ₱{{ number_format((float)$item->price, 2) }}
                            </div>
                        </div>

                        <div class="mt-3 text-xs text-gray-500">
                            Tap to add
                        </div>
                    </button>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center text-gray-500">
                        No items found.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: Cart --}}
        <div class="w-full lg:w-1/3 space-y-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Cart</h2>
                    <button wire:click="clearCart" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                        Clear
                    </button>
                </div>

                @error('cart')
                    <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                        {{ $message }}
                    </div>
                @enderror

                <div class="mt-4 space-y-3">
                    @forelse($cart as $row)
                        <div class="rounded-xl border border-gray-200 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 truncate">{{ $row['name'] }}</div>
                                    <div class="text-xs text-gray-500">₱{{ number_format((float)$row['price'], 2) }} each</div>
                                </div>

                                <div class="text-sm font-bold text-gray-900">
                                    ₱{{ number_format((float)($row['line_total'] ?? ((float)$row['price']*(int)$row['qty'])), 2) }}
                                </div>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button wire:click="dec({{ $row['id'] }})"
                                        class="h-9 w-9 rounded-lg border border-gray-200 hover:bg-gray-50">−</button>

                                    <div class="w-10 text-center font-semibold">{{ $row['qty'] }}</div>

                                    <button wire:click="inc({{ $row['id'] }})"
                                        class="h-9 w-9 rounded-lg border border-gray-200 hover:bg-gray-50">+</button>
                                </div>

                                <button wire:click="remove({{ $row['id'] }})"
                                    class="text-xs font-semibold text-red-700 hover:text-red-900">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-gray-500">
                            No items yet.
                        </div>
                    @endforelse
                </div>

                <div class="mt-5 border-t pt-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($this->subtotal, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between text-base">
                        <span class="text-gray-900 font-semibold">Total</span>
                        <span class="text-gray-900 font-bold">₱{{ number_format($this->total, 2) }}</span>
                    </div>

                    {{-- Payment --}}
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <select wire:model="payment_method"
                            class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 text-sm
                                   focus:border-gray-900 focus:bg-white focus:outline-none">
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="maya">Maya</option>
                            <option value="debit_card">Debit Card</option>
                        </select>

                        <div>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                wire:model.defer="amount_paid"
                                placeholder="Amount paid"
                                class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 text-sm
                                       focus:border-gray-900 focus:bg-white focus:outline-none"
                            />
                            @error('amount_paid')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Reference (non-cash only) --}}
                    @if(in_array($payment_method, ['gcash','maya','debit_card'], true))
                        <div>
                            <input
                                type="text"
                                wire:model.defer="payment_reference"
                                placeholder="Reference number"
                                class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 text-sm
                                       focus:border-gray-900 focus:bg-white focus:outline-none"
                            />
                            @error('payment_reference')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Change</span>
                        <span class="font-semibold {{ $this->change < 0 ? 'text-red-700' : 'text-gray-900' }}">
                            ₱{{ number_format($this->change, 2) }}
                        </span>
                    </div>

                    @if (session('status'))
                        <div class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    <button
                        wire:click="checkout"
                        class="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800"
                        @disabled(count($cart) === 0)
                    >
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
