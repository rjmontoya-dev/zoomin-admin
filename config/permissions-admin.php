<?php


return [

    'Roles & Permissions Management' => [
        [
            'label' => 'Can view roles',
            'name' => 'view-roles',
            'description' => 'Allow the admin to view roles',
            'guard_name' => 'admin',
            'category_module' => 'Accounts',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can view permission per role',
            'name' => 'view-permission-per-role',
            'description' => 'Allow the admin to view permission per role',
            'guard_name' => 'admin',
            'category_module' => 'Accounts',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can view the members per role',
            'name' => 'view-members-per-role',
            'description' => 'Allow the admin to view members per role',
            'guard_name' => 'admin',
            'category_module' => 'Accounts',
            'roles' => ['Super Admin', 'Admin'],
        ],
    ],

    'Articles Module' => [
        [
            'label' => 'Can create articles',
            'name' => 'create-articles',
            'description' => 'Allow the admin to create articles',
            'guard_name' => 'admin',
            'category_module' => 'Articles',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can view articles',
            'name' => 'view-articles',
            'description' => 'Allow the admin to view articles',
            'guard_name' => 'admin',
            'category_module' => 'Articles',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can update articles',
            'name' => 'update-articles',
            'description' => 'Allow the admin to update articles',
            'guard_name' => 'admin',
            'category_module' => 'Articles',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can archive articles',
            'name' => 'archive-articles',
            'description' => 'Allow the admin to archive articles',
            'guard_name' => 'admin',
            'category_module' => 'Articles',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can restore articles',
            'name' => 'restore-articles',
            'description' => 'Allow the admin to restore archived articles',
            'guard_name' => 'admin',
            'category_module' => 'Articles',
            'roles' => ['Super Admin', 'Admin'],
        ],
    ],

    'Users Module' => [
        [
            'label' => 'Can create users',
            'name' => 'create-users',
            'description' => 'Allow the admin to create users',
            'guard_name' => 'admin',
            'category_module' => 'Users',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can view users',
            'name' => 'view-users',
            'description' => 'Allow the admin to view users',
            'guard_name' => 'admin',
            'category_module' => 'Users',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can update users',
            'name' => 'update-users',
            'description' => 'Allow the admin to update users',
            'guard_name' => 'admin',
            'category_module' => 'Users',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can archive users',
            'name' => 'archive-users',
            'description' => 'Allow the admin to archive users',
            'guard_name' => 'admin',
            'category_module' => 'Users',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can restore users',
            'name' => 'restore-users',
            'description' => 'Allow the admin to restore archived users',
            'guard_name' => 'admin',
            'category_module' => 'Users',
            'roles' => ['Super Admin', 'Admin'],
        ],
    ],

    'CMS Module' => [
        [
            'label' => 'Can view CMS content',
            'name' => 'view-cms',
            'description' => 'Allow the admin to view CMS content',
            'guard_name' => 'admin',
            'category_module' => 'CMS',
            'roles' => ['Super Admin', 'Admin'],
        ],
        [
            'label' => 'Can update CMS content',
            'name' => 'update-cms',
            'description' => 'Allow the admin to update CMS content',
            'guard_name' => 'admin',
            'category_module' => 'CMS',
            'roles' => ['Super Admin', 'Admin'],
        ],
    ],

    'Dashboard' => [
        [
            'label' => 'Can view dashboard',
            'name' => 'view-dashboard',
            'description' => 'Allow the admin to view the dashboard',
            'guard_name' => 'admin',
            'category_module' => 'Dashboard',
            'roles' => ['Super Admin'],
        ],
    ],

    'Activity Logs' => [
        [
            'label' => 'Can view activity logs',
            'name' => 'view-activity-logs',
            'description' => 'Allow the admin to view system activity logs',
            'guard_name' => 'admin',
            'category_module' => 'Logs',
            'roles' => ['Super Admin'],
        ],
    ],

];
