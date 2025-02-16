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
- `DELETE /admin/settings/delete` - Delete a setting.

### User Roles & Permissions

- `GET /admin/roles` - Fetch user roles and permissions.
- `POST /admin/roles/update` - Update user roles.

### Notifications

- `GET /admin/notifications` - Fetch notification settings.
- `POST /admin/notifications/update` - Update notifications.
- `POST /admin/notifications/mark-as-read` - Mark notifications as read.
- `DELETE /admin/notifications/clear` - Clear notifications.

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

### Analytics

- `GET /admin/analytics` - Fetch analytics data.
- `GET /admin/analytics/export` - Export analytics data.

### Staff Management

- `apiResource /admin/staff` - Manage staff accounts.
- `POST /admin/staff/{id}/deactivate` - Deactivate a staff account.

### Visitors Management

- `apiResource /admin/visitors` - Manage visitors.
- `POST /admin/visitors/{id}/vip` - Mark a visitor as VIP.
- `POST /admin/visitors/{id}/blacklist` - Blacklist a visitor.

### Visits Management

- `apiResource /admin/visits` - Manage visits.

### Front Desk Routes

- `POST /front-desk/login` - Front desk login.
- `POST /front-desk/forgot-password` - Front desk forgot password.
- `POST /front-desk/verify-otp` - Front desk verify OTP.
- `POST /front-desk/reset-password` - Front desk reset password.
- `GET /front-desk/guests` - Fetch guests.
- `GET /front-desk/guests/{id}` - Fetch guest details.
- `GET /front-desk/guests/filter` - Filter guests.
- `GET /front-desk/guests/search` - Search guests.
- `POST /front-desk/guests/{id}/check-in` - Check-in guest.
- `POST /front-desk/guests/{id}/check-out` - Check-out guest.
- `GET /front-desk/guests/{id}/print-tag` - Print guest tag.
- `POST /front-desk/guests/register` - Register guest.
- `POST /front-desk/guests/register-group` - Register group of guests.
- `GET /front-desk/history` - Fetch front desk history.
- `GET /front-desk/notifications` - Fetch front desk notifications.
- `GET /front-desk/profile` - Fetch front desk profile.

### Security Routes

- `POST /security/auth/login` - Security login.
- `POST /security/auth/forgot-password` - Security forgot password.
- `POST /security/auth/verify-otp` - Security verify OTP.
- `PUT /security/auth/reset-password` - Security reset password.
- `POST /security/auth/logout` - Security logout.
- `GET /security/auth/session` - Fetch security session.
- `POST /security/guest/confirm` - Confirm guest.
- `GET /security/guest/{guest_id}` - Fetch guest details.
- `GET /security/guest/{guest_id}/belongings` - Fetch guest belongings.
- `POST /security/guest/confirm/status` - Confirm guest status.
- `POST /security/guest/belongings` - Check-in guest belongings.
- `POST /security/guest/checkout` - Checkout guest.
- `POST /security/guest/notify/frontdesk` - Notify front desk.
- `POST /security/guest/register-guest` - Register guest.
- `POST /security/guest/register-group` - Register group of guests.
- `GET /security/guest/guest-list` - Fetch guest list.
- `POST /security/guest/create-visitor` - Create visitor.
- `GET /security/guest/visit-confirmation/{guest_id}` - Confirm visit.
- `POST /security/guest/send-notification` - Send notification.
- `GET /security/history` - Fetch security history.
- `GET /security/notifications` - Fetch security notifications.
- `GET /security/user-profile` - Fetch security user profile.

### Staff Routes

- `POST /staff/auth/login` - Staff login.
- `POST /staff/auth/logout` - Staff logout.
- `POST /staff/auth/forgot-password` - Staff forgot password.
- `POST /staff/auth/verify-otp` - Staff verify OTP.
- `POST /staff/auth/reset-password` - Staff reset password.
- `GET /staff/dashboard/summary` - Fetch staff dashboard summary.
- `GET /staff/dashboard/recents` - Fetch recent activities.
- `GET /staff/dashboard/todays-guests` - Fetch today's guests.
- `POST /staff/dashboard/guests/check-in` - Check-in guest.
- `POST /staff/dashboard/guests/cancel-visit` - Cancel guest visit.
- `GET /staff/dashboard/guests/pending` - Fetch pending guests.
- `GET /staff/guests/individual` - Fetch individual guests.
- `GET /staff/guests/filter` - Filter guests.
- `DELETE /staff/guests/cancel/{guest_id}` - Cancel guest visit.
- `PUT /staff/guests/reschedule/{guest_id}` - Reschedule guest visit.
- `POST /staff/guests/register` - Register individual guest.
- `POST /staff/guests/register-group` - Register group of guests.
- `GET /staff/guests` - Search visitors.
- `POST /staff/guests/notify` - Notify guests.
- `GET /staff/guests/{guest_id}/qr` - Generate QR code for guest.
- `GET /staff/guests/group` - Fetch group visits.
- `POST /staff/guests/group` - Schedule group visit.
- `PUT /staff/guests/group/{id}/reschedule` - Reschedule group visit.
- `DELETE /staff/guests/group/{id}/cancel` - Cancel group visit.
- `GET /staff/notifications` - Fetch staff notifications.
- `GET /staff/profile` - Fetch staff profile.
- `POST /staff/send-confirmation-email/{visit_id}` - Send confirmation email.

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
