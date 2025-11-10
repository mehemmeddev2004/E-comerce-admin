import AppLayout from '@/layouts/app-layout';
import { Head, Link, usePage } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import { PageProps } from '@inertiajs/core';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '#',
    },
];

interface Category {
    id: number;
    name: string;
    slug: string;
}

type Props = {
    categories: Category[];
    flash: {
        success?: string;
        error?: string;
    };
} & PageProps;

export default function CategoriesIndex() {
    const { categories, flash } = usePage<Props>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Categories" />
            
            {flash?.success && (
                <div className="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-600">
                    {flash.success}
                </div>
            )}

            <div className="p-4">
                <div className="flex items-center justify-between mb-4">
                    <h1 className="text-2xl font-semibold">Categories</h1>
                    <Link
                        href="/categories/create"
                        className="rounded-md  px-3 py-1  bg-black border-1 border-white  text-white hover:bg-primary-600"
                    >
                        New Category
                    </Link>
                </div>

                <div className="overflow-hidden rounded-lg border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead className="bg-gray-50 dark:bg-neutral-900">
                            <tr>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                                <th className="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 bg-white dark:divide-neutral-800 dark:bg-neutral-950">
                            {categories.map((category) => (
                                <tr key={category.id}>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{category.id}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{category.name}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div className="flex justify-end gap-2">
                                            <Link href={`/categories/${category.id}/edit`} className="text-sm text-primary">Edit</Link>
                                            <Link href={`/categories/${category.id}`} method="delete" as="button" className="text-sm text-red-500">Delete</Link>
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
