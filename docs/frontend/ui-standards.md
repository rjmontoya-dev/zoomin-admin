# Frontend UI Standards

This document outlines the UI standards for the frontend, focusing on the use of shadcn/ui components.

## Buttons

- **Primary Actions:** Use the default button style for primary actions, such as "Save" or "Create".
- **Secondary Actions:** Use the `outline` variant for secondary actions, such as "Cancel" or "Reset".
- **Destructive Actions:** Use the `destructive` variant for actions that have a destructive effect, such as "Delete" or "Archive".

### Example

```vue
<template>
  <Button>Primary</Button>
  <Button variant="outline">Secondary</Button>
  <Button variant="destructive">Destructive</Button>
</template>
```

## Forms

Forms should be built using the `Input`, `Label`, and `Button` components. All form elements should be wrapped in a `div` with the class `space-y-2` to ensure consistent spacing.

### Example

```vue
<template>
  <div class="space-y-2">
    <Label for="name">Name</Label>
    <Input id="name" v-model="form.name" />
  </div>
  <div class="space-y-2">
    <Label for="email">Email</Label>
    <Input id="email" v-model="form.email" type="email" />
  </div>
  <Button type="submit">Submit</Button>
</template>
