import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useInitials } from '@/hooks/use-initials';
import { router } from '@inertiajs/react';
import { Mail, MapPin, Phone, Calendar, User } from 'lucide-react';

interface Profile {
    id: number;
    user_id: number;
    first_name: string;
    last_name: string;
    phone?: string;
    address?: string;
    birth_date?: string;
    avatar?: string;
    bio?: string;
    city?: string;
    country?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
}

interface ProfileCardProps {
    profile?: Profile;
    user: User;
}

export function ProfileCard({ profile, user }: ProfileCardProps) {
    const getInitials = useInitials();

    const handleEditProfile = () => {
        router.visit('/profile');
    };

    return (
        <Card className="overflow-hidden">
            <CardHeader className="bg-gradient-to-r from-blue-500/10 to-purple-500/10 pb-8">
                <div className="flex items-start justify-between">
                    <div className="flex items-center gap-4">
                        <Avatar className="h-20 w-20 border-4 border-background">
                            <AvatarImage 
                                src={profile?.avatar || user.avatar} 
                                alt={profile ? `${profile.first_name} ${profile.last_name}` : user.name} 
                            />
                            <AvatarFallback className="bg-gradient-to-br from-blue-500 to-purple-500 text-lg font-semibold text-white">
                                {profile 
                                    ? getInitials(`${profile.first_name} ${profile.last_name}`)
                                    : getInitials(user.name)
                                }
                            </AvatarFallback>
                        </Avatar>
                        <div>
                            <CardTitle className="text-2xl">
                                {profile 
                                    ? `${profile.first_name} ${profile.last_name}`
                                    : user.name
                                }
                            </CardTitle>
                            <CardDescription className="mt-1">
                                {profile?.bio || 'E-Commerce Admin User'}
                            </CardDescription>
                        </div>
                    </div>
                    <Button onClick={handleEditProfile} variant="outline" size="sm">
                        Edit Profile
                    </Button>
                </div>
            </CardHeader>
            <CardContent className="pt-6">
                {profile ? (
                    <div className="grid gap-4 md:grid-cols-2">
                        <div className="flex items-center gap-3 text-sm">
                            <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                <Mail className="h-4 w-4" />
                            </div>
                            <div>
                                <p className="text-xs text-muted-foreground">Email</p>
                                <p className="font-medium">{user.email}</p>
                            </div>
                        </div>

                        {profile.phone && (
                            <div className="flex items-center gap-3 text-sm">
                                <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-green-500/10 text-green-600 dark:text-green-400">
                                    <Phone className="h-4 w-4" />
                                </div>
                                <div>
                                    <p className="text-xs text-muted-foreground">Phone</p>
                                    <p className="font-medium">{profile.phone}</p>
                                </div>
                            </div>
                        )}

                        {profile.address && (
                            <div className="flex items-center gap-3 text-sm">
                                <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                    <MapPin className="h-4 w-4" />
                                </div>
                                <div>
                                    <p className="text-xs text-muted-foreground">Address</p>
                                    <p className="font-medium">{profile.address}</p>
                                </div>
                            </div>
                        )}

                        {(profile.city || profile.country) && (
                            <div className="flex items-center gap-3 text-sm">
                                <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-500/10 text-orange-600 dark:text-orange-400">
                                    <MapPin className="h-4 w-4" />
                                </div>
                                <div>
                                    <p className="text-xs text-muted-foreground">Location</p>
                                    <p className="font-medium">
                                        {[profile.city, profile.country].filter(Boolean).join(', ')}
                                    </p>
                                </div>
                            </div>
                        )}

                        {profile.birth_date && (
                            <div className="flex items-center gap-3 text-sm">
                                <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-pink-500/10 text-pink-600 dark:text-pink-400">
                                    <Calendar className="h-4 w-4" />
                                </div>
                                <div>
                                    <p className="text-xs text-muted-foreground">Birth Date</p>
                                    <p className="font-medium">
                                        {new Date(profile.birth_date).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric',
                                        })}
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>
                ) : (
                    <div className="flex flex-col items-center justify-center py-8 text-center">
                        <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                            <User className="h-8 w-8 text-muted-foreground" />
                        </div>
                        <h3 className="mb-2 text-lg font-semibold">No Profile Yet</h3>
                        <p className="mb-4 text-sm text-muted-foreground">
                            Complete your profile to personalize your experience
                        </p>
                        <Button onClick={handleEditProfile}>
                            Create Profile
                        </Button>
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
