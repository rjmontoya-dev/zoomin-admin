### Frontend Architecture Overview

This document outlines the architecture, conventions, and key patterns used in the frontend codebase.

**Core Technologies:**

*   **Vue.js 3 & Composition API:** The project is built with Vue 3 and extensively uses the Composition API (`<script setup>`) for building reactive and maintainable components.
*   **TypeScript:** TypeScript is used across the entire codebase for static typing, ensuring code quality and developer productivity.
*   **Inertia.js:** The frontend is tightly coupled with the Laravel backend via Inertia.js, which allows for building a modern single-page application without the complexity of a separate API.
*   **Vite:** The project uses Vite for fast development and optimized builds.
*   **Tailwind CSS:** Styling is handled with Tailwind CSS, a utility-first CSS framework. The `cn` utility function in `resources/js/lib/utils.ts` is used to merge and manage classes.
*   **Shadcn-Vue & Radix-Vue:** The UI is built with Shadcn-Vue, a component library that provides accessible and unstyled components built on top of Radix-Vue. The components are located in `resources/js/components/ui`.
*   **Lucide Icons:** Icons are provided by the `lucide-vue-next` library.
*   **Ziggy:** The Ziggy library is used to handle Laravel routes in JavaScript, providing a seamless way to use named routes from the backend.

**Architectural Patterns:**

*   **Feature-Based Structure:** The `resources/js/pages` directory is organized by feature (e.g., `admin/articles`, `admin/projects`), with each feature containing its own pages (`Index.vue`, `Create.vue`, `Edit.vue`) and page-specific components.
*   **Base and Shared Components:**
    *   **Base Components:** Found in `resources/js/components/base`, these are fundamental UI building blocks like `BaseDataTable.vue` and `BaseTable.vue`.
    *   **Shared Components:** Found in `resources/js/components/shared`, these are components that are reused across different features, such as `FileUploader.vue` and `RichTextEditor.vue`.
*   **Composable-Based State Management:** Reusable logic and state are managed through composables in the `resources/js/composables` directory (e.g., `useAppearance.ts`, `useDateFormat.ts`).
*   **Layout System:** A flexible layout system is implemented in `resources/js/layouts`, with different layouts for the main application (`AppLayout.vue`) and authentication pages (`AuthLayout.vue`).

---

### Standardization Prompt for Creating New Modules (Updated)

**Objective:** To create a new module named `[Module Name]` that adheres to the latest frontend architecture, as demonstrated by the `articles` module. The new module will be built with TypeScript and will leverage shared base components for common UI patterns like tables and forms.

**Key Information:**

*   **Module Name:** `[Module Name]` (e.g., "Invoices", "Clients", "Tasks")
*   **Module API Endpoint:** `admin.[module-name].index` (e.g., `admin.invoices.index`)
*   **Main Fields:** `[List of main fields for the module]` (e.g., "Invoice Number", "Client Name", "Amount", "Status")
*   **Data Interface:** Define a TypeScript interface for the module's data structure (e.g., `interface [ModuleName] { ... }`).

---

#### **File Structure**

Create the following file structure under `resources/js/pages/admin/[module-name]/`:

```
resources/js/pages/admin/[module-name]/
├── Create.vue
├── Edit.vue
├── Index.vue
└── components/
    └── FormSection.vue
```

---

#### **1. `Index.vue` - Listing Page**

This file will use the `BaseTable` component to display a paginated, sortable, and filterable list of `[Module Name]` records.

*   **Layout:** Use [`AppLayout`](resources/js/layouts/AppLayout.vue:1) and [`AppMainContent`](resources/js/components/AppMainContent.vue:1). Define `breadcrumbs` and a `title`.
*   **Core Component:** Use [`BaseTable.vue`](resources/js/components/base/BaseTable.vue:1) as the primary component for displaying data.
    *   **Props:** Pass all necessary props to `BaseTable`, including `route`, `data`, `columns`, `meta`, `tabs`, `active-tab`, and `filters`.
*   **Columns Definition:** Define a `COLUMNS` constant array. Each object should have a `key` and a `label`.
*   **Custom Cells:** Use `template` slots to customize cell rendering (e.g., `#cell-status`, `#cell-actions`). This is useful for displaying badges, formatted dates, or action buttons.
*   **Actions:**
    *   **Create:** A `Button` with a `PlusCircle` icon that calls `router.get(route('admin.[module-name].create'))`.
    *   **Edit/Archive/Restore:** Place buttons within the `#cell-actions` template. Use `lucide-vue-next` icons (`Edit`, `Trash2`, `ArchiveRestore`). The actions should call the appropriate `router` methods (`.get`, `.delete`, `.post`) on the corresponding routes (`edit`, `archive`, `restore`).
*   **Filtering:**
    *   Use [`BaseNavTabs`](resources/js/components/base/BaseNavTabs.vue:1) for primary status filtering (e.g., "All", "Archived").
    *   For more complex filters, use the `#drawer-filters` slot of `BaseTable` to add custom filter controls.
*   **Notifications & Loading:**
    *   Use `Toaster` from `vue-sonner` to display flash messages from the backend.
    *   Use the [`LoadingPage`](resources/js/components/shared/LoadingPage.vue:1) component, toggled by an `isLoading` ref during router navigation.

---

#### **2. `Create.vue` & `Edit.vue` - Form Pages**

These pages will manage the form state and submission logic, wrapping the shared `FormSection` component.

*   **Script (`<script setup lang="ts">`)**
    *   **Layout:** Use [`AppLayout`](resources/js/layouts/AppLayout.vue:1) and [`AppMainContent`](resources/js/components/AppMainContent.vue:1), providing `breadcrumbs` and a `title`.
    *   **Form State:** Use `useForm` from `@inertiajs/vue3` to create a `form` object with all the necessary fields for the module.
    *   **Data Population (`Edit.vue`):** Use a `watch`er on the `item` prop to populate the `form` with data for editing.
    *   **Submission:** Create a `submit` function that sets `isLoading` to `true`, then calls `form.post` (for create) or `form.put`/`patch` (for edit). Use the `onSuccess` and `onError` callbacks to show toast notifications and reset the `isLoading` state.
*   **Template (`<template>`)**
    *   The main content area should contain the [`FormSection`](resources/js/pages/admin/articles/components/FormSection.vue:1) component, passing the `form` object and any other necessary props (like `statuses`).
    *   Include a sticky footer with "Cancel" and "Submit" buttons. The "Cancel" button should navigate back to the index page (`router.get`). The "Submit" button should call the `submit` function.

---

#### **3. `components/FormSection.vue` - Shared Form UI**

This component is responsible only for the form's UI and layout. It receives its state from the parent.

*   **Script (`<script setup lang="ts">`)**
    *   **Props:** Define props to accept the `form` object and any arrays needed for select dropdowns (e.g., `statuses`).
*   **Template (`<template>`)**
    *   Lay out the form fields using `Input`, `select`, [`RichTextEditor`](resources/js/components/shared/RichTextEditor.vue:1), etc.
    *   Bind each field to the corresponding property on the `form` prop (e.g., `v-model="form.title"`).
    *   Display validation errors for each field by checking the `form.errors` object (e.g., `<span v-if="form.errors.title">{{ form.errors.title }}</span>`).
