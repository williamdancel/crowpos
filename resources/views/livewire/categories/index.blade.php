<div class="p-6 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Categories</h1>
            <p class="text-sm text-gray-500">Item grouping for POS</p>
        </div>

        <button wire:click="create"
            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            + New Category
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <input
        type="text"
        wire:model.live="search"
        placeholder="Search categories..."
        class="w-64 rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2
               focus:border-gray-900 focus:bg-white focus:outline-none"
    />

    <div class="overflow-hidden rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3">Name</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $category->name }}</td>

                        <td class="px-5 py-3">
                            <button wire:click="toggleActive({{ $category->id }})"
                                class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $category->is_active ? 'bg-green-50 text-green-700 border' : 'bg-gray-100 text-gray-700 border' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>

                        <td class="px-5 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $category->id }})"
                                    class="rounded-lg border px-3 py-1.5 text-xs font-semibold hover:bg-gray-100">
                                    Edit
                                </button>

                                <button
                                    onclick="confirm('Delete this category?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $category->id }})"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-gray-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-4">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/40" wire:click="cancel"></div>

            <div class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold mb-4">
                    {{ $editingId ? 'Edit Category' : 'New Category' }}
                </h2>

                <input
                    type="text"
                    wire:model.defer="name"
                    placeholder="Category name"
                    class="w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2
                           focus:border-gray-900 focus:bg-white focus:outline-none"
                />

                <label class="mt-4 flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.defer="is_active"
                        class="h-5 w-5 rounded border-2 border-gray-300">
                    Active
                </label>

                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="cancel"
                        class="rounded-lg border px-4 py-2 text-sm">
                        Cancel
                    </button>
                    <button wire:click="save"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm text-white">
                        Save
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
