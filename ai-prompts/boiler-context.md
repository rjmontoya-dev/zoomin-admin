# Project Context & Coding Guide

You are an expert Laravel and Vue.js developer. Your task is to help me build a new feature for an existing project. It is critical that you follow the established architecture, conventions, and coding style precisely.

## Core Tech Stack
- **Backend:** Laravel 11
- **Frontend:** Inertia.js with Vue.js 3 (Composition API)
- **Styling:** Tailwind CSS with Shadcn-Vue components.
- **Authentication:** Laravel Fortify

## Key Architectural Patterns
The project uses a **Service-Controller pattern** with **Base Class Abstraction** to enforce DRY principles and maintainability.

1.  **`BaseController` & `BaseService`:** All new modules must extend these base classes. The controller's responsibility is to handle HTTP requests and responses, while the service handles all business logic (querying, filtering, data manipulation).
2.  **`FormRequest` for Validation:** All `store` and `update` operations must use dedicated `FormRequest` classes for validation.
3.  **API Resources for Transformation:** Data returned to the Inertia frontend must be formatted using `JsonResource` classes.
4.  **Named Routes:** All routes must be named and grouped logically within the `routes/web.php` file.

## Task: Create a New CRUD Module for "{FEATURE_NAME}"

Your goal is to create all the necessary files for a new CRUD module for **{FEATURE_NAME}**. Please generate the files one by one, following the structure below.

### Step-by-Step File Creation Plan:

1.  **Migration:**
    - Create a migration file for the `{table_name}` table.
    - Include the following fields: `{field_list}`.
    - Remember to include `softDeletes()`.

2.  **Model:**
    - Create the model at `app/Models/Admin/{FEATURE_NAME}.php`.
    - It should extend `App\Models\System\BaseModel`.
    - Use the `SoftDeletes` trait.
    - Define the `$fillable` array.

3.  **Service:**
    - Create the service at `app/Http/Service/{FEATURE_NAME}/{FEATURE_NAME}Service.php`.
    - It must extend `App\Http\Service\Base\BaseService`.
    - In the `initialize()` method, configure searchable fields, filters, and sorters.

4.  **Form Requests:**
    - Create `app/Http/Requests/Admin/{FEATURE_NAME}/{FEATURE_NAME}StoreRequest.php`.
    - Create `app/Http/Requests/Admin/{FEATURE_NAME}/{FEATURE_NAME}UpdateRequest.php`.
    - Define the validation `rules()` for the model's fields.

5.  **API Resources:**
    - Create `app/Http/Resources/Admin/{FEATURE_NAME}/IndexCollection.php` to format data for the index page.
    - Create `app/Http/Resources/Admin/{FEATURE_NAME}/EditResource.php` to format data for the edit page.

6.  **Controller:**
    - Create the controller at `app/Http/Controllers/Admin/{FEATURE_NAME}/{FEATURE_NAME}Controller.php`.
    - It must extend `App\Http\Controllers\Base\BaseController`.
    - Implement the abstract methods: `setModel()`, `setService()`, `setIndexResource()`, `setEditResource()`, and `getViewPath()`.
    - Define the standard CRUD methods (`index`, `create`, `store`, `edit`, `update`, `archive`, `restore`) which should call their `parent::base...` counterparts.

7.  **Routes:**
    - Add the resource routes to `routes/web.php` under the `admin` prefix group.
    - Use the new controller and name the routes appropriately (e.g., `admin.{feature_name}.index`).

8.  **Frontend (Vue Components):**
    - I will handle the frontend Vue components separately after the backend is complete.

Please start with step 1 and ask for confirmation before proceeding to the next step.
