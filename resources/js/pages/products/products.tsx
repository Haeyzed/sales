import { columns, Product } from "./columns"
import { DataTable } from "./data-table"
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-react';
import { useState, useEffect, useCallback } from 'react';
import { Combobox } from '@/components/ui/combobox';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];

interface ProductsPageProps {
    warehouses: Array<{ id: number; name: string }>;
    warehouseId: number;
    products?: Product[];
}

export default function Products({ warehouses, warehouseId: initialWarehouseId }: ProductsPageProps) {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [warehouseId, setWarehouseId] = useState(initialWarehouseId);

    const fetchProducts = useCallback(async () => {
        setLoading(true);
        try {
            const response = await fetch(`/products/data?warehouse_id=${warehouseId}&draw=1&start=0&length=15`);
            const data = await response.json();
            setProducts(data.data || []);
        } catch (error) {
            console.error('Error fetching products:', error);
        } finally {
            setLoading(false);
        }
    }, [warehouseId]);

    useEffect(() => {
        fetchProducts();
    }, [fetchProducts]);

    const handleWarehouseChange = (value: string) => {
        const newWarehouseId = parseInt(value);
        setWarehouseId(newWarehouseId);
        router.get('/products', { warehouse_id: newWarehouseId }, { preserveState: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">Products</h1>
                        <p className="text-muted-foreground">
                            Manage your product inventory
                        </p>
                    </div>
                    <div className="flex items-center gap-4">
                        <Combobox
                            options={[
                                { value: '0', label: 'All Warehouses' },
                                ...warehouses.map((warehouse) => ({
                                    value: warehouse.id.toString(),
                                    label: warehouse.name,
                                })),
                            ]}
                            value={warehouseId.toString()}
                            onValueChange={(value) => handleWarehouseChange(value)}
                            placeholder="Select warehouse"
                            className="w-[200px]"
                        />
                        <Button onClick={() => router.visit('/products/create')}>
                            <Plus className="mr-2 h-4 w-4" />
                            Add Product
                        </Button>
                    </div>
                </div>

                {loading ? (
                    <div className="flex items-center justify-center h-64">
                        <div className="text-muted-foreground">Loading products...</div>
                    </div>
                ) : (
                    <DataTable columns={columns} data={products} />
                )}
            </div>
        </AppLayout>
    )
}
