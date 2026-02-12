# Documentation & Coding Standards

To maintain code quality and ensure the project remains maintainable, we follow several documentation and coding standards.

## Code Documentation Standards

### Backend (PHP/Laravel)

All PHP classes, methods, and properties should be documented using **PHPDoc**. This helps with IDE autocompletion and generating automated documentation.

Example:
```php
/**
 * Create a new diagnosis for a patient.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function store(Request $request)
{
    // ...
}
```

### Frontend (JavaScript/Vue)

Vue components and JavaScript functions should use **JSDoc**.

Example:
```javascript
/**
 * Formats a date string into a user-friendly format.
 * @param {string} dateString - The raw date string.
 * @returns {string} - The formatted date.
 */
function formatDate(dateString) {
    // ...
}
```

## API Documentation

If this project expands to include a RESTful API for external consumption, we recommend using **Swagger/OpenAPI**.

### Recommended Tool: L5-Swagger

[L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) is a popular package that integrates Swagger UI and OpenAPI documentation into Laravel.

**Installation**:
```bash
composer require "darkaonline/l5-swagger"
```

**Usage**:
Add annotations to your controllers to define API endpoints, parameters, and responses. Run the following command to generate the documentation:
```bash
php artisan l5-swagger:generate
```

## Future Documentation Improvements

- **Automated API generation**: Fully implement `L5-Swagger` if an external API is developed.
- **Component Libary**: Consider using [Storybook](https://storybook.js.org/) for documenting Vue components in isolation.
- **Wiki**: Use the GitHub Wiki or a similar tool for high-level project management and non-technical documentation.
```
