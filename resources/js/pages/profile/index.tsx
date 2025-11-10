import AppLayout from '@/layouts/app-layout';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form, Head, usePage } from '@inertiajs/react';
import { type BreadcrumbItem } from '@/types';
import { PageProps } from '@inertiajs/core';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile',
        href: '#',
    },
];

interface Profile {
    id: number;
    user_id: number;
    first_name: string;
    last_name: string;
    phone?: string;
    address?: string;
    birth_date?: string;
    avatar?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

type Props = {
    profile?: Profile;
    flash: {
        success?: string;
        error?: string;
    };
    auth: {
        user: User;
    };
} & PageProps;

export default function ProfileIndex() {
    const { profile, flash, auth } = usePage<Props>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My Profile" />

            {flash?.success && (
                <div className="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-600 dark:bg-green-900/20 dark:text-green-400">
                    {flash.success}
                </div>
            )}

            {flash?.error && (
                <div className="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-600 dark:bg-red-900/20 dark:text-red-400">
                    {flash.error}
                </div>
            )}

            <div className="p-4">
                <div className="mb-6">
                    <h1 className="text-2xl font-semibold">My Profile</h1>
                    <p className="text-sm text-gray-500 dark:text-gray-400">
                        Manage your personal information
                    </p>
                </div>

                {/* Profile Avatar */}
                {profile?.avatar && (
                    <div className="mb-6 flex items-center gap-4">
                        <img
                            src={profile.avatar}
                            alt={`${profile.first_name} ${profile.last_name}`}
                            className="h-24 w-24 rounded-full object-cover"
                        />
                        <div>
                            <h2 className="text-xl font-semibold">
                                {profile.first_name} {profile.last_name}
                            </h2>
                            <p className="text-sm text-gray-500">{auth.user?.email}</p>
                        </div>
                    </div>
                )}

                {/* Profile Form */}
                <Form
                    action={profile ? `/profile/${profile.user_id}` : '/profile'}
                    method={profile ? 'put' : 'post'}
                    className="max-w-2xl space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-6 md:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="first_name">First Name *</Label>
                                    <Input
                                        id="first_name"
                                        name="first_name"
                                        defaultValue={profile?.first_name}
                                        required
                                        placeholder="John"
                                    />
                                    <InputError message={errors.first_name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="last_name">Last Name *</Label>
                                    <Input
                                        id="last_name"
                                        name="last_name"
                                        defaultValue={profile?.last_name}
                                        required
                                        placeholder="Doe"
                                    />
                                    <InputError message={errors.last_name} />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="phone">Phone Number</Label>
                                <Input
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    defaultValue={profile?.phone}
                                    placeholder="+994501234567"
                                />
                                <InputError message={errors.phone} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="address">Address</Label>
                                <Input
                                    id="address"
                                    name="address"
                                    defaultValue={profile?.address}
                                    placeholder="Baku, Azerbaijan"
                                />
                                <InputError message={errors.address} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="birth_date">Birth Date</Label>
                                <Input
                                    id="birth_date"
                                    name="birth_date"
                                    type="date"
                                    defaultValue={profile?.birth_date}
                                />
                                <InputError message={errors.birth_date} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="avatar">Profile Picture</Label>
                                <Input
                                    id="avatar"
                                    name="avatar"
                                    type="file"
                                    accept="image/*"
                                    className="cursor-pointer"
                                />
                                <InputError message={errors.avatar} />
                                <p className="text-xs text-gray-500">
                                    Upload a profile picture (JPG, PNG, max 2MB)
                                </p>
                            </div>

                            <div className="flex gap-3">
                                <Button type="submit" disabled={processing}>
                                    {profile ? 'Update Profile' : 'Create Profile'}
                                </Button>

                                {profile && (
                                    <Button
                                        type="button"
                                        variant="outline"
                                        onClick={() => {
                                            if (confirm('Are you sure you want to delete your profile?')) {
                                                // Handle delete
                                                window.location.href = `/profile/${profile.user_id}`;
                                            }
                                        }}
                                    >
                                        Delete Profile
                                    </Button>
                                )}
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </AppLayout>
    );
}
