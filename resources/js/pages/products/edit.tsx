import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-react';
import { ProductForm } from '@/components/forms/product-form';
import React from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: 'Edit Product',
        href: '#',
    },
];

interface EditProductProps {
    product: {
        id: number;
        name: string;
        code: string;
        type: string;
        barcode_symbology: string;
        category_id: number;
        unit_id: number | null;
        purchase_unit_id: number | null;
        sale_unit_id: number | null;
        brand_id: number | null;
        tax_id: number | null;
        tax_method: string;
        cost: number;
        price: number;
        wholesale_price: number | null;
        alert_quantity: number | null;
        daily_sale_objective: number | null;
        product_details: string | null;
        warranty: number | null;
        warranty_type: string | null;
        guarantee: number | null;
        guarantee_type: string | null;
        featured: boolean;
        is_embeded: boolean;
        is_batch: boolean;
        is_variant: boolean;
        is_diffPrice: boolean;
        is_imei: boolean;
        promotion: boolean;
        promotion_price: number | null;
        starting_date: string | null;
        last_date: string | null;
        image: string | null;
    };
    brands: Array<{ id: number; title: string }>;
    categories: Array<{ id: number; name: string }>;
    units: Array<{ id: number; unit_name: string; base_unit: number | null }>;
    taxes: Array<{ id: number; name: string }>;
    warehouses: Array<{ id: number; name: string }>;
    productsWithoutVariant?: Array<{ id: number; name: string; code: string }>;
    productsWithVariant?: Array<{ id: number; name: string; code: string }>;
    customFields?: Array<{
        id: number;
        name: string;
        type: string;
        is_required: boolean;
        default_value: string;
        option_value: string;
        grid_value: number;
        is_admin: boolean;
    }>;
}

export default function EditProduct({
    product,
    brands,
    categories,
    units,
    taxes,
    warehouses,
    productsWithoutVariant = [],
    productsWithVariant = [],
    customFields = [],
}: EditProductProps) {
    const [processing, setProcessing] = React.useState(false);
    const [errors, setErrors] = React.useState<Record<string, string>>({});

    const handleSubmit = async (formData: Record<string, unknown>) => {
        setProcessing(true);
        setErrors({});

        try {
            const formDataToSend = new FormData();
            formDataToSend.append('_method', 'PUT');
            Object.entries(formData).forEach(([key, value]) => {
                if (value !== null && value !== undefined) {
                    if (value instanceof File) {
                        formDataToSend.append(key, value);
                    } else if (Array.isArray(value)) {
                        value.forEach((item, index) => {
                            formDataToSend.append(`${key}[${index}]`, String(item));
                        });
                    } else if (typeof value === 'object') {
                        formDataToSend.append(key, JSON.stringify(value));
                    } else {
                        formDataToSend.append(key, String(value));
                    }
                }
            });

            const response = await fetch(`/products/${product.id}`, {
                method: 'POST',
                body: formDataToSend,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                router.visit('/products');
            } else {
                const data = await response.json();
                if (data.errors) {
                    setErrors(data.errors);
                }
            }
        } catch (error) {
            console.error('Error submitting form:', error);
        } finally {
            setProcessing(false);
        }
    };

    const handleCancel = () => {
        router.visit('/products');
    };

    const handleCategoryCreated = () => {
        // After category is created, refresh the page to get updated categories list
        router.reload({
            only: ['categories'],
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Product" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
                <div className="flex items-center gap-4">
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => router.visit('/products')}
                    >
                        <ArrowLeft className="h-4 w-4" />
                    </Button>
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">Edit Product</h1>
                        <p className="text-muted-foreground">
                            Update product information. Fields marked with * are required.
                        </p>
                    </div>
                </div>

                <ProductForm
                    initialData={{
                        ...product,
                        wholesale_price: product.wholesale_price?.toString() || '',
                        alert_quantity: product.alert_quantity?.toString() || '',
                        daily_sale_objective: product.daily_sale_objective?.toString() || '',
                        product_details: product.product_details || '',
                        warranty: product.warranty?.toString() || '',
                        warranty_type: product.warranty_type || 'months',
                        guarantee: product.guarantee?.toString() || '',
                        guarantee_type: product.guarantee_type || 'months',
                        promotion_price: product.promotion_price?.toString() || '',
                        starting_date: product.starting_date || '',
                        last_date: product.last_date || '',
                        image: product.image || undefined,
                    }}
                    brands={brands}
                    categories={categories}
                    units={units}
                    taxes={taxes}
                    warehouses={warehouses}
                    productsWithoutVariant={productsWithoutVariant}
                    productsWithVariant={productsWithVariant}
                    customFields={customFields}
                    onSubmit={handleSubmit}
                    onCancel={handleCancel}
                    submitLabel="Update Product"
                    cancelLabel="Cancel"
                    processing={processing}
                    errors={errors}
                    onCategoryCreated={handleCategoryCreated}
                />
            </div>
        </AppLayout>
    );
}
