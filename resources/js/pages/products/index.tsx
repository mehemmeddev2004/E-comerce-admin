import AppLayout from '@/layouts/app-layout';
import { Head, Link, usePage } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import { PageProps } from '@inertiajs/core';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '#',
    },
];

interface Category {
    id: number;
    name: string;
    slug: string;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    brand: string;
    price: number;
    stock: number;
    discount: number;
    is_active: boolean;
    is_new: boolean;
    image_url?: string;
    category?: Category;
}

type Props = {
    products: Product[];
    flash: {
        success?: string;
        error?: string;
    };
} & PageProps;

export default function ProductsIndex() {
    const { products, flash } = usePage<Props>().props;

    // Debug: Log products data from backend
    console.log('Products from backend:', products);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Products" />
            
            {flash?.success && (
                <div className="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-600">
                    {flash.success}
                </div>
            )}

            <div className="p-4">
                <div className="flex items-center justify-between mb-4">
                    <h1 className="text-2xl font-semibold">Products</h1>
                    <Link
                        href="/products/create"
                        className="rounded-md bg-primary px-3 py-1 text-white hover:bg-primary-600"
                    >
                        New Product
                    </Link>
                </div>

                <div className="overflow-hidden rounded-lg border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead className="bg-gray-50 dark:bg-neutral-900">
                            <tr>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Image</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Brand</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Price</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Stock</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                                <th className="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 bg-white dark:divide-neutral-800 dark:bg-neutral-950">
                            {products.map((product) => (
                                <tr key={product.id}>
                                    <td className="whitespace-nowrap px-6 py-4">
                                        {product.image_url ? (
                                            <img 
                                                src={product.image_url} 
                                                alt={product.name}
                                                className="h-12 w-12 rounded object-cover"
                                            />
                                        ) : (
                                            <div className="h-12 w-12 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <span className="text-xs text-gray-400">No img</span>
                                            </div>
                                        )}
                                    </td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{product.id}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{product.name}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{product.brand}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${product.price}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{product.stock}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        {product.category?.name || '-'}
                                    </td>
                                    <td className="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div className="flex justify-end gap-2">
                                            <Link href={`/products/${product.id}/edit`} className="text-sm text-primary">Edit</Link>
                                            <Link href={`/products/${product.id}`} method="delete" as="button" className="text-sm text-red-500">Delete</Link>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AppLayout>
    );
}
