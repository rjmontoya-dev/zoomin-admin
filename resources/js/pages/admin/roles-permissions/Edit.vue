<script setup lang="ts">
import { ref } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue';
import AppMainContent from '@/components/AppMainContent.vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import { Edit } from 'lucide-vue-next';
import {  router } from '@inertiajs/vue3';

interface Subcategory {
    name: string;
    description: string;
    canManage: boolean;
}

interface Category {
    id: string;
    name: string;
    description: string | null;
    canManage: boolean | null;
    subcategories: Subcategory[];
}

interface Module {
    id: number;
    name: string;
    categories: Category[];
}

interface Member {
    id: number;
    name: string;
    email: string;
    profile_photo_url: string;
    created_at: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Module[];
    members: Member[];
}

const props = defineProps<{
  item: Object;
}>()
console.log(props.item.data.id)
const breadcrumbs = [
    {
        title: 'Roles & Permissions',
        href: route('admin.roles-permissions.index'),
    },
    {
        title: props.item.data?.name,
        href: route('admin.roles-permissions.edit', props.item.data?.id),
    },
];

const activeTab = ref('permissions');

const tabs = [
    { label: 'Permissions', value: 'permissions' },
    { label: 'Members', value: 'members' },
];

</script>

<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" :title="`Role: ${item.data?.name}`">
            <BaseNavTabs :tabs="tabs" v-model="activeTab" />
            <div v-if="activeTab === 'permissions'" class="mt-4 p-4 space-y-4 flex-1 overflow-y-auto p-6">
                <Card v-for="module in item.data?.permissions" :key="module.id">
                    <CardContent class="p-6">
                        <h3 class="text-lg font-semibold">{{ module.name }}</h3>
                        <div v-for="category in module.categories" :key="category.id" class="mt-4">
                            <h4 class="font-medium">{{ category.name }}</h4>
                            <Table class="mt-2">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Permission</TableHead>
                                        <TableHead>Description</TableHead>
                                        <TableHead>Status</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="subcategory in category.subcategories" :key="subcategory.name">
                                        <TableCell>{{ subcategory.name }}</TableCell>
                                        <TableCell>{{ subcategory.description }}</TableCell>
                                        <TableCell>
                                            <span v-if="subcategory.canManage" class="text-green-500">✓</span>
                                            <span v-else class="text-red-500">✗</span>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>
            <div v-if="activeTab === 'members'" class="mt-4 mt-4 p-4 space-y-4 flex-1 overflow-y-auto p-6">
                <Card>
                    <CardContent class="p-6">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="item.data?.members && item.data.members.length > 0">
                                    <TableRow v-for="user in item.data.members" :key="user.id">
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>
                                        <button
                                        @click="router.get(route('admin.users.edit', user.id))"
                                        class="auto-focus p-2 text-gray-600 hover:text-gray-800"
                                        title="Edit"
                                        >
                                        <Edit class="h-4 w-4" />
                                        </button>
                                    </TableCell>
                                    </TableRow>
                                </template>
                                <template v-else>
                                    <TableRow>
                                    <TableCell colspan="3" class="text-center py-4  text-gray-500">
                                        <h3 class="text-lg font-semibold">No Data Found</h3>
                                    </TableCell>
                                    </TableRow>
                                </template>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
