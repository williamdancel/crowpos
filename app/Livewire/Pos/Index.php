<?php

namespace App\Livewire\Pos;

use App\Models\Category;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public ?int $activeCategoryId = null;

    /** cart:
     *  [itemId => ['id','name','price','qty','line_total']]
     */
    public array $cart = [];

    public string $payment_method = 'cash'; // cash|gcash|maya|debit_card
    public ?string $payment_reference = null;
    public string $amount_paid = '0.00';

    public function setCategory(?int $categoryId): void
    {
        $this->activeCategoryId = $categoryId;
    }

    public function addToCart(int $itemId): void
    {
        $item = Item::query()
            ->where('is_active', true)
            ->findOrFail($itemId);

        if (isset($this->cart[$itemId])) {
            $this->cart[$itemId]['qty']++;
        } else {
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'qty' => 1,
            ];
        }

        $this->recalcLine($itemId);
    }

    public function inc(int $itemId): void
    {
        if (!isset($this->cart[$itemId])) return;
        $this->cart[$itemId]['qty']++;
        $this->recalcLine($itemId);
    }

    public function dec(int $itemId): void
    {
        if (!isset($this->cart[$itemId])) return;

        $this->cart[$itemId]['qty']--;

        if ($this->cart[$itemId]['qty'] <= 0) {
            unset($this->cart[$itemId]);
            return;
        }

        $this->recalcLine($itemId);
    }

    public function remove(int $itemId): void
    {
        unset($this->cart[$itemId]);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->payment_method = 'cash';
        $this->payment_reference = null;
        $this->amount_paid = '0.00';
        $this->resetErrorBag();
    }

    private function recalcLine(int $itemId): void
    {
        $qty = (int) ($this->cart[$itemId]['qty'] ?? 1);
        $price = (float) ($this->cart[$itemId]['price'] ?? 0);
        $this->cart[$itemId]['line_total'] = round($qty * $price, 2);
    }

    public function getSubtotalProperty(): float
    {
        $sum = 0.0;
        foreach ($this->cart as $row) {
            $sum += (float) ($row['line_total'] ?? ((float)$row['price'] * (int)$row['qty']));
        }
        return round($sum, 2);
    }

    public function getTotalProperty(): float
    {
        // MVP: no tax/discount yet
        return $this->subtotal;
    }

    public function getChangeProperty(): float
    {
        $paid = (float) $this->amount_paid;
        return round($paid - $this->total, 2);
    }

    public function updatedPaymentMethod(): void
    {
        // If switching back to cash, clear reference
        if ($this->payment_method === 'cash') {
            $this->payment_reference = null;
        }
        $this->resetErrorBag('payment_reference');
    }

    private function requiresReference(): bool
    {
        return in_array($this->payment_method, ['gcash', 'maya', 'debit_card'], true);
    }

    public bool $isCheckingOut = false;

    public function checkout()
    {
        if ($this->isCheckingOut) {
            return; // prevents double-click / double-submit
        }

        if (count($this->cart) === 0) {
            $this->addError('cart', 'Cart is empty.');
            return;
        }

        if ($this->requiresReference() && blank($this->payment_reference)) {
            $this->addError('payment_reference', 'Reference number is required.');
            return;
        }

        $paid = (float) $this->amount_paid;
        if ($paid < $this->total) {
            $this->addError('amount_paid', 'Amount paid is not enough.');
            return;
        }

        $this->isCheckingOut = true;

        try {
            $saleId = DB::transaction(function () use ($paid) {
                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'subtotal' => $this->subtotal,
                    'discount' => 0,
                    'tax' => 0,
                    'total' => $this->total,
                    'payment_method' => $this->payment_method,
                    'payment_reference' => $this->requiresReference() ? trim((string) $this->payment_reference) : null,
                    'amount_paid' => round($paid, 2),
                    'change' => $this->change,
                ]);

                foreach ($this->cart as $row) {
                    $sale->items()->create([
                        'item_id' => $row['id'],
                        'item_name' => $row['name'],
                        'unit_price' => round((float) $row['price'], 2),
                        'qty' => (int) $row['qty'],
                        'line_total' => round((float) ($row['line_total'] ?? ((float)$row['price'] * (int)$row['qty'])), 2),
                    ]);
                }

                return $sale->id;
            });

            $this->clearCart();

            return redirect()->route('pos.receipt', $saleId);
        } finally {
            $this->isCheckingOut = false;
        }
    }

    public function render()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $items = Item::query()
            ->with('category')
            ->where('is_active', true)
            ->when($this->activeCategoryId, fn ($q) => $q->where('category_id', $this->activeCategoryId))
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->limit(80)
            ->get();

        return view('livewire.pos.index', compact('categories', 'items'));
    }
}
