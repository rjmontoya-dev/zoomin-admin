# CMS Module Documentation

## 1. Overview

The CMS module is a flexible and data-driven system for managing content within a Laravel application. It allows developers to define the structure of various content pages through a configuration file, which then dynamically generates the necessary UI in the admin panel for content editors to manage the data. The module supports a wide range of field types, from simple text inputs to complex nested data structures, and includes features for SEO management, content synchronization, and caching.

## 2. Configuration

The core of the CMS module is the `config/cms.php` file, which defines the schema for all manageable content pages. This file returns an array of page schemas.

### Page Schema Definition

Each page schema is an object with the following keys:

-   **`id`** (`string`): A unique identifier for the page.
-   **`slug`** (`string`): A URL-friendly identifier used for routing and database lookups.
-   **`label`** (`string`): A human-readable name for the page, displayed in the admin panel.
-   **`show_metadata`** (`boolean`): Determines whether the SEO metadata section is available for the page.
-   **`sections`** (`array`): An array of content sections.

### Section Definition

Each item in the `sections` array is an object with the following keys:

-   **`id`** (`string`): A unique identifier for the section.
-   **`label`** (`string`): The display name for the section tab in the editor.
-   **`description`** (`string`): A brief explanation of the section's purpose.
-   **`items`** (`array`): An array of content fields.

### Field (Item) Definition

Each item in the `items` array is an object that defines a field in the section:

-   **`id`** (`string`): A unique identifier for the field within the section.
-   **`type`** (`string`): The field type, which determines the UI component to render (e.g., `text`, `textarea`, `image`, `list_table`).
-   **`label`** (`string`): The display name for the field.
-   **`description`** (`string`): Additional guidance for the content editor.
-   **`rules`** (`string`): Laravel validation rules for the field.
-   **`summary_field`** (`boolean`): If `true`, this field will be used as a summary in list views.

### Example Configuration:

```php
// config/cms.php

return [
    [
        'id' => 'home_page',
        'slug' => 'home',
        'label' => 'Home Page',
        'show_metadata' => true,
        'sections' => [
            [
                'id' => 'banner_section',
                'label' => 'Banner',
                'description' => 'Main banner content for the home page.',
                'items' => [
                    [
                        'id' => 'banner_title',
                        'type' => 'text',
                        'label' => 'Banner Title',
                        'description' => 'The main title for the banner.',
                        'rules' => 'required|string|max:255',
                        'summary_field' => true,
                    ],
                    [
                        'id' => 'banner_image',
                        'type' => 'image',
                        'label' => 'Banner Image',
                        'description' => 'Upload the main banner image.',
                        'rules' => 'required|image',
                    ],
                ],
            ],
        ],
    ],
];
```

## 3. Sync Process

The `sync:cms` artisan command is used to synchronize the page schemas from `config/cms.php` with the `cms_pages` table in the database.

### Command

```bash
php artisan sync:cms
```

### Logic

The command, defined in `app/Console/Commands/SyncCms.php`, iterates through each page schema in the configuration file. It uses the `firstOrCreate` Eloquent method on the `CmsPage` model. This ensures that a database record is created for each page defined in the config if it doesn't already exist, based on the `slug`. If a page with the same `slug` already exists, it remains untouched.

It is crucial to run this command whenever you add a new page to `config/cms.php` or modify an existing page's `slug`.

## 4. Admin Panel UI

The admin panel UI is built with Vue.js and consists of two primary components.

-   **`resources/js/pages/admin/cms/Index.vue`**: This component displays a list of all CMS pages from the `cms_pages` table. It provides a quick overview and navigation to the edit page for each item.
-   **`resources/js/pages/admin/cms/Edit.vue`**: This is the main content editor. When a user navigates to edit a page, this component dynamically generates a tabbed interface based on the `sections` defined for that page in `config/cms.php`. Each tab corresponds to a section, and within each tab, the appropriate form fields are rendered based on the `items` array.

## 5. Routes & Controller Logic

The HTTP requests for the CMS admin panel are handled by `app/Http/Controllers/CmsPage/PagesController.php`.

-   **`index()`**: Fetches all pages from the database via `PagesService` and renders the `Index.vue` component.
-   **`edit(CmsPage $page)`**: Fetches a specific page and its schema, then renders the `Edit.vue` component with the necessary data.
-   **`update(Request $request, CmsPage $page)`**: Validates and saves the updated content for a page, using the `PagesService` to handle the logic.

## 6. Service Logic

The core business logic of the CMS module is encapsulated in `app/Http/Service/CmsPage/PagesService.php`. This service is responsible for:

-   **Schema Retrieval**: Fetching and parsing the page schemas from `config/cms.php`.
-   **Content Building**: Preparing the content structure for the `Edit.vue` component.
-   **Validation**: Dynamically generating and applying validation rules based on the schema.
-   **Update Handling**: Processing and saving the content submitted from the admin panel.
-   **Caching**: Implementing caching strategies to improve performance.

## 7. Model

The `app/Models/Admin/Pages/CmsPage.php` Eloquent model represents a single page in the `cms_pages` table.

A key feature of this model is the `content` attribute, which is cast to an array:

```php
protected $casts = [
    'content' => 'array',
];
```

This automatically handles the serialization of the page's content to JSON when saving to the database and deserialization back to a PHP array when retrieving it.

## 8. Best Practices

-   **Clear Schemas**: Always define clear and well-structured schemas in `config/cms.php`. This is the foundation of the entire module.
-   **Validation**: Use Laravel's validation rules for all fields to ensure data integrity.
-   **Sync Command**: Remember to run `php artisan sync:cms` after adding or modifying page schemas in the configuration.
-   **Performance**: Optimize images before uploading and leverage the built-in caching mechanisms to ensure the front-end loads quickly.

## 9. Extensibility

The module is designed to be extensible.

-   **Custom Field Types**: You can add new field types by:
    1.  Creating a new Vue component for the field's UI.
    2.  Updating the `PagesService` and `Edit.vue` to recognize and render the new field type.
-   **Custom Logic**: The `PagesService` can be extended with custom methods to handle more complex scenarios.
-   **Future Enhancements**: The module can be enhanced with features like:
    -   Content versioning and history.
    -   Role-based user permissions for editing specific pages or sections.
    -   Localization for multi-language support.
