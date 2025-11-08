import { columns, Category } from "./columns"
import { DataTable } from "./data-table"
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-react';
import { useState, useEffect } from 'react';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import InputError from '@/components/input-error';
import { useForm } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
    {
        title: 'Categories',
        href: '/products/categories',
    },
];

interface CategoriesPageProps {
    categories?: Category[];
}

export default function Categories({ categories: initialCategories = [] }: CategoriesPageProps) {
    const [categories, setCategories] = useState<Category[]>(initialCategories);
    const [loading, setLoading] = useState(true);
    const [isCreateDialogOpen, setIsCreateDialogOpen] = useState(false);
    const [editingCategory, setEditingCategory] = useState<Category | null>(null);

    const { data: createData, setData: setCreateData, post: createPost, processing: creating, errors: createErrors, reset: resetCreate } = useForm({
        name: '',
        parent_id: '',
        image: null as File | null,
    });

    const { data: editData, setData: setEditData, put: updatePut, processing: updating, errors: editErrors, reset: resetEdit } = useForm({
        name: '',
        parent_id: '',
        image: null as File | null,
    });

    useEffect(() => {
        fetchCategories();
    }, []);

    const fetchCategories = async () => {
        setLoading(true);
        try {
            const response = await fetch('/products/categories/data?draw=1&start=0&length=15');
            const data = await response.json();
            setCategories(data.data || []);
        } catch (error) {
            console.error('Error fetching categories:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        createPost('/products/categories', {
            onSuccess: () => {
                setIsCreateDialogOpen(false);
                resetCreate();
                fetchCategories();
            },
            forceFormData: true,
        });
    };

    const handleEdit = (category: Category) => {
        setEditingCategory(category);
        setEditData({
            name: category.name,
            parent_id: category.parent_id?.toString() || '',
            image: null,
        });
    };

    const handleUpdate = (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingCategory) return;

        updatePut(`/products/categories/${editingCategory.id}`, {
            onSuccess: () => {
                setEditingCategory(null);
                resetEdit();
                fetchCategories();
            },
            forceFormData: true,
        });
    };

    const handleDelete = async (id: number) => {
        if (confirm('Are you sure you want to delete this category?')) {
            try {
                await fetch(`/products/categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                });
                fetchCategories();
            } catch (error) {
                console.error('Error deleting category:', error);
            }
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Categories" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">Categories</h1>
                        <p className="text-muted-foreground">
                            Manage product categories
                        </p>
                    </div>
                    <Dialog open={isCreateDialogOpen} onOpenChange={setIsCreateDialogOpen}>
                        <DialogTrigger asChild>
                            <Button>
                                <Plus className="mr-2 h-4 w-4" />
                                Add Category
                            </Button>
                        </DialogTrigger>
                        <DialogContent className="sm:max-w-[500px]">
                            <form onSubmit={handleCreate}>
                                <DialogHeader>
                                    <DialogTitle>Add Category</DialogTitle>
                                    <DialogDescription>
                                        Create a new product category
                                    </DialogDescription>
                                </DialogHeader>
                                <div className="grid gap-4 py-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="create-name">Name *</Label>
                                        <Input
                                            id="create-name"
                                            value={createData.name}
                                            onChange={(e) => setCreateData('name', e.target.value)}
                                            required
                                        />
                                        <InputError message={createErrors.name} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="create-parent_id">Parent Category</Label>
                                        <Select
                                            value={createData.parent_id}
                                            onValueChange={(value) => setCreateData('parent_id', value)}
                                        >
                                            <SelectTrigger>
                                                <SelectValue placeholder="No parent" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="">No parent</SelectItem>
                                                {categories.map((category) => (
                                                    <SelectItem key={category.id} value={category.id.toString()}>
                                                        {category.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={createErrors.parent_id} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="create-image">Image</Label>
                                        <Input
                                            id="create-image"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => {
                                                const file = e.target.files?.[0];
                                                if (file) setCreateData('image', file);
                                            }}
                                        />
                                        <InputError message={createErrors.image} />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => setIsCreateDialogOpen(false)}
                                    >
                                        Cancel
                                    </Button>
                                    <Button type="submit" disabled={creating}>
                                        {creating ? 'Creating...' : 'Create Category'}
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>

                {loading ? (
                    <div className="flex items-center justify-center h-64">
                        <div className="text-muted-foreground">Loading categories...</div>
                    </div>
                ) : (
                    <DataTable columns={columns(categories, handleEdit, handleDelete)} data={categories} />
                )}

                {/* Edit Dialog */}
                {editingCategory && (
                    <Dialog open={!!editingCategory} onOpenChange={(open) => !open && setEditingCategory(null)}>
                        <DialogContent className="sm:max-w-[500px]">
                            <form onSubmit={handleUpdate}>
                                <DialogHeader>
                                    <DialogTitle>Edit Category</DialogTitle>
                                    <DialogDescription>
                                        Update category information
                                    </DialogDescription>
                                </DialogHeader>
                                <div className="grid gap-4 py-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="edit-name">Name *</Label>
                                        <Input
                                            id="edit-name"
                                            value={editData.name}
                                            onChange={(e) => setEditData('name', e.target.value)}
                                            required
                                        />
                                        <InputError message={editErrors.name} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="edit-parent_id">Parent Category</Label>
                                        <Select
                                            value={editData.parent_id}
                                            onValueChange={(value) => setEditData('parent_id', value)}
                                        >
                                            <SelectTrigger>
                                                <SelectValue placeholder="No parent" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="">No parent</SelectItem>
                                                {categories.filter((c) => c.id !== editingCategory.id).map((category) => (
                                                    <SelectItem key={category.id} value={category.id.toString()}>
                                                        {category.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={editErrors.parent_id} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="edit-image">Image</Label>
                                        <Input
                                            id="edit-image"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => {
                                                const file = e.target.files?.[0];
                                                if (file) setEditData('image', file);
                                            }}
                                        />
                                        <InputError message={editErrors.image} />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => setEditingCategory(null)}
                                    >
                                        Cancel
                                    </Button>
                                    <Button type="submit" disabled={updating}>
                                        {updating ? 'Updating...' : 'Update Category'}
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                )}
            </div>
        </AppLayout>
    );
}

