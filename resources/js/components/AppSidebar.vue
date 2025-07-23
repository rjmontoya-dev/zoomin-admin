<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Notebook, Newspaper, Construction, FolderCog, Users, ScrollText } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'CMS',
        href: '/admin/cms',
        icon: Notebook,
    },
    {
        title: 'Articles',
        href: '/admin/articles',
        icon: Newspaper,
    },
    {
        title: 'Maintenance',
        href: '/admin/maintenance',
        icon: Construction,
    },
    // {
    //     title: 'Notification',
    //     href: '/admin/articles',
    //     icon: LayoutGrid,
    // },
    {
        title: 'Roles & Permissions',
        href: '/admin/roles-permissions',
        icon: FolderCog,
    },
    {
        title: 'Users',
        href: '/admin/users',
        icon: Users,
    },
    {
        title: 'Activity Log',
        href: '/admin/activity-log',
        icon: ScrollText,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/rjmontoya-dev/template-v0.1',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: '/docs/',
        icon: BookOpen,
    }
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
