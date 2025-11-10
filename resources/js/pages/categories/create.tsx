import AppLayout from '@/layouts/app-layout';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm, Head } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Categories', href: '/categories' },
    { title: 'Create', href: '#' },
];

export default function CategoryCreate() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        slug: '',
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        post('/categories');
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Category" />

            <div className="p-4">
                <h1 className="text-2xl font-semibold mb-4">Create Category</h1>

                <form onSubmit={handleSubmit} className="max-w-lg">
                    <div className="grid gap-4">
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>
                            <Input
                                id="name"
                                value={data.name}
                                onChange={e => setData('name', e.target.value)}
                                required
                                placeholder="Category name"
                            />
                            <InputError message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="slug">Slug</Label>
                            <Input
                                id="slug"
                                value={data.slug}
                                onChange={e => setData('slug', e.target.value)}
                                required
                                placeholder="category-slug"
                            />
                            <InputError message={errors.slug} />
                        </div>

                        <Button type="submit" disabled={processing} className="w-fit">
                            Create Category
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
