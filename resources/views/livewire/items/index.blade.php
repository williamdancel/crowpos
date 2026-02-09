<div class="p-6 space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Items</h1>
            <p class="text-sm text-gray-500">Products / Services</p>
        </div>

        <button
            type="button"
            wire:click="create"
            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
        >
            + New Item
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search items..."
            class="w-full sm:w-72 rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900"
        />

        <select wire:model.live="typeFilter"
            class="w-full sm:w-44 rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
            <option value="all">All Types</option>
            <option value="service">Service</option>
            <option value="product">Product</option>
        </select>

        <select wire:model.live="statusFilter"
            class="w-full sm:w-44 rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="all">All</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Name</th>
                    <th class="px-5 py-3">Price</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <span class="rounded-full border px-2 py-1 text-xs font-semibold text-gray-700">
                                {{ ucfirst($item->type) }}
                            </span>
                        </td>

                        <td class="px-5 py-3 font-medium text-gray-900">{{ $item->name }}</td>

                        <td class="px-5 py-3 text-gray-700">₱{{ $item->price }}</td>

                        <td class="px-5 py-3">
                            <button type="button"
                                wire:click="toggleActive({{ $item->id }})"
                                class="rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $item->is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>

                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    wire:click="edit({{ $item->id }})"
                                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-800 hover:bg-gray-100">
                                    Edit
                                </button>

                                <button type="button"
                                    onclick="confirm('Delete this item?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $item->id }})"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                            No items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4">
            {{ $items->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/40" wire:click="cancel"></div>

            <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $editingId ? 'Edit Item' : 'New Item' }}
                        </h2>
                        <p class="text-sm text-gray-500">Type, name, price, status.</p>
                    </div>

                    <button type="button" wire:click="cancel" class="rounded-lg p-2 hover:bg-gray-100">✕</button>
                </div>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Type</label>
                        <select
                            wire:model.defer="type"
                            class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2
                                text-gray-900
                                focus:border-gray-900 focus:bg-white focus:outline-none focus:ring-0"
                        >
                            <option value="">Select type</option>
                            <option value="service">Service</option>
                            <option value="product">Product</option>
                        </select>
                        @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Product Name</label>
                        <input
                            type="text"
                            wire:model.defer="name"
                            placeholder=""
                            class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2
                                text-gray-900 placeholder-gray-400
                                focus:border-gray-900 focus:bg-white focus:outline-none focus:ring-0"
                        />
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Price</label>
                        <input
                            type="text"
                            wire:model.defer="price"
                            placeholder="0.00"
                            class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2
                                text-gray-900 placeholder-gray-400
                                focus:border-gray-900 focus:bg-white focus:outline-none focus:ring-0"
                        />
                        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model.defer="is_active"
                                class="h-5 w-5 rounded border-2 border-gray-300
                                    text-gray-900 focus:ring-0 focus:ring-offset-0"
                            />
                            <span class="text-sm font-medium text-gray-800">
                                Active
                            </span>
                        </label>
                    </div>
                </div>


                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" wire:click="cancel"
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100">
                        Cancel
                    </button>

                    <button type="button" wire:click="save"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
