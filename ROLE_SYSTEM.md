# Role-Based Access Control System

## Overview
This project implements a comprehensive role-based access control (RBAC) system with 5 distinct roles and granular permissions for each module.

## Roles

### 1. Super Administrator
- **Access Level**: Full system access
- **Capabilities**:
  - All modules and permissions
  - User management (create, edit, delete users)
  - System configuration
  - Advanced reports and analytics
  - Role and permission management

### 2. Administrator
- **Access Level**: Management level
- **Capabilities**:
  - All operational modules
  - Reports and analytics
  - Cannot manage users or system settings
  - Full CRUD operations on business data

### 3. Manager
- **Access Level**: Department level
- **Capabilities**:
  - Stock management
  - Sales and procurement
  - Customer management
  - Basic reports
  - Cannot delete critical records
  - No advanced analytics

### 4. Staff
- **Access Level**: Operational level
- **Capabilities**:
  - Basic stock operations
  - Sales transactions
  - Customer interactions
  - Limited reports
  - View and edit permissions only
  - Cannot delete records

### 5. Viewer
- **Access Level**: Read-only
- **Capabilities**:
  - View-only access to most modules
  - Basic reports
  - No modification permissions
  - No data entry capabilities

## Module Access Matrix

| Module | Super Admin | Admin | Manager | Staff | Viewer |
|--------|-------------|-------|---------|-------|--------|
| **Dashboard** | Full | Full | Full | Limited | Read |
| **Stationary Items** | Full | Full | Full | View/Edit | Read |
| **Godowns** | Full | Full | Full | View | Read |
| **Customers** | Full | Full | Full | View/Edit | Read |
| **Schools** | Full | Full | Full | View | Read |
| **Students** | Full | Full | Full | View/Edit | Read |
| **Sales** | Full | Full | Full | Create/View | Read |
| **Stationary Awards** | Full | Full | Full | View/Edit | Read |
| **Procurements** | Full | Full | Full | View/Edit | Read |
| **Stock Movements** | Full | Full | Full | Create/View | Read |
| **Reports** | Full | Full | Limited | Basic | Basic |
| **User Management** | Full | None | None | None | None |
| **System Settings** | Full | None | None | None | None |

## Permissions

### Permission Structure
Permissions follow the pattern: `{module}.{action}`

Examples:
- `stationary-items.view` - View stationary items
- `sales.create` - Create sales records
- `users.delete` - Delete users
- `reports.export` - Export reports

### Available Permissions by Module

#### Dashboard
- `dashboard.view` - View dashboard
- `dashboard.reports` - View reports
- `dashboard.analytics` - View analytics

#### Stationary Items
- `stationary-items.view` - View items
- `stationary-items.create` - Create items
- `stationary-items.edit` - Edit items
- `stationary-items.delete` - Delete items

#### Godowns
- `godowns.view` - View godowns
- `godowns.create` - Create godowns
- `godowns.edit` - Edit godowns
- `godowns.delete` - Delete godowns

#### Customers
- `customers.view` - View customers
- `customers.create` - Create customers
- `customers.edit` - Edit customers
- `customers.delete` - Delete customers

#### Schools
- `schools.view` - View schools
- `schools.create` - Create schools
- `schools.edit` - Edit schools
- `schools.delete` - Delete schools

#### Students
- `students.view` - View students
- `students.create` - Create students
- `students.edit` - Edit students
- `students.delete` - Delete students

#### Sales
- `sales.view` - View sales
- `sales.create` - Create sales
- `sales.edit` - Edit sales
- `sales.delete` - Delete sales

#### Stationary Awards
- `stationary-awards.view` - View awards
- `stationary-awards.create` - Create awards
- `stationary-awards.edit` - Edit awards
- `stationary-awards.delete` - Delete awards

#### Procurements
- `procurements.view` - View procurements
- `procurements.create` - Create procurements
- `procurements.edit` - Edit procurements
- `procurements.delete` - Delete procurements

#### Stock Movements
- `stock-movements.view` - View movements
- `stock-movements.create` - Create movements
- `stock-movements.edit` - Edit movements
- `stock-movements.delete` - Delete movements

#### Reports
- `reports.view` - View reports
- `reports.export` - Export reports
- `reports.advanced` - Advanced reports

#### User Management
- `users.view` - View users
- `users.create` - Create users
- `users.edit` - Edit users
- `users.delete` - Delete users
- `users.assign-roles` - Assign user roles

#### System Settings
- `settings.view` - View settings
- `settings.edit` - Edit settings
- `settings.system` - System settings

## Implementation

### Database Structure
- `roles` table - Stores role information
- `permissions` table - Stores permission information
- `role_permission` table - Many-to-many relationship between roles and permissions
- `users` table - Has `role_id` foreign key

### Middleware
- `CheckRole` middleware - Check if user has specific role
- `CheckPermission` middleware - Check if user has specific permission

### Usage Examples

#### In Controllers
```php
// Check role
if ($request->user()->hasRole('admin')) {
    // Admin logic
}

// Check permission
if ($request->user()->hasPermission('sales.create')) {
    // Create sale logic
}

// Check module access
if ($request->user()->hasModulePermission('customers')) {
    // Customer module logic
}
```

#### In Routes
```php
// Role-based route protection
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin-panel', AdminController::class);
});

// Permission-based route protection
Route::middleware(['permission:users.create'])->group(function () {
    Route::post('/users', UserController::class);
});
```

#### In Vue Components
```vue
<template>
  <div v-if="$page.props.auth.user.permissions.includes('sales.create')">
    <button @click="createSale">Create Sale</button>
  </div>
</template>
```

## Default Super Admin
- **Email**: admin@example.com
- **Password**: (set during registration)
- **Role**: Super Administrator

## Security Features
- Role-based access control
- Permission-based authorization
- Middleware protection
- Frontend permission checks
- Secure role assignment
- Audit trail ready

## Future Enhancements
- Role hierarchy
- Dynamic permissions
- Permission groups
- Audit logging
- Role templates
- Bulk role assignment




