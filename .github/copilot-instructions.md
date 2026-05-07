# GitHub Copilot Instructions

## Project Overview

This project is a modern web application built with:

* Laravel
* TailwindCSS
* DaisyUI
* MySQL
* JavaScript
* Blade Templates

The application focuses on clean architecture, maintainable code, consistent naming conventions, reusable components, and responsive UI.

---

# General Development Rules

## Code Quality

* Always write clean, readable, and maintainable code.
* Prefer simplicity over unnecessary abstraction.
* Avoid duplicated code.
* Use reusable components whenever possible.
* Keep functions and methods small and focused.
* Use meaningful variable and method names.
* Never use hardcoded magic values unless necessary.
* Add comments only when logic is complex.
* Follow existing project structure and coding style.

---

# Naming Conventions

## Database

### Tables

* Use plural snake_case.
* Examples:

  * users
  * job_requests
  * payment_transactions

### Columns

* Use snake_case.
* Foreign keys must use:

  * user_id
  * created_by
  * assigned_to

### Primary Key

* Always use:

  * id

### Boolean Columns

Must start with:

* is_
* has_
* can_

Examples:

* is_deleted
* is_active
* has_attachment

### Timestamps

Use Laravel standard:

* created_at
* updated_at

---

## Laravel

### Models

* Use singular PascalCase.
* Examples:

  * User
  * JobRequest
  * PaymentTransaction

### Controllers

* Suffix with Controller.
* Examples:

  * UserController
  * JobRequestController

### Methods

* Use camelCase.
* Examples:

  * getUserJobs()
  * storePayment()
  * approveRequest()

### Routes

* Use kebab-case.
* Examples:

  * /job-request
  * /payment-history

### Blade Files

* Use kebab-case.
* Examples:

  * job-request.blade.php
  * payment-history.blade.php

---

## JavaScript

### Variables

* Use camelCase.
* Use const by default.
* Use let only when reassignment is needed.
* Never use var.

### Functions

* Use camelCase.
* Prefer arrow functions for callbacks.

### Constants

* Use UPPER_SNAKE_CASE.

Example:

```js
const MAX_UPLOAD_SIZE = 5;
```

---

# UI/UX Rules

## General Design Principles

* Modern and minimalistic UI.
* Clean spacing.
* Consistent border radius.
* Soft shadows.
* Responsive layout.
* Mobile-first approach.
* Avoid clutter.
* Use proper visual hierarchy.

---

# Color Theme

## Primary Theme

### Primary Color

* Indigo
* Tailwind:

  * indigo-600

### Secondary Color

* Slate
* Tailwind:

  * slate-700

### Success Color

* emerald-500

### Warning Color

* amber-500

### Danger Color

* red-500

### Background

* gray-50

### Card Background

* white

### Border Color

* gray-200

---

# Typography

## Font

Use:

* Inter
* Instrument Sans
* sans-serif fallback

## Text Rules

* Headings should be semibold or bold.
* Body text should remain readable.
* Avoid excessive font sizes.
* Use proper line height.

---

# Component Styling Rules

## Buttons

### Primary Button

```html
btn btn-primary
```

### Secondary Button

```html
btn btn-outline
```

### Danger Button

```html
btn btn-error
```

### Button Rules

* Use icons only when necessary.
* Buttons must have hover states.
* Buttons must have loading states for async actions.

---

## Cards

* Use rounded-xl or rounded-2xl.
* Use subtle shadow.
* Add sufficient padding.
* Avoid excessive nested cards.

Example:

```html
<div class="rounded-2xl bg-white shadow-sm border border-gray-200 p-6">
```

---

## Forms

* Use consistent spacing.
* Labels must always exist.
* Show validation errors below fields.
* Required fields should be clearly indicated.
* Inputs must have focus states.

Example:

```html
<input class="input input-bordered w-full" />
```

---

# Backend Rules

## Validation

* Always validate request data.
* Never trust frontend input.
* Use Form Request validation when possible.

Example:

```php
$request->validate([
    'name' => 'required|string|max:255'
]);
```

---

## Database

* Use migrations for schema changes.
* Avoid raw SQL unless necessary.
* Use Eloquent relationships.
* Use indexes for frequently searched columns.

