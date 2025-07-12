# Roles & Permissions Module Documentation

## 1. Overview

The Roles & Permissions module provides a powerful and flexible way to manage user access control within the application. It is a configuration-driven system, meaning that all roles and permissions are defined in configuration files, providing a clear and version-controllable way to manage access rights.

This module allows you to:

-   Define custom roles (e.g., Administrator, Editor, Viewer).
-   Create granular permissions for different parts of the application.
-   Assign permissions to roles.
-   View which users are assigned to a specific role.

## 2. Key Functionalities & Workflows

The entire workflow of managing roles and permissions revolves around editing configuration files and then running a database seeder to apply the changes.

### Defining Roles

Roles are defined in the `config/roles-admin.php` file. To add a new role, simply add a new string to the array in this file.

**Example:**

```php
// config/roles-admin.php
return [
    'Administrator',
    'Editor',
    'Viewer',
];
```

### Defining Permissions

Permissions are defined in the `config/permissions-admin.php` file. This file has a more complex structure, allowing for the grouping of permissions into modules and categories.

**Structure:**

-   **Module**: The top-level grouping for permissions (e.g., `Users`, `Articles`, `CMS`).
-   **Category**: A sub-grouping within a module (e.g., `General`, `Content`).
-   **Permission**: The specific permission, with the following properties:
    -   `name`: A unique identifier for the permission.
    -   `label`: A human-readable name for the permission.
    -   `description`: A brief explanation of what the permission allows.
    -   `roles`: An array of roles that should be assigned this permission.

**Example:**

```php
// config/permissions-admin.php
return [
    'Users' => [
        [
            'category_module' => 'General',
            'name' => 'users.view',
            'label' => 'View Users',
            'description' => 'Allows viewing the list of users.',
            'roles' => ['Administrator', 'Editor'],
        ],
        [
            'category_module' => 'General',
            'name' => 'users.edit',
            'label' => 'Edit Users',
            'description' => 'Allows editing user details.',
            'roles' => ['Administrator'],
        ],
    ],
];
```

### Database Seeding

After making any changes to the `config/roles-admin.php` or `config/permissions-admin.php` files, you **must** run the database seeders to apply the changes to the application.

Run the following command in your terminal:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Managing Members

You can view which users are assigned to a specific role through the admin panel.

1.  Navigate to **Roles & Permissions** in the admin menu.
2.  Select the role you want to view.
3.  Click on the **Members** tab.

To assign a role to a user, you will need to go to the **Users** module. (For more details, see the *User Management Documentation*).

## 3. UI References

### Permissions Tab

This tab shows all the permissions that are assigned to the selected role, grouped by module and category.

![Permissions Tab](path/to/permissions-tab-screenshot.png)

### Members Tab

This tab lists all the users who have been assigned the selected role.

![Members Tab](path/to/members-tab-screenshot.png)

## 4. Best Practices

-   **Clear Naming Conventions**: Use a consistent and clear naming convention for your roles and permissions (e.g., `module.action`).
-   **Keep Descriptions Updated**: Ensure that the descriptions for permissions are always up-to-date and accurately reflect what the permission does.
-   **Always Re-seed**: Remember to run the database seeder after every change to the configuration files. This is a critical step that is easy to forget.
