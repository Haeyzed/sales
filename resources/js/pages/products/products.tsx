import { columns, Product } from "./columns"
import { DataTable } from "./data-table"
import { type BreadcrumbItem } from '@/types';
import { products } from '@/routes';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: products().url,
    },
];

function getData(): Product[] {
    // Fetch data from your API here.
    return [
        {
            id: "728ed52f",
            amount: 100,
            status: "pending",
            email: "m@example.com",
        },
    ]
}

export default function Products() {
    const data = getData()

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <DataTable columns={columns} data={data} />
            </div>
        </AppLayout>
    )
}
