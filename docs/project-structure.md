# Project Structure

This document provides an overview of the project's structure, covering both the backend and frontend.

## Backend Structure

The backend is a standard Laravel application with a few key conventions:

- **`app/Http/Controllers`**: Controllers are organized by resource. For example, `ArticleController` handles all article-related requests.
- **`app/Http/Service`**: Services contain the business logic for each resource. For example, `ArticleService` handles all the logic for creating, updating, and deleting articles.
- **`app/Http/Controllers/Base`**: The `BaseController` provides common functionality for all controllers, such as `index`, `store`, `update`, and `destroy` methods.
- **`app/Http/Service/Base`**: The `BaseService` provides common functionality for all services, such as handling filters, sorters, and pagination.
- **`routes/web.php`**: All web routes are defined in this file.

## Frontend Structure

The frontend is built with Vue 3, Inertia.js, and shadcn/ui.

- **`resources/js/pages`**: Each page is a Vue component. For example, `resources/js/pages/admin/articles/Index.vue` is the main page for managing articles.
- **`resources/js/components`**: Reusable components are stored in this directory.
- **`resources/js/components/base`**: Base components, such as `BaseTable` and `BaseDataTable`, provide common functionality for building UI elements.
- **`resources/js/components/ui`**: This directory contains all the shadcn/ui components.
- **`resources/js/layouts`**: Layout components, such as `AppLayout`, define the overall structure of the application.