---

## Relationships

### Foreign Keys

Use explicit foreign keys.

Example:

```php
public function assignedUser()
{
    return $this->belongsTo(User::class, 'assigned_to');
}
```

---

# API Rules

## Response Format

### Success Response

```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": []
}
```

### Error Response

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {}
}
```

---

# Security Rules

* Never expose sensitive credentials.
* Always use CSRF protection.
* Validate uploaded files.
* Sanitize user input.
* Prevent SQL injection.
* Prevent XSS vulnerabilities.
* Never trust client-side validation only.
* Use Laravel authentication and authorization.

---

# File Upload Rules

## Uploads

* Validate mime types.
* Validate file size.
* Store uploads properly.
* Generate unique filenames.

Example:

```php
$fileName = time() . '_' . $file->getClientOriginalName();
```

---

# Git Rules

## Branch Naming

Use:

* feature/
* fix/
* hotfix/
* refactor/

Examples:

* feature/job-approval
* fix/payment-validation

---

## Commit Messages

Use clear commit messages.

Examples:

* add job approval feature
* fix validation issue on payment form
* refactor dashboard layout

---

# Performance Rules

* Avoid N+1 queries.
* Use eager loading.
* Paginate large datasets.
* Optimize database indexes.
* Minimize unnecessary API calls.
* Lazy load heavy components.

Example:

```php
Job::with(['assignedUser', 'creator'])->paginate(10);
```

---

# Error Handling

* Always handle possible failures.
* Use try-catch when needed.
* Return meaningful error messages.
* Log server-side errors.

---

# Frontend Behavior Rules

* Use loading indicators.
* Disable buttons during submit.
* Show toast notifications.
* Confirm destructive actions.
* Prevent duplicate submissions.
* Use modals consistently.

---

# Accessibility Rules

* Use semantic HTML.
* Buttons must always be accessible.
* Inputs must have labels.
* Ensure sufficient color contrast.
* Support keyboard navigation.

---

# Laravel Blade Rules

* Keep Blade templates clean.
* Move heavy logic to controller/service.
* Use components for reusable UI.
* Avoid deeply nested Blade conditions.

---

# Service Layer Rules

* Complex business logic should use service classes.
* Controllers should remain thin.
* Services should focus on one responsibility.

---

# Trigger Rules

## Database Trigger Usage

* Use triggers only for database-level automation.
* Keep trigger logic simple.
* Document all triggers.
* Avoid overly complex trigger chains.

Example use case:

* Auto increment number_of_the_day based on start_at date.

---

# Logging Rules

* Use Laravel logging.
* Log important system actions.
* Log exceptions.
* Avoid logging sensitive data.

---

# Testing Rules

* Write feature tests for critical flows.
* Write unit tests for business logic.
* Test validation.
* Test permissions.

---

# Preferred Architecture

## Recommended Structure

* Controllers
* Services
* Repositories (optional)
* Models
* Form Requests
* Blade Components

---

# Copilot Behavior Instructions

When generating code:

* Follow all naming conventions strictly.
* Prefer reusable and scalable solutions.
* Use TailwindCSS utility classes consistently.
* Maintain clean indentation.
* Do not generate unnecessary comments.
* Avoid deprecated syntax.
* Generate responsive UI by default.
* Use Laravel best practices.
* Prefer Eloquent over raw SQL.
* Keep UI visually consistent with the defined color theme.
* Always include validation.
* Always consider security.
* Always consider performance.
* Use clear and maintainable structure.

---

# Preferred Tailwind Utilities

## Layout

* container
* mx-auto
* grid
* flex
* gap-4
* gap-6
* p-4
* p-6

## Cards

* rounded-2xl
* shadow-sm
* border
* border-gray-200
* bg-white

## Typography

* text-sm
* text-base
* text-lg
* font-medium
* font-semibold

## Responsive

* md:grid-cols-2
* lg:grid-cols-3
* xl:grid-cols-4

---

# Final Rule

All generated code must prioritize:

1. Readability
2. Maintainability
3. Consistency
4. Scalability
5. Security
6. Performance
7. User Experience
