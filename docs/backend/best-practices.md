# Backend Best Practices

This document outlines the best practices for writing backend code, focusing on coding standards, folder naming conventions, and reusability strategies.

## Coding Standards

- **Follow PSR-12:** All PHP code must adhere to the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard.
- **Use strict types:** Enable strict types in all PHP files: `declare(strict_types=1);`.
- **Use type hinting:** Use type hinting for all function arguments, return types, and class properties.

## Folder Naming Conventions

- **Controllers:** Controllers should be named using the singular form of the resource, followed by `Controller`. For example, `ArticleController`.
- **Services:** Services should be named using the singular form of the resource, followed by `Service`. For example, `ArticleService`.
- **Models:** Models should be named using the singular form of the resource. For example, `Article`.

## Reusability Strategies

- **BaseController:** The `BaseController` provides common functionality for all controllers. Extend this class to reuse common logic for `index`, `store`, `update`, and `destroy` actions.
- **BaseService:** The `BaseService` provides common functionality for all services. Extend this class to reuse common logic for handling filters, sorters, and pagination.
