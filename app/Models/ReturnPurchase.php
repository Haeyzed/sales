<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ReturnPurchase Model
 *
 * Represents a return purchase to a supplier.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $purchaseId
 * @property int $userId
 * @property int $supplierId
 * @property int $warehouseId
 * @property int|null $accountId
 * @property int|null $currencyId
 * @property float|null $exchangeRate
 * @property int $item
 * @property float $totalQty
 * @property float $totalDiscount
 * @property float $totalTax
 * @property float $totalCost
 * @property float|null $orderTaxRate
 * @property float|null $orderTax
 * @property float $grandTotal
 * @property string|null $document
 * @property string|null $returnNote
 * @property string|null $staffNote
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Purchase $purchase
 * @property-read User $user
 * @property-read Supplier $supplier
 * @property-read Warehouse $warehouse
 * @property-read Account|null $account
 * @property-read Currency|null $currency
 */
class ReturnPurchase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'return_purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'purchase_id',
        'user_id',
        'supplier_id',
        'warehouse_id',
        'account_id',
        'currency_id',
        'exchange_rate',
        'item',
        'total_qty',
        'total_discount',
        'total_tax',
        'total_cost',
        'order_tax_rate',
        'order_tax',
        'grand_total',
        'document',
        'return_note',
        'staff_note',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_id' => 'integer',
            'user_id' => 'integer',
            'supplier_id' => 'integer',
            'warehouse_id' => 'integer',
            'account_id' => 'integer',
            'currency_id' => 'integer',
            'exchange_rate' => 'float',
            'item' => 'integer',
            'total_qty' => 'float',
            'total_discount' => 'float',
            'total_tax' => 'float',
            'total_cost' => 'float',
            'order_tax_rate' => 'float',
            'order_tax' => 'float',
            'grand_total' => 'float',
        ];
    }

    /**
     * Get the purchase.
     *
     * @return BelongsTo<Purchase, ReturnPurchase>
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the user who created this return purchase.
     *
     * @return BelongsTo<User, ReturnPurchase>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the supplier.
     *
     * @return BelongsTo<Supplier, ReturnPurchase>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, ReturnPurchase>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the account.
     *
     * @return BelongsTo<Account, ReturnPurchase>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the currency.
     *
     * @return BelongsTo<Currency, ReturnPurchase>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}

