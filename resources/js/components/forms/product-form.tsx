"use client"

import * as React from "react"
import { useForm } from "@inertiajs/react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { Combobox } from "@/components/ui/combobox"
import { DatePicker } from "@/components/ui/date-picker"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Checkbox } from "@/components/ui/checkbox"
import { Spinner } from "@/components/ui/spinner"
import InputError from "@/components/input-error"
import { Plus, X, RefreshCw } from "lucide-react"
import {
    PRODUCT_TYPES,
    BARCODE_SYMBOLOGIES,
    TAX_METHODS,
    WARRANTY_GUARANTEE_TYPES,
} from "@/constants/product-options"
import {
    ResponsiveDialog,
    ResponsiveDialogContent,
    ResponsiveDialogDescription,
    ResponsiveDialogFooter,
    ResponsiveDialogHeader,
    ResponsiveDialogTitle,
    ResponsiveDialogTrigger,
    ResponsiveDialogClose,
} from "@/components/ui/responsive-dialog"
import { CategoryForm } from "./category-form"

interface ProductFormProps {
    initialData?: {
        id?: number
        name?: string
        code?: string
        type?: string
        barcode_symbology?: string
        category_id?: number | string
        unit_id?: number | string | null
        purchase_unit_id?: number | string | null
        sale_unit_id?: number | string | null
        brand_id?: number | string | null
        tax_id?: number | string | null
        tax_method?: string
        cost?: number | string
        price?: number | string
        wholesale_price?: number | string
        alert_quantity?: number | string
        daily_sale_objective?: number | string
        product_details?: string
        warranty?: number | string
        warranty_type?: string
        guarantee?: number | string
        guarantee_type?: string
        featured?: boolean
        is_embeded?: boolean
        is_batch?: boolean
        is_variant?: boolean
        is_diffPrice?: boolean
        is_imei?: boolean
        promotion?: boolean
        promotion_price?: number | string
        starting_date?: string
        last_date?: string
        image?: string
    }
    brands: Array<{ id: number; title: string }>
    categories: Array<{ id: number; name: string }>
    units: Array<{ id: number; unit_name: string; base_unit: number | null }>
    taxes: Array<{ id: number; name: string }>
    warehouses: Array<{ id: number; name: string }>
    productsWithoutVariant?: Array<{ id: number; name: string; code: string }>
    productsWithVariant?: Array<{ id: number; name: string; code: string }>
    customFields?: Array<{
        id: number
        name: string
        type: string
        is_required: boolean
        default_value: string
        option_value: string
        grid_value: number
        is_admin: boolean
    }>
    onSubmit: (data: Record<string, unknown>) => void
    onCancel?: () => void
    onCategoryCreated?: (category: Record<string, unknown>) => void
    submitLabel?: string
    cancelLabel?: string
    processing?: boolean
    errors?: Record<string, string>
    showActions?: boolean
    className?: string
}

