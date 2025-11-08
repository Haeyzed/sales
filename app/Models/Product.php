<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product Model
 *
 * Represents a product in the inventory system.
 * Supports standard products, variants, batches, and digital products.
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string|null $slug
 * @property string $barcodeSymbology
 * @property int|null $brandId
 * @property int $categoryId
 * @property int $unitId
 * @property int $purchaseUnitId
 * @property int $saleUnitId
 * @property float $cost
 * @property float $price
 * @property float|null $wholesalePrice
 * @property float|null $qty
 * @property float|null $alertQuantity
 * @property float|null $dailySaleObjective
 * @property bool|null $promotion
 * @property float|null $promotionPrice
 * @property \Illuminate\Support\Carbon|null $startingDate
 * @property \Illuminate\Support\Carbon|null $lastDate
 * @property int|null $taxId
 * @property int|null $taxMethod
 * @property string|null $image
 * @property string|null $file
 * @property bool|null $isEmbeded
 * @property bool $isBatch
 * @property bool $isVariant
 * @property bool $isDiffPrice
 * @property bool $isImei
 * @property bool|null $featured
 * @property string|null $productList
 * @property string|null $variantList
 * @property string|null $qtyList
 * @property string|null $priceList
 * @property string|null $productDetails
 * @property string|null $shortDescription
 * @property string|null $specification
 * @property string|null $relatedProducts
 * @property string|null $extra
 * @property string|null $menuType
 * @property string|null $variantOption
 * @property string|null $variantValue
 * @property bool $isActive
 * @property bool|null $isOnline
 * @property int|null $kitchenId
 * @property bool|null $inStock
 * @property bool|null $trackInventory
 * @property bool $isSyncDisable
 * @property int|null $woocommerceProductId
 * @property int|null $woocommerceMediaId
 * @property string|null $tags
 * @property string|null $metaTitle
 * @property string|null $metaDescription
 * @property int|null $warranty
 * @property int|null $guarantee
 * @property string|null $warrantyType
 * @property string|null $guaranteeType
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property-read Category $category
 * @property-read Brand|null $brand
 * @property-read Unit $unit
 * @property-read Unit $purchaseUnit
 * @property-read Unit $saleUnit
 * @property-read Tax|null $tax
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Variant> $variants
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductVariant> $productVariants
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductBatch> $batches
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Warehouse> $warehouses
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Purchase> $purchases
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'slug',
        'barcode_symbology',
        'brand_id',
        'category_id',
        'unit_id',
        'purchase_unit_id',
        'sale_unit_id',
        'cost',
        'price',
        'wholesale_price',
        'qty',
        'alert_quantity',
        'daily_sale_objective',
        'promotion',
        'promotion_price',
        'starting_date',
        'last_date',
        'tax_id',
        'tax_method',
        'image',
        'file',
        'is_embeded',
        'is_batch',
        'is_variant',
        'is_diffPrice',
        'is_imei',
        'featured',
        'product_list',
        'variant_list',
        'qty_list',
        'price_list',
        'product_details',
        'short_description',
        'specification',
        'related_products',
        'extra',
        'menu_type',
        'variant_option',
        'variant_value',
        'is_active',
        'is_online',
        'kitchen_id',
        'in_stock',
        'track_inventory',
        'is_sync_disable',
        'woocommerce_product_id',
        'woocommerce_media_id',
        'tags',
        'meta_title',
        'meta_description',
        'warranty',
        'guarantee',
        'warranty_type',
        'guarantee_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'brand_id' => 'integer',
            'category_id' => 'integer',
            'unit_id' => 'integer',
            'purchase_unit_id' => 'integer',
            'sale_unit_id' => 'integer',
            'cost' => 'float',
            'price' => 'float',
            'wholesale_price' => 'float',
            'qty' => 'float',
            'alert_quantity' => 'float',
            'daily_sale_objective' => 'float',
            'promotion' => 'boolean',
            'promotion_price' => 'float',
            'starting_date' => 'date',
            'last_date' => 'date',
            'tax_id' => 'integer',
            'tax_method' => 'integer',
            'is_embeded' => 'boolean',
            'is_batch' => 'boolean',
            'is_variant' => 'boolean',
            'is_diffPrice' => 'boolean',
            'is_imei' => 'boolean',
            'featured' => 'boolean',
            'is_active' => 'boolean',
            'is_online' => 'boolean',
            'kitchen_id' => 'integer',
            'in_stock' => 'boolean',
            'track_inventory' => 'boolean',
            'is_sync_disable' => 'boolean',
            'woocommerce_product_id' => 'integer',
            'woocommerce_media_id' => 'integer',
            'warranty' => 'integer',
            'guarantee' => 'integer',
        ];
    }

    /**
     * Get the category.
     *
     * @return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand.
     *
     * @return BelongsTo<Brand, Product>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the unit.
     *
     * @return BelongsTo<Unit, Product>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the purchase unit.
     *
     * @return BelongsTo<Unit, Product>
     */
    public function purchaseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    /**
     * Get the sale unit.
     *
     * @return BelongsTo<Unit, Product>
     */
    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    /**
     * Get the tax.
     *
     * @return BelongsTo<Tax, Product>
     */
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Get the variants for this product.
     *
     * @return BelongsToMany<Variant>
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'product_variants')
            ->withPivot('id', 'item_code', 'additional_cost', 'additional_price', 'qty', 'position')
            ->withTimestamps();
    }

    /**
     * Get the product variants.
     *
     * @return HasMany<ProductVariant>
     */
    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the batches for this product.
     *
     * @return HasMany<ProductBatch>
     */
    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    /**
     * Get the warehouses that have this product.
     *
     * @return BelongsToMany<Warehouse>
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
            ->withPivot('qty', 'price', 'product_batch_id', 'variant_id', 'imei_number')
            ->withTimestamps();
    }

    /**
     * Get the purchases for this product.
     *
     * @return BelongsToMany<Purchase>
     */
    public function purchases(): BelongsToMany
    {
        return $this->belongsToMany(Purchase::class, 'product_purchases')
            ->withPivot('qty', 'tax', 'tax_rate', 'discount', 'total', 'variant_id', 'product_batch_id', 'imei_number')
            ->withTimestamps();
    }

    /**
     * Get the sales for this product.
     *
     * @return BelongsToMany<Sale>
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'product_sales')
            ->withPivot('qty', 'product_batch_id', 'return_qty', 'net_unit_price', 'tax', 'discount', 'tax_rate', 'total', 'is_delivered', 'variant_id', 'imei_number')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active standard products.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Product> $query
     * @return \Illuminate\Database\Eloquent\Builder<Product>
     */
    public function scopeActiveStandard($query)
    {
        return $query->where([
            ['is_active', true],
            ['type', 'standard'],
        ]);
    }

    /**
     * Scope a query to only include active featured products.
     *
     * @param \Illuminate\Database\Eloquent\Builder<Product> $query
     * @return \Illuminate\Database\Eloquent\Builder<Product>
     */
    public function scopeActiveFeatured($query)
    {
        return $query->where([
            ['is_active', true],
            ['featured', true],
        ]);
    }
}

