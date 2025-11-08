'use client';

import { ColumnDef } from '@tanstack/react-table';
import { MoreHorizontal, Edit, Trash2 } from 'lucide-react';

import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Checkbox } from '@/components/ui/checkbox';
import { DataTableColumnHeader } from '@/components/data-table-column-header';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

export type Category = {
    id: number;
    image: string;
    name: string;
    parent_id: string;
    number_of_products: number;
    stock_qty: number;
    stock_worth: string;
};

export const columns = (
    categories: Category[],
    onEdit: (category: Category) => void,
    onDelete: (id: number) => void
): ColumnDef<Category>[] => [
    {
        id: 'select',
        header: ({ table }) => (
            <Checkbox
                checked={
                    table.getIsAllPageRowsSelected() ||
                    (table.getIsSomePageRowsSelected() && 'indeterminate')
                }
                onCheckedChange={(value) =>
                    table.toggleAllPageRowsSelected(!!value)
                }
                aria-label="Select all"
            />
        ),
        cell: ({ row }) => (
            <Checkbox
                checked={row.getIsSelected()}
                onCheckedChange={(value) => row.toggleSelected(!!value)}
                aria-label="Select row"
            />
        ),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: ({ column }) => (
            <DataTableColumnHeader column={column} title="Category" />
        ),
        cell: ({ row }) => {
            const category = row.original;
            const imageUrl = category.image && category.image !== 'zummXD2dvAtI.png'
                ? `/images/category/${category.image}`
                : '/images/zummXD2dvAtI.png';

            return (
                <div className="flex items-center gap-3">
                    <Avatar className="h-10 w-10">
                        <AvatarImage src={imageUrl} alt={category.name} />
                        <AvatarFallback>{category.name.substring(0, 2).toUpperCase()}</AvatarFallback>
                    </Avatar>
                    <span className="font-medium">{category.name}</span>
                </div>
            );
        },
    },
    {
        accessorKey: 'parent_id',
        header: ({ column }) => (
            <DataTableColumnHeader column={column} title="Parent Category" />
        ),
    },
    {
        accessorKey: 'number_of_products',
        header: ({ column }) => (
            <DataTableColumnHeader column={column} title="Products" />
        ),
        cell: ({ row }) => {
            return <div className="text-right">{row.getValue('number_of_products')}</div>;
        },
    },
    {
        accessorKey: 'stock_qty',
        header: ({ column }) => (
            <DataTableColumnHeader column={column} title="Stock Qty" />
        ),
        cell: ({ row }) => {
            const qty = parseFloat(row.getValue('stock_qty'));
            return <div className="text-right">{qty.toFixed(2)}</div>;
        },
    },
    {
        accessorKey: 'stock_worth',
        header: () => <div className="text-right">Stock Worth</div>,
        cell: ({ row }) => {
            return <div className="text-right text-sm text-muted-foreground">{row.getValue('stock_worth')}</div>;
        },
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const category = row.original;

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="h-8 w-8 p-0">
                            <span className="sr-only">Open menu</span>
                            <MoreHorizontal className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Actions</DropdownMenuLabel>
                        <DropdownMenuItem
                            onClick={() => onEdit(category)}
                        >
                            <Edit className="mr-2 h-4 w-4" />
                            Edit
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem
                            onClick={() => onDelete(category.id)}
                            className="text-red-600"
                        >
                            <Trash2 className="mr-2 h-4 w-4" />
                            Delete
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];

