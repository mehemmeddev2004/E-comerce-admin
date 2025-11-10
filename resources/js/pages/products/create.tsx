import AppLayout from '@/layouts/app-layout';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// Using native select for simplicity (radix Select wrapper requires different API)
import { Form, Head, usePage } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import { PageProps } from '@inertiajs/core';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: '/products' },
    { title: 'Create', href: '#' },
];

interface Category {
    id: number;
    name: string;
}

type Props = {
    categories: Category[];
} & PageProps;

export default function ProductCreate() {
    const { categories } = usePage<Props>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Product" />

            <div className="p-4">
                <h1 className="text-2xl font-semibold mb-4">Create Product</h1>

                <Form action="/products" method="post" className="max-w-lg grid gap-4">
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Product name</Label>
                                <Input id="name" name="name" required placeholder="Product name" />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="brand">Brand</Label>
                                <Input id="brand" name="brand" required placeholder="Brand name" />
                                <InputError message={errors.brand} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="category_id">Category</Label>
                                <select
                                    id="category_id"
                                    name="category_id"
                                    className="border-input flex h-9 w-full items-center rounded-md border bg-transparent px-3 py-2 text-sm"
                                    defaultValue=""
                                >
                                    <option value="" disabled>
                                        Select a category
                                    </option>
                                    {categories.map((c) => (
                                        <option key={c.id} value={c.id}>
                                            {c.name}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.category_id} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="price">Price</Label>
                                <Input id="price" name="price" type="number" step="0.01" required placeholder="0.00" />
                                <InputError message={errors.price} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="stock">Stock</Label>
                                <Input id="stock" name="stock" type="number" placeholder="0" />
                                <InputError message={errors.stock} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="discount">Discount (%)</Label>
                                <Input id="discount" name="discount" type="number" step="0.01" placeholder="0" />
                                <InputError message={errors.discount} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <Input id="description" name="description" placeholder="Product description" />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">Product Image</Label>
                                <Input 
                                    id="image" 
                                    name="image" 
                                    type="file" 
                                    accept="image/*"
                                    className="cursor-pointer"
                                />
                                <InputError message={errors.image} />
                                <p className="text-xs text-gray-500">Upload a product image (JPG, PNG, WebP)</p>
                            </div>

                            <Button type="submit" disabled={processing} className="w-fit">
                                Create Product
                            </Button>
                        </>
                    )}
                </Form>
            </div>
        </AppLayout>
    );
}
