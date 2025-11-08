<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Returns Model
 *
 * Represents a return sale transaction.
 *
 * @property int $id
 * @property string $referenceNo
 * @property int $userId
 * @property int|null $saleId
 * @property int|null $cashRegisterId
 * @property int|null $customerId
 * @property int $warehouseId
 * @property int $billerId
 * @property int|null $accountId
 * @property int|null $currencyId
 * @property float|null $exchangeRate
 * @property int $item
 * @property float $totalQty
 * @property float $totalDiscount
 * @property float $totalTax
 * @property float $totalPrice
 * @property float|null $orderTaxRate
 * @property float|null $orderTax
 * @property float $grandTotal
 * @property string|null $document
 * @property string|null $returnNote
 * @property string|null $staffNote
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read User $user
 * @property-read Sale|null $sale
 * @property-read CashRegister|null $cashRegister
 * @property-read Customer|null $customer
 * @property-read Warehouse $warehouse
 * @property-read Biller $biller
 * @property-read Account|null $account
 * @property-read Currency|null $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductReturn> $productReturns
 */
class Returns extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'returns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_no',
        'user_id',
        'sale_id',
        'cash_register_id',
        'customer_id',
        'warehouse_id',
        'biller_id',
        'account_id',
        'currency_id',
        'exchange_rate',
        'item',
        'total_qty',
        'total_discount',
        'total_tax',
        'total_price',
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
            'user_id' => 'integer',
            'sale_id' => 'integer',
            'cash_register_id' => 'integer',
            'customer_id' => 'integer',
            'warehouse_id' => 'integer',
            'biller_id' => 'integer',
            'account_id' => 'integer',
            'currency_id' => 'integer',
            'exchange_rate' => 'float',
            'item' => 'integer',
            'total_qty' => 'float',
            'total_discount' => 'float',
            'total_tax' => 'float',
            'total_price' => 'float',
            'order_tax_rate' => 'float',
            'order_tax' => 'float',
            'grand_total' => 'float',
        ];
    }

    /**
     * Get the user who created this return.
     *
     * @return BelongsTo<User, Returns>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sale.
     *
     * @return BelongsTo<Sale, Returns>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the cash register.
     *
     * @return BelongsTo<CashRegister, Returns>
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    /**
     * Get the customer.
     *
     * @return BelongsTo<Customer, Returns>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the warehouse.
     *
     * @return BelongsTo<Warehouse, Returns>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the biller.
     *
     * @return BelongsTo<Biller, Returns>
     */
    public function biller(): BelongsTo
    {
        return $this->belongsTo(Biller::class);
    }

    /**
     * Get the account.
     *
     * @return BelongsTo<Account, Returns>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the currency.
     *
     * @return BelongsTo<Currency, Returns>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the product returns.
     *
     * @return HasMany<ProductReturn>
     */
    public function productReturns(): HasMany
    {
        return $this->hasMany(ProductReturn::class, 'return_id');
    }
}

