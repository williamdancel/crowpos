<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public bool $is_active = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->is_active = $category->is_active;

        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        Category::updateOrCreate(
            ['id' => $this->editingId],
            $data
        );

        $this->showModal = false;
        $this->resetForm();

        session()->flash('status', $this->editingId ? 'Category updated.' : 'Category created.');
    }

    public function delete(int $id)
    {
        Category::whereKey($id)->delete();
        session()->flash('status', 'Category deleted.');
    }

    public function toggleActive(int $id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);
    }

    public function cancel()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.categories.index', [
            'categories' => Category::query()
                ->when($this->search !== '', fn ($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                )
                ->latest()
                ->paginate(10),
        ]);
    }
}