export function ProductForm({
    initialData,
    brands,
    categories,
    units,
    taxes,
    warehouses,
    productsWithoutVariant = [],
    // productsWithVariant = [],
    customFields = [],
    onSubmit,
    onCancel,
    onCategoryCreated,
    submitLabel = "Save Product",
    cancelLabel = "Cancel",
    processing = false,
    errors = {},
    showActions = true,
    className,
}: ProductFormProps) {
    const [isVariant, setIsVariant] = React.useState(initialData?.is_variant || false)
    const [isDiffPrice, setIsDiffPrice] = React.useState(initialData?.is_diffPrice || false)
    const [isInitialStock, setIsInitialStock] = React.useState(false)
    const [isPromotion, setIsPromotion] = React.useState(initialData?.promotion || false)
    const [variantOptions, setVariantOptions] = React.useState<Array<{ option: string; value: string }>>([
        { option: "", value: "" },
    ])
    const [comboProducts, setComboProducts] = React.useState<
        Array<{ product_id: string; qty: string; unit_price: string }>
    >([])
    const [diffPrices, setDiffPrices] = React.useState<Array<{ warehouse_id: string; price: string }>>(
        warehouses.map((w) => ({ warehouse_id: w.id.toString(), price: "" }))
    )
    const [initialStocks, setInitialStocks] = React.useState<Array<{ warehouse_id: string; qty: string }>>(
        warehouses.map((w) => ({ warehouse_id: w.id.toString(), qty: "" }))
    )
    const [categoryDialogOpen, setCategoryDialogOpen] = React.useState(false)
    const [brandDialogOpen, setBrandDialogOpen] = React.useState(false)
    const [taxDialogOpen, setTaxDialogOpen] = React.useState(false)

    const baseUnits = units.filter((u) => u.base_unit === null)
    const saleUnits = units.filter((u) => u.base_unit !== null || units.some((b) => b.id === u.base_unit))

    const { data, setData } = useForm({
        name: initialData?.name || "",
        code: initialData?.code || "",
        type: (initialData?.type as string) || "standard",
        barcode_symbology: initialData?.barcode_symbology || "C128",
        category_id: initialData?.category_id?.toString() || "",
        unit_id: initialData?.unit_id?.toString() || "",
        purchase_unit_id: initialData?.purchase_unit_id?.toString() || "",
        sale_unit_id: initialData?.sale_unit_id?.toString() || "",
        brand_id: initialData?.brand_id?.toString() || "",
        tax_id: initialData?.tax_id?.toString() || "",
        tax_method: initialData?.tax_method || "1",
        cost: initialData?.cost?.toString() || "",
        price: initialData?.price?.toString() || "",
        wholesale_price: initialData?.wholesale_price?.toString() || "",
        alert_quantity: initialData?.alert_quantity?.toString() || "",
        daily_sale_objective: initialData?.daily_sale_objective?.toString() || "",
        product_details: initialData?.product_details || "",
        warranty: initialData?.warranty?.toString() || "",
        warranty_type: initialData?.warranty_type || "months",
        guarantee: initialData?.guarantee?.toString() || "",
        guarantee_type: initialData?.guarantee_type || "months",
        featured: initialData?.featured || false,
        is_embeded: initialData?.is_embeded || false,
        is_batch: initialData?.is_batch || false,
        is_variant: false,
        is_diffPrice: false,
        is_imei: initialData?.is_imei || false,
        promotion: false,
        promotion_price: initialData?.promotion_price?.toString() || "",
        starting_date: initialData?.starting_date || "",
        last_date: initialData?.last_date || "",
        file: null as File | null,
        variant_option: [] as string[],
        variant_value: [] as string[],
        product_id: [] as string[],
        product_qty: [] as string[],
        unit_price: [] as string[],
        warehouse_id: [] as string[],
        diff_price: [] as string[],
        stock_warehouse_id: [] as string[],
        stock: [] as string[],
        is_initial_stock: false,
    })

    const generateCode = async () => {
        try {
            const response = await fetch("/products/generate-code")
            const code = await response.text()
            setData("code", code.trim())
        } catch (error) {
            console.error("Error generating code:", error)
        }
    }

    const addVariantOption = () => {
        setVariantOptions([...variantOptions, { option: "", value: "" }])
    }

    const removeVariantOption = (index: number) => {
        setVariantOptions(variantOptions.filter((_, i) => i !== index))
    }

    const updateVariantOption = (index: number, field: "option" | "value", value: string) => {
        const updated = [...variantOptions]
        updated[index][field] = value
        setVariantOptions(updated)
    }

    const addComboProduct = () => {
        setComboProducts([...comboProducts, { product_id: "", qty: "", unit_price: "" }])
    }

    const removeComboProduct = (index: number) => {
        setComboProducts(comboProducts.filter((_, i) => i !== index))
    }

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()

        const formData = {
            ...data,
            is_variant: isVariant,
            is_diffPrice: isDiffPrice,
            is_initial_stock: isInitialStock,
            promotion: isPromotion,
            variant_option: isVariant ? variantOptions.map((v) => v.option) : [],
            variant_value: isVariant ? variantOptions.map((v) => v.value) : [],
            product_id: data.type === "combo" ? comboProducts.map((c) => c.product_id) : [],
            product_qty: data.type === "combo" ? comboProducts.map((c) => c.qty) : [],
            unit_price: data.type === "combo" ? comboProducts.map((c) => c.unit_price) : [],
            warehouse_id: isDiffPrice ? diffPrices.map((d) => d.warehouse_id) : [],
            diff_price: isDiffPrice ? diffPrices.map((d) => d.price) : [],
            stock_warehouse_id: isInitialStock ? initialStocks.map((s) => s.warehouse_id) : [],
            stock: isInitialStock ? initialStocks.map((s) => s.qty) : [],
        }

        onSubmit(formData)
    }

    React.useEffect(() => {
        // Initialize variant options if editing a product with variants
        if (initialData?.is_variant && initialData.id) {
            // This would need to be loaded from the product data
            // For now, we'll keep the default empty state
        }
    }, [initialData])

    const handleCategorySubmit = async (categoryData: Record<string, unknown>) => {
        try {
            const formData = new FormData()
            formData.append('name', categoryData.name as string)
            if (categoryData.parent_id) {
                formData.append('parent_id', categoryData.parent_id as string)
            }
            if (categoryData.image) {
                formData.append('image', categoryData.image as File)
            }

            const response = await fetch('/categories', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })

            if (response.ok) {
                const newCategory = await response.json()
                if (onCategoryCreated) {
                    onCategoryCreated(newCategory)
                }
                setCategoryDialogOpen(false)
                // Update the selected category
                if (newCategory.id) {
                    setData('category_id', newCategory.id.toString())
                }
            } else {
                const errorData = await response.json()
                console.error('Error creating category:', errorData)
            }
        } catch (error) {
            console.error('Error creating category:', error)
        }
    }

    return (
        <form onSubmit={handleSubmit} className={className}>
            {/* Basic Information */}
            <Card>
                <CardHeader>
                    <CardTitle>Basic Information</CardTitle>
                    <CardDescription>Enter the basic details of the product</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-4 md:grid-cols-2">
                        {/* 1. Product Type */}
                        <div className="space-y-2">
                            <Label htmlFor="type">Product Type *</Label>
                            <Combobox
                                options={[...PRODUCT_TYPES]}
                                value={data.type}
                                onValueChange={(value) => {
                                    setData("type", value)
                                    if (value === "digital" || value === "service") {
                                        setData("cost", "0")
                                        setData("unit_id", "")
                                    }
                                }}
                                placeholder="Select product type"
                            />
                            <InputError message={errors.type} />
                        </div>

                        {/* 2. Product Name */}
                        <div className="space-y-2">
                            <Label htmlFor="name">Product Name *</Label>
                            <Input
                                id="name"
                                value={data.name}
                                onChange={(e) => setData("name", e.target.value)}
                                required
                            />
                            <InputError message={errors.name} />
                        </div>

                        {/* 3. Product Code */}
                        <div className="space-y-2">
                            <Label htmlFor="code">Product Code *</Label>
                            <div className="flex gap-2">
                                <Input
                                    id="code"
                                    value={data.code}
                                    onChange={(e) => setData("code", e.target.value)}
                                    required
                                />
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="icon"
                                    onClick={generateCode}
                                    title="Generate Code"
                                >
                                    <RefreshCw className="h-4 w-4" />
                                </Button>
                            </div>
                            <InputError message={errors.code} />
                        </div>

                        {/* 4. Barcode Symbology */}
                        <div className="space-y-2">
                            <Label htmlFor="barcode_symbology">Barcode Symbology *</Label>
                            <Combobox
                                options={[...BARCODE_SYMBOLOGIES]}
                                value={data.barcode_symbology}
                                onValueChange={(value) => setData("barcode_symbology", value)}
                                placeholder="Select barcode symbology"
                            />
                            <InputError message={errors.barcode_symbology} />
                        </div>

                        {/* 5. Attach File (for digital) */}
                        {data.type === "digital" && (
                            <div className="space-y-2">
                                <Label htmlFor="file">Attach File *</Label>
                                <Input
                                    id="file"
                                    type="file"
                                    onChange={(e) => {
                                        const file = e.target.files?.[0]
                                        if (file) setData("file", file)
                                    }}
                                    accept=".pdf,.doc,.docx,.zip,.rar"
                                />
                                <InputError message={errors.file} />
                            </div>
                        )}

                        {/* 7. Brand */}
                        <div className="space-y-2">
                            <Label htmlFor="brand_id">Brand</Label>
                            <div className="flex gap-2">
                                <div className="flex-1">
                                    <Combobox
                                        options={[
                                            { value: "", label: "None" },
                                            ...brands.map((brand) => ({
                                                value: brand.id.toString(),
                                                label: brand.title,
                                            })),
                                        ]}
                                        value={data.brand_id}
                                        onValueChange={(value) => setData("brand_id", value)}
                                        placeholder="Select brand"
                                    />
                                </div>
                                <ResponsiveDialog open={brandDialogOpen} onOpenChange={setBrandDialogOpen}>
                                    <ResponsiveDialogTrigger asChild>
                                        <Button type="button" variant="outline" size="icon">
                                            <Plus className="h-4 w-4" />
                                        </Button>
                                    </ResponsiveDialogTrigger>
                                    <ResponsiveDialogContent>
                                        <ResponsiveDialogHeader>
                                            <ResponsiveDialogTitle>Add Brand</ResponsiveDialogTitle>
                                            <ResponsiveDialogDescription>Create a new brand</ResponsiveDialogDescription>
                                        </ResponsiveDialogHeader>
                                        {/* Brand form will be added here */}
                                        <ResponsiveDialogFooter>
                                            <ResponsiveDialogClose asChild>
                                                <Button type="button" variant="outline">
                                                    Cancel
                                                </Button>
                                            </ResponsiveDialogClose>
                                            <Button type="button">Add Brand</Button>
                                        </ResponsiveDialogFooter>
                                    </ResponsiveDialogContent>
                                </ResponsiveDialog>
                            </div>
                            <InputError message={errors.brand_id} />
                        </div>

                        {/* 8. Category */}
                        <div className="space-y-2">
                            <Label htmlFor="category_id">Category *</Label>
                            <div className="flex gap-2">
                                <div className="flex-1">
                                    <Combobox
                                        options={categories.map((category) => ({
                                            value: category.id.toString(),
                                            label: category.name,
                                        }))}
                                        value={data.category_id}
                                        onValueChange={(value) => setData("category_id", value)}
                                        placeholder="Select category"
                                    />
                                </div>
                                <ResponsiveDialog open={categoryDialogOpen} onOpenChange={setCategoryDialogOpen}>
                                    <ResponsiveDialogTrigger asChild>
                                        <Button type="button" variant="outline" size="icon">
                                            <Plus className="h-4 w-4" />
                                        </Button>
                                    </ResponsiveDialogTrigger>
                                    <ResponsiveDialogContent>
                                        <ResponsiveDialogHeader>
                                            <ResponsiveDialogTitle>Add Category</ResponsiveDialogTitle>
                                            <ResponsiveDialogDescription>
                                                Create a new category
                                            </ResponsiveDialogDescription>
                                        </ResponsiveDialogHeader>
                                        <div className="space-y-4">
                                            <CategoryForm
                                                categories={categories}
                                                onSubmit={handleCategorySubmit}
                                                onCancel={() => setCategoryDialogOpen(false)}
                                                showActions={false}
                                                className="category-form-in-dialog"
                                            />
                                        </div>
                                        <ResponsiveDialogFooter>
                                            <ResponsiveDialogClose asChild>
                                                <Button type="button" variant="outline">
                                                    Cancel
                                                </Button>
                                            </ResponsiveDialogClose>
                                            <Button
                                                type="button"
                                                onClick={(e) => {
                                                    e.preventDefault()
                                                    const form = document.querySelector('.category-form-in-dialog form') as HTMLFormElement
                                                    if (form) {
                                                        form.requestSubmit()
                                                    }
                                                }}
                                            >
                                                Add Category
                                            </Button>
                                        </ResponsiveDialogFooter>
                                    </ResponsiveDialogContent>
                                </ResponsiveDialog>
                            </div>
                            <InputError message={errors.category_id} />
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* 9. Units Section */}
            {data.type !== "digital" && data.type !== "service" && (
                <Card>
                    <CardHeader>
                        <CardTitle>Product Units</CardTitle>
                        <CardDescription>Set product, purchase, and sale units</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="grid gap-4 md:grid-cols-3">
                            <div className="space-y-2">
                                <Label htmlFor="unit_id">Product Unit *</Label>
                                <Combobox
                                    options={baseUnits.map((unit) => ({
                                        value: unit.id.toString(),
                                        label: unit.unit_name,
                                    }))}
                                    value={data.unit_id}
                                    onValueChange={(value) => {
                                        setData("unit_id", value)
                                        if (!data.purchase_unit_id) setData("purchase_unit_id", value)
                                        if (!data.sale_unit_id) setData("sale_unit_id", value)
                                    }}
                                    placeholder="Select unit"
                                />
                                <InputError message={errors.unit_id} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="sale_unit_id">Sale Unit</Label>
                                <Combobox
                                    options={saleUnits.map((unit) => ({
                                        value: unit.id.toString(),
                                        label: unit.unit_name,
                                    }))}
                                    value={data.sale_unit_id}
                                    onValueChange={(value) => setData("sale_unit_id", value)}
                                    placeholder="Select unit"
                                />
                                <InputError message={errors.sale_unit_id} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="purchase_unit_id">Purchase Unit</Label>
                                <Combobox
                                    options={saleUnits.map((unit) => ({
                                        value: unit.id.toString(),
                                        label: unit.unit_name,
                                    }))}
                                    value={data.purchase_unit_id}
                                    onValueChange={(value) => setData("purchase_unit_id", value)}
                                    placeholder="Select unit"
                                />
                                <InputError message={errors.purchase_unit_id} />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* 6. Combo Products Section */}
            {data.type === "combo" && (
                <Card>
                    <CardHeader>
                        <CardTitle>Combo Products</CardTitle>
                        <CardDescription>Add products to this combo</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="space-y-2">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={addComboProduct}
                                className="w-full"
                            >
                                <Plus className="mr-2 h-4 w-4" />
                                Add Product
                            </Button>
                        </div>
                        {comboProducts.length > 0 && (
                            <div className="space-y-2">
                                {comboProducts.map((combo, index) => (
                                    <div key={index} className="flex gap-2 items-end">
                                        <div className="flex-1 space-y-2">
                                            <Label>Product</Label>
                                            <Combobox
                                                options={productsWithoutVariant.map((product) => ({
                                                    value: product.id.toString(),
                                                    label: `${product.name} (${product.code})`,
                                                }))}
                                                value={combo.product_id}
                                                onValueChange={(value) => {
                                                    const updated = [...comboProducts]
                                                    updated[index].product_id = value
                                                    setComboProducts(updated)
                                                }}
                                                placeholder="Select product"
                                            />
                                        </div>
                                        <div className="w-32 space-y-2">
                                            <Label>Quantity</Label>
                                            <Input
                                                type="number"
                                                step="0.01"
                                                value={combo.qty}
                                                onChange={(e) => {
                                                    const updated = [...comboProducts]
                                                    updated[index].qty = e.target.value
                                                    setComboProducts(updated)
                                                }}
                                            />
                                        </div>
                                        <div className="w-32 space-y-2">
                                            <Label>Unit Price</Label>
                                            <Input
                                                type="number"
                                                step="0.01"
                                                value={combo.unit_price}
                                                onChange={(e) => {
                                                    const updated = [...comboProducts]
                                                    updated[index].unit_price = e.target.value
                                                    setComboProducts(updated)
                                                }}
                                            />
                                        </div>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            onClick={() => removeComboProduct(index)}
                                        >
                                            <X className="h-4 w-4" />
                                        </Button>
                                    </div>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            )}

            {/* Pricing & Inventory - 10-16 */}
            <Card>
                <CardHeader>
                    <CardTitle>Pricing & Inventory</CardTitle>
                    <CardDescription>Set pricing and inventory details</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-4 md:grid-cols-3">
                        {/* 10. Product Cost */}
                        {data.type !== "digital" && data.type !== "service" && (
                            <div className="space-y-2">
                                <Label htmlFor="cost">Product Cost *</Label>
                                <Input
                                    id="cost"
                                    type="number"
                                    step="0.01"
                                    value={data.cost}
                                    onChange={(e) => setData("cost", e.target.value)}
                                    required={data.type === "standard"}
                                />
                                <InputError message={errors.cost} />
                            </div>
                        )}

                        {/* 11. Product Price */}
                        <div className="space-y-2">
                            <Label htmlFor="price">Product Price *</Label>
                            <Input
                                id="price"
                                type="number"
                                step="0.01"
                                value={data.price}
                                onChange={(e) => setData("price", e.target.value)}
                                required
                            />
                            <InputError message={errors.price} />
                        </div>

                        {/* 12. Wholesale Price */}
                        <div className="space-y-2">
                            <Label htmlFor="wholesale_price">Wholesale Price</Label>
                            <Input
                                id="wholesale_price"
                                type="number"
                                step="0.01"
                                value={data.wholesale_price}
                                onChange={(e) => setData("wholesale_price", e.target.value)}
                            />
                            <InputError message={errors.wholesale_price} />
                        </div>

                        {/* 13. Daily Sale Objective */}
                        <div className="space-y-2">
                            <Label htmlFor="daily_sale_objective">Daily Sale Objective</Label>
                            <Input
                                id="daily_sale_objective"
                                type="number"
                                step="0.01"
                                value={data.daily_sale_objective}
                                onChange={(e) => setData("daily_sale_objective", e.target.value)}
                            />
                            <InputError message={errors.daily_sale_objective} />
                        </div>

                        {/* 14. Alert Quantity */}
                        {data.type !== "digital" && data.type !== "service" && (
                            <div className="space-y-2">
                                <Label htmlFor="alert_quantity">Alert Quantity</Label>
                                <Input
                                    id="alert_quantity"
                                    type="number"
                                    step="0.01"
                                    value={data.alert_quantity}
                                    onChange={(e) => setData("alert_quantity", e.target.value)}
                                />
                                <InputError message={errors.alert_quantity} />
                            </div>
                        )}
                    </div>

                    <div className="grid gap-4 md:grid-cols-2">
                        {/* 15. Product Tax */}
                        <div className="space-y-2">
                            <Label htmlFor="tax_id">Product Tax</Label>
                            <div className="flex gap-2">
                                <div className="flex-1">
                                    <Combobox
                                        options={[
                                            { value: "", label: "No Tax" },
                                            ...taxes.map((tax) => ({
                                                value: tax.id.toString(),
                                                label: tax.name,
                                            })),
                                        ]}
                                        value={data.tax_id}
                                        onValueChange={(value) => setData("tax_id", value)}
                                        placeholder="No Tax"
                                    />
                                </div>
                                <ResponsiveDialog open={taxDialogOpen} onOpenChange={setTaxDialogOpen}>
                                    <ResponsiveDialogTrigger asChild>
                                        <Button type="button" variant="outline" size="icon">
                                            <Plus className="h-4 w-4" />
                                        </Button>
                                    </ResponsiveDialogTrigger>
                                    <ResponsiveDialogContent>
                                        <ResponsiveDialogHeader>
                                            <ResponsiveDialogTitle>Add Tax</ResponsiveDialogTitle>
                                            <ResponsiveDialogDescription>Create a new tax</ResponsiveDialogDescription>
                                        </ResponsiveDialogHeader>
                                        {/* Tax form will be added here */}
                                        <ResponsiveDialogFooter>
                                            <ResponsiveDialogClose asChild>
                                                <Button type="button" variant="outline">
                                                    Cancel
                                                </Button>
                                            </ResponsiveDialogClose>
                                            <Button type="button">Add Tax</Button>
                                        </ResponsiveDialogFooter>
                                    </ResponsiveDialogContent>
                                </ResponsiveDialog>
                            </div>
                            <InputError message={errors.tax_id} />
                        </div>

                        {/* 16. Tax Method */}
                        <div className="space-y-2">
                            <Label htmlFor="tax_method">Tax Method</Label>
                            <Combobox
                                options={[...TAX_METHODS]}
                                value={data.tax_method}
                                onValueChange={(value) => setData("tax_method", value)}
                                placeholder="Select tax method"
                            />
                            <p className="text-xs text-muted-foreground">
                                Exclusive: Product price = Actual product price + Tax
                                <br />
                                Inclusive: Actual product price = Product price - Tax
                            </p>
                            <InputError message={errors.tax_method} />
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* 17-18. Warranty and Guarantee */}
            <Card>
                <CardHeader>
                    <CardTitle>Warranty & Guarantee</CardTitle>
                    <CardDescription>Set warranty and guarantee information</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="grid gap-4 md:grid-cols-2">
                        {/* 17. Warranty */}
                        <div className="space-y-2">
                            <Label htmlFor="warranty">Warranty</Label>
                            <div className="flex gap-2">
                                <Input
                                    id="warranty"
                                    type="number"
                                    min="1"
                                    placeholder="e.g., 1"
                                    value={data.warranty}
                                    onChange={(e) => setData("warranty", e.target.value)}
                                    className="flex-1"
                                />
                                <Combobox
                                    options={[...WARRANTY_GUARANTEE_TYPES]}
                                    value={data.warranty_type}
                                    onValueChange={(value) => setData("warranty_type", value)}
                                    placeholder="Select type"
                                    className="w-32"
                                />
                            </div>
                            <InputError message={errors.warranty} />
                        </div>

                        {/* 18. Guarantee */}
                        <div className="space-y-2">
                            <Label htmlFor="guarantee">Guarantee</Label>
                            <div className="flex gap-2">
                                <Input
                                    id="guarantee"
                                    type="number"
                                    min="1"
                                    placeholder="e.g., 1"
                                    value={data.guarantee}
                                    onChange={(e) => setData("guarantee", e.target.value)}
                                    className="flex-1"
                                />
                                <Combobox
                                    options={[...WARRANTY_GUARANTEE_TYPES]}
                                    value={data.guarantee_type}
                                    onValueChange={(value) => setData("guarantee_type", value)}
                                    placeholder="Select type"
                                    className="w-32"
                                />
                            </div>
                            <InputError message={errors.guarantee} />
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* 19. Custom Fields */}
            {customFields.length > 0 && (
                <Card>
                    <CardHeader>
                        <CardTitle>Custom Fields</CardTitle>
                        <CardDescription>Additional custom fields for this product</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="grid gap-4 md:grid-cols-2">
                            {customFields.map((field) => (
                                <div key={field.id} className="space-y-2">
                                    <Label>
                                        {field.name}
                                        {field.is_required && " *"}
                                    </Label>
                                    {field.type === "text" && (
                                        <Input
                                            name={field.name.toLowerCase().replace(/\s+/g, "_")}
                                            defaultValue={field.default_value}
                                            required={field.is_required}
                                        />
                                    )}
                                    {field.type === "number" && (
                                        <Input
                                            type="number"
                                            name={field.name.toLowerCase().replace(/\s+/g, "_")}
                                            defaultValue={field.default_value}
                                            required={field.is_required}
                                        />
                                    )}
                                    {field.type === "textarea" && (
                                        <Textarea
                                            name={field.name.toLowerCase().replace(/\s+/g, "_")}
                                            defaultValue={field.default_value}
                                            required={field.is_required}
                                            rows={3}
                                        />
                                    )}
                                    {field.type === "date_picker" && (
                                        <DatePicker
                                            value={field.default_value || undefined}
                                            onChange={(date) => {
                                                // Handle date change
                                                console.log(date)
                                            }}
                                            placeholder="Select date"
                                        />
                                    )}
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* 20-21. Product Options - Featured & Embedded */}
            <Card>
                <CardHeader>
                    <CardTitle>Product Options</CardTitle>
                    <CardDescription>Configure additional product settings</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="space-y-3">
                        {/* 20. Featured */}
                        <div className="flex items-center space-x-2">
                            <Checkbox
                                id="featured"
                                checked={data.featured}
                                onCheckedChange={(checked) => setData("featured", checked as boolean)}
                            />
                            <Label htmlFor="featured" className="cursor-pointer">
                                Featured
                            </Label>
                            <p className="text-xs text-muted-foreground">
                                Featured product will be displayed in POS
                            </p>
                        </div>

                        {/* 21. Embedded Barcode */}
                        <div className="flex items-center space-x-2">
                            <Checkbox
                                id="is_embeded"
                                checked={data.is_embeded}
                                onCheckedChange={(checked) => setData("is_embeded", checked as boolean)}
                            />
                            <Label htmlFor="is_embeded" className="cursor-pointer">
                                Embedded Barcode
                            </Label>
                            <p className="text-xs text-muted-foreground">
                                Check this if this product will be used in weight scale machine
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* 22. Initial Stock Section */}
            {data.type === "standard" && (
                <Card>
                    <CardHeader>
                        <CardTitle>Initial Stock</CardTitle>
                        <CardDescription>Set initial stock quantities for warehouses</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="flex items-center space-x-2 mb-4">
                            <Checkbox
                                id="is_initial_stock"
                                checked={isInitialStock}
                                onCheckedChange={(checked) => setIsInitialStock(checked as boolean)}
                            />
                            <Label htmlFor="is_initial_stock" className="cursor-pointer">
                                Initial Stock
                            </Label>
                            <p className="text-xs text-muted-foreground">
                                This feature will not work for product with variants and batches
                            </p>
                        </div>
                        {isInitialStock && !isVariant && !data.is_batch && (
                            <div className="space-y-2">
                                {initialStocks.map((stock, index) => (
                                    <div key={index} className="flex gap-2 items-end">
                                        <div className="flex-1">
                                            <Label>
                                                {warehouses.find((w) => w.id.toString() === stock.warehouse_id)?.name}
                                            </Label>
                                        </div>
                                        <div className="w-48 space-y-2">
                                            <Label>Quantity</Label>
                                            <Input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                value={stock.qty}
                                                onChange={(e) => {
                                                    const updated = [...initialStocks]
                                                    updated[index].qty = e.target.value
                                                    setInitialStocks(updated)
                                                }}
                                            />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            )}

            {/* 23. Product Image */}
            <Card>
                <CardHeader>
                    <CardTitle>Product Image</CardTitle>
                    <CardDescription>Upload product images (You can upload multiple images. Only jpeg, jpg, png, gif file can be uploaded. First image will be base image)</CardDescription>
                </CardHeader>
                <CardContent>
                    <div className="space-y-2">
                        <Label htmlFor="image">Product Image</Label>
                        <Input
                            id="image"
                            type="file"
                            multiple
                            accept="image/jpeg,image/jpg,image/png,image/gif"
                            onChange={(e) => {
                                const files = Array.from(e.target.files || [])
                                if (files.length > 0) {
                                    // Handle file upload - will be processed in form submission
                                    const fileInput = e.target as HTMLInputElement
                                    if (fileInput.files) {
                                        // Store files in a way that can be accessed during submission
                                        // For now, we'll handle this in the submit handler
                                    }
                                }
                            }}
                        />
                        <InputError message={errors.image} />
                    </div>
                </CardContent>
            </Card>

            {/* 24. Product Details */}
            <Card>
                <CardHeader>
                    <CardTitle>Product Details</CardTitle>
                    <CardDescription>Add product details and description</CardDescription>
                </CardHeader>
                <CardContent>
                    <div className="space-y-2">
                        <Label htmlFor="product_details">Product Details</Label>
                        <Textarea
                            id="product_details"
                            value={data.product_details}
                            onChange={(e) => setData("product_details", e.target.value)}
                            rows={4}
                        />
                        <InputError message={errors.product_details} />
                    </div>
                </CardContent>
            </Card>

            {/* 25. Variant Section */}
            {data.type === "standard" && (
                <Card>
                    <CardHeader>
                        <CardTitle>Product Variants</CardTitle>
                        <CardDescription>Configure product variants (e.g., Size, Color)</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="flex items-center space-x-2 mb-4">
                            <Checkbox
                                id="is_variant"
                                checked={isVariant}
                                onCheckedChange={(checked) => setIsVariant(checked as boolean)}
                            />
                            <Label htmlFor="is_variant" className="cursor-pointer">
                                This product has variant
                            </Label>
                        </div>
                        {isVariant && (
                            <>
                                {variantOptions.map((variant, index) => (
                                    <div key={index} className="flex gap-2 items-end">
                                        <div className="flex-1 space-y-2">
                                            <Label>Option * (e.g., Size, Color)</Label>
                                            <Input
                                                value={variant.option}
                                                onChange={(e) => updateVariantOption(index, "option", e.target.value)}
                                                placeholder="Size, Color etc"
                                            />
                                        </div>
                                        <div className="flex-1 space-y-2">
                                            <Label>Value * (comma separated)</Label>
                                            <Input
                                                value={variant.value}
                                                onChange={(e) => updateVariantOption(index, "value", e.target.value)}
                                                placeholder="Small, Medium, Large"
                                            />
                                        </div>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            onClick={() => removeVariantOption(index)}
                                        >
                                            <X className="h-4 w-4" />
                                        </Button>
                                    </div>
                                ))}
                                <Button type="button" variant="outline" onClick={addVariantOption}>
                                    <Plus className="mr-2 h-4 w-4" />
                                    Add More Variant
                                </Button>
                            </>
                        )}
                    </CardContent>
                </Card>
            )}

            {/* 26. Different Prices Section */}
            {data.type === "standard" && (
                <Card>
                    <CardHeader>
                        <CardTitle>Different Prices for Warehouses</CardTitle>
                        <CardDescription>Set different prices for different warehouses</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="flex items-center space-x-2 mb-4">
                            <Checkbox
                                id="is_diffPrice"
                                checked={isDiffPrice}
                                onCheckedChange={(checked) => setIsDiffPrice(checked as boolean)}
                            />
                            <Label htmlFor="is_diffPrice" className="cursor-pointer">
                                This product has different price for different warehouse
                            </Label>
                        </div>
                        {isDiffPrice && (
                            <div className="space-y-2">
                                {diffPrices.map((diffPrice, index) => (
                                    <div key={index} className="flex gap-2 items-end">
                                        <div className="flex-1">
                                            <Label>
                                                {warehouses.find((w) => w.id.toString() === diffPrice.warehouse_id)?.name}
                                            </Label>
                                        </div>
                                        <div className="w-48 space-y-2">
                                            <Label>Price</Label>
                                            <Input
                                                type="number"
                                                step="0.01"
                                                value={diffPrice.price}
                                                onChange={(e) => {
                                                    const updated = [...diffPrices]
                                                    updated[index].price = e.target.value
                                                    setDiffPrices(updated)
                                                }}
                                            />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            )}

            {/* 27. Batch Option */}
            {data.type === "standard" && (
                <Card>
                    <CardHeader>
                        <CardTitle>Batch & Expiry</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="flex items-center space-x-2">
                            <Checkbox
                                id="is_batch"
                                checked={data.is_batch}
                                onCheckedChange={(checked) => setData("is_batch", checked as boolean)}
                            />
                            <Label htmlFor="is_batch" className="cursor-pointer">
                                This product has batch and expired date
                            </Label>
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* 28. IMEI Option */}
            {data.type === "standard" && (
                <Card>
                    <CardHeader>
                        <CardTitle>IMEI / Serial Numbers</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="flex items-center space-x-2">
                            <Checkbox
                                id="is_imei"
                                checked={data.is_imei}
                                onCheckedChange={(checked) => setData("is_imei", checked as boolean)}
                            />
                            <Label htmlFor="is_imei" className="cursor-pointer">
                                This product has IMEI or Serial numbers
                            </Label>
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* 29. Promotion Section */}
            <Card>
                <CardHeader>
                    <CardTitle>Promotional Price</CardTitle>
                    <CardDescription>Set promotional pricing with start and end dates</CardDescription>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="flex items-center space-x-2 mb-4">
                        <Checkbox
                            id="promotion"
                            checked={isPromotion}
                            onCheckedChange={(checked) => setIsPromotion(checked as boolean)}
                        />
                        <Label htmlFor="promotion" className="cursor-pointer">
                            Add Promotional Price
                        </Label>
                    </div>
                    {isPromotion && (
                        <div className="grid gap-4 md:grid-cols-3">
                            <div className="space-y-2">
                                <Label htmlFor="promotion_price">Promotional Price</Label>
                                <Input
                                    id="promotion_price"
                                    type="number"
                                    step="0.01"
                                    value={data.promotion_price}
                                    onChange={(e) => setData("promotion_price", e.target.value)}
                                />
                                <InputError message={errors.promotion_price} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="starting_date">Promotion Starts</Label>
                                <DatePicker
                                    value={data.starting_date || undefined}
                                    onChange={(date) =>
                                        setData("starting_date", date ? date.toISOString().split("T")[0] : "")
                                    }
                                    placeholder="Select start date"
                                />
                                <InputError message={errors.starting_date} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="last_date">Promotion Ends</Label>
                                <DatePicker
                                    value={data.last_date || undefined}
                                    onChange={(date) =>
                                        setData("last_date", date ? date.toISOString().split("T")[0] : "")
                                    }
                                    placeholder="Select end date"
                                    fromDate={data.starting_date ? new Date(data.starting_date) : undefined}
                                />
                                <InputError message={errors.last_date} />
                            </div>
                        </div>
                    )}
                </CardContent>
            </Card>


            {showActions && (
                <div className="flex justify-end gap-4">
                    {onCancel && (
                        <Button type="button" variant="outline" onClick={onCancel}>
                            {cancelLabel}
                        </Button>
                    )}
                    <Button type="submit" disabled={processing}>
                        {processing && <Spinner className="mr-2" />}
                        {submitLabel}
                    </Button>
                </div>
            )}
        </form>
    )
}

