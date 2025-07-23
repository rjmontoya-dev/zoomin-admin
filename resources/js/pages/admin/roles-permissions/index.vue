<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useInitials } from '@/composables/useInitials';

interface User {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    users: User[];
}

interface PaginatedData {
    data: Role[];
}

const props = defineProps<{
    data: PaginatedData;
}>()

const { getInitials } = useInitials();

const breadcrumbs = [
    {
        title: 'Roles & Permissions',
        href: route('admin.roles-permissions.index'),
    },
];

</script>
<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Roles & Permissions">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                <Link v-for="role in props.data" :key="role.id" :href="route('admin.roles-permissions.edit', role.id)">
                    <Card class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <CardHeader>
                            <CardTitle>{{ role.name }}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-sm text-gray-500">
                                View permissions and members assigned to this role.
                            </p>
                            <div class="flex items-center mt-4">
                                <template v-for="user in role.users" :key="user.id">
                                    <Avatar class="-ml-2">
                                        <AvatarImage :src="user.avatar_url" :alt="user.name" />
                                        <AvatarFallback>
                                            {{ getInitials(user?.name)  }}
                                        </AvatarFallback>
                                    </Avatar>
                                </template>
                            </div>
                        </CardContent>
                    </Card>
                </Link>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
