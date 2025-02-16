# Visitor Management System (VMS) API

## Overview

The Visitor Management System (VMS) API is designed to manage visitor interactions within an organization. It includes features for visitor check-in/check-out, visitor data management, user roles and permissions, notifications, and system settings. This API is built using Laravel and follows best practices for RESTful API design.

## Features

- **Visitor Management**
  - Visitor check-in/check-out
  - Visitor data management
  - VIP guest management
  - Blacklist management

- **User Roles & Permissions**
  - Role-based access control (RBAC)
  - Four roles: Admin, Staff, Front Desk, Security
  - Permissions include:
    - Create/Edit Staff Accounts
    - Assign Roles
    - Generate Visitor Reports
    - View Visitor Data
    - Blacklist Management
    - VIP Guest Management
    - Visitor Check-in/Check-out
    - Tag Printing
    - Visitor Traffic Reports

- **Notifications**
  - Real-time notifications for:
    - New guest check-ins/check-outs
    - VIP guest arrivals
    - Blacklisted guest attempts
    - Missed or canceled visits

- **Settings Management**
  - Theme customization
  - Time zone management
  - Visit purpose management
  - Date & time format settings
  - Language preference
  - Data retention policies
  - Report customization
  - System security settings
  - Admin profile management
  - Company profile management

## API Endpoints

### General Settings
- `GET /admin/settings` - Fetch all current admin settings.
- `POST /admin/settings/update` - Update settings.

### User Roles & Permissions
- `GET /admin/roles` - Fetch user roles and permissions.
- `POST /admin/roles/update` - Update user roles.

### Notifications
- `GET /admin/notifications` - Fetch notification settings.
- `POST /admin/notifications/update` - Update notifications.

### Data & Privacy
- `GET /admin/data-retention` - Fetch data cleanup settings.
- `POST /admin/data-retention/update` - Update retention policies.

### System Security
- `GET /admin/security` - Fetch system security settings.
- `POST /admin/security/update` - Update security configurations.

### Admin Profile Management
- `PUT /admin/settings/profile` - Update profile information.
- `POST /admin/settings/admins` - Add new admin.

### Company Profile Management
- `GET /admin/settings/company` - Fetch company details.
- `PUT /admin/settings/company` - Update company details.

## Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/yourusername/vms-api.git
   composer install
   php artisan key:generate
   php artisan migrate
   php artisan db:seed


## Usage

Use a tool like Postman or cURL to interact with the API endpoints. Ensure you have the necessary authentication tokens and permissions to access the endpoints.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Author

Chijindu Nwokeohuru
