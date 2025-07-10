<script setup lang="ts">
import { computed, ref } from 'vue'
import { Card, CardContent } from '../../../components/ui/card'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '../../../components/ui/table'
import AppLayout from '../../../layouts/AppLayout.vue';
import AppMainContent from '../../../components/AppMainContent.vue';
import BaseNavTabs from '../../../components/base/BaseNavTabs.vue';

interface Permission {
  id: number
  name: string
}

interface Role {
  id: number;
  name: string
  permissions: Permission[]
}

interface User {
  id: number;
  name: string;
  email: string;
}

const props = defineProps<{
  role: Role
  permissions: Permission[]
  users: User[]
}>()

const breadcrumbs = [
    {
        title: 'Roles & Permissions',
        href: route('admin.roles-permissions.index'),
    },
    {
        title: props.role.name,
        href: route('admin.roles-permissions.show', props.role.id),
    },
];

const rolePermissions = computed(() => {
  return props.role.permissions.map((p: Permission) => p.name)
})

function hasPermission(permissionName: string) {
  return rolePermissions.value.includes(permissionName)
}

const activeTab = ref('permissions');

const tabs = [
    { label: 'Permissions', value: 'permissions' },
    { label: 'Members', value: 'members' },
];

</script>

<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" :title="`Role: ${role.name}`">
            <BaseNavTabs :tabs="tabs" v-model="activeTab" />

            <div v-if="activeTab === 'permissions'" class="mt-4 p-4">
                <Card>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Permission</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="permission in permissions" :key="permission.id">
                                    <TableCell>{{ permission.name }}</TableCell>
                                    <TableCell>
                                        <span v-if="hasPermission(permission.name)" class="text-green-500">✓</span>
                                        <span v-else class="text-red-500">✗</span>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
            <div v-if="activeTab === 'members'" class="mt-4">
                <Card>
                    <CardContent class="p-6">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users" :key="user.id">
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
