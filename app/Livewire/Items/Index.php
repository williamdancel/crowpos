<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = 'all';   // all|product|service
    public string $statusFilter = 'active'; // active|inactive|all
    public int $perPage = 10;

    public bool $showModal = false;
    public ?int $editingId = null;

    // form fields (match your model)
    public string $type = 'product';
    public string $name = '';
    public string $price = '0.00'; // uses Item::setPriceAttribute -> price_cents
    public bool $is_active = true;
    public ?int $category_id = null;
    public string $categoryFilter = 'all'; 

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
        'statusFilter' => ['except' => 'active'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingTypeFilter(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $item = Item::findOrFail($id);

        $this->editingId = $item->id;
        $this->type = $item->type;
        $this->name = $item->name;
        $this->price = $item->price; // accessor returns formatted string
        $this->category_id = $item->category_id;
        $this->is_active = (bool) $item->is_active;

        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'type' => ['required', 'in:product,service'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ]);

        Item::updateOrCreate(
            ['id' => $this->editingId],
            [
                'type' => $data['type'],
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                'price' => $data['price'],
                'is_active' => $data['is_active'],
            ]
        );

        $this->showModal = false;
        $this->resetForm();

        session()->flash('status', $this->editingId ? 'Item updated.' : 'Item created.');
    }

    public function delete(int $id): void
    {
        Item::findOrFail($id)->delete();
        session()->flash('status', 'Item deleted.');
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $item = Item::findOrFail($id);
        $item->update(['is_active' => ! $item->is_active]);
    }

    public function cancel(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->type = '';
        $this->name = '';
        $this->price = '0.00';
        $this->is_active = true;
        $this->category_id = null;
    }

    public function render()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

       $items = Item::query()
            ->with('category')
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->typeFilter !== 'all', fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('is_active', $this->statusFilter === 'active'))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.items.index', compact('items', 'categories'));
            }
}
