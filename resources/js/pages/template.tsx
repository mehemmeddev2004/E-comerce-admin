import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Template',
        href: '#',
    },
];

export default function TemplatePage() {
    const sampleData = [
        { id: 1, name: 'Product A', category: 'Category 1', price: '$10' },
        { id: 2, name: 'Product B', category: 'Category 2', price: '$20' },
        { id: 3, name: 'Product C', category: 'Category 1', price: '$30' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Template" />

            <div className="flex flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-semibold">Template Page</h1>
                    <div className="flex items-center gap-2">
                        <Link
                            href="#"
                            className="rounded-md bg-primary px-3 py-1 text-white hover:bg-primary-600"
                        >
                            New
                        </Link>
                    </div>
                </div>

                <div className="overflow-hidden rounded-lg border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead className="bg-gray-50 dark:bg-neutral-900">
                            <tr>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                                <th className="px-6 py-3 text-left text-sm font-medium text-gray-500">Price</th>
                                <th className="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 bg-white dark:divide-neutral-800 dark:bg-neutral-950">
                            {sampleData.map((row) => (
                                <tr key={row.id}>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{row.id}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{row.name}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{row.category}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{row.price}</td>
                                    <td className="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div className="flex justify-end gap-2">
                                            <Link href="#" className="text-sm text-primary">Edit</Link>
                                            <Link href="#" className="text-sm text-red-500">Delete</Link>
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
