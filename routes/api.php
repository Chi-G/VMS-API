<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\FrontDesk\FrontDeskAuthController;
use App\Http\Controllers\FrontDesk\GuestConfirmationController;
use App\Http\Controllers\FrontDesk\GuestRegistrationController;
use App\Http\Controllers\FrontDesk\HistoryController;
use App\Http\Controllers\FrontDesk\ProfileController;
use App\Http\Controllers\FrontDesk\NotificationController as FrontDeskNotificationController;
use App\Http\Controllers\Security\SecurityAuthController;
use App\Http\Controllers\Security\SecurityGuestController;
use App\Http\Controllers\Security\SecurityGuestRegistrationController;
use App\Http\Controllers\Security\SecurityController;
use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffGuestRegistrationController;
use App\Http\Controllers\Staff\StaffGuestManagementController;
use App\Http\Controllers\Staff\StaffController;
use Illuminate\Support\Facades\Route;


// Admin Routes
Route::prefix('admin')->group(function () {
    // Public authentication routes
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
    Route::post('verify-otp', [AdminAuthController::class, 'verifyOtp']);
    Route::post('forgot-password', [AdminAuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AdminAuthController::class, 'resetPassword']);

    // Profile route (requires authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('profile', [AdminAuthController::class, 'profile']);

        // Notifications
        Route::get('notifications', [AdminNotificationController::class, 'index']);
        Route::post('notifications/mark-as-read', [AdminNotificationController::class, 'markAsRead']);
        Route::delete('notifications/clear', [AdminNotificationController::class, 'clear']);

        // Settings
        Route::get('settings', [SettingsController::class, 'index']);
        Route::post('settings/update', [SettingsController::class, 'update']);
        Route::delete('settings/delete', [SettingsController::class, 'delete']);
        Route::get('roles', [SettingsController::class, 'fetchRoles']);
        Route::post('roles/update', [SettingsController::class, 'updateRoles']);
        Route::get('notifications', [SettingsController::class, 'fetchNotifications']);
        Route::post('notifications/update', [SettingsController::class, 'updateNotifications']);
        Route::get('data-retention', [SettingsController::class, 'fetchDataRetention']);
        Route::post('data-retention/update', [SettingsController::class, 'updateDataRetention']);
        Route::get('security', [SettingsController::class, 'fetchSecuritySettings']);
        Route::post('security/update', [SettingsController::class, 'updateSecuritySettings']);
        Route::put('settings/profile', [SettingsController::class, 'updateProfile']);
        Route::post('settings/admins', [SettingsController::class, 'addAdmin']);
        Route::get('settings/company', [SettingsController::class, 'fetchCompanyDetails']);
        Route::put('settings/company', [SettingsController::class, 'updateCompanyDetails']);

        // Session Management
        Route::post('settings/session-timeout', [SettingsController::class, 'updateSessionTimeout']);
        Route::post('settings/lockout', [SettingsController::class, 'updateLockoutSettings']);
        Route::post('settings/password', [SettingsController::class, 'updatePassword']);

        // Analytics
        Route::get('analytics', [AnalyticsController::class, 'index']);
        Route::get('analytics/export', [AnalyticsController::class, 'export']);

        // Staff Management
        Route::apiResource('staff', AdminStaffController::class);
        Route::post('staff/{id}/deactivate', [AdminStaffController::class, 'deactivate']);

        // Visitors Management
        Route::apiResource('visitors', VisitorController::class);
        Route::post('visitors/{id}/vip', [VisitorController::class, 'markAsVip']);
        Route::post('visitors/{id}/blacklist', [VisitorController::class, 'blacklist']);

        // Visits Management
        Route::apiResource('visits', VisitController::class);
    });
});

// Front Desk Routes
Route::prefix('front-desk')->group(function () {
    Route::post('login', [FrontDeskAuthController::class, 'login']);
    Route::post('forgot-password', [FrontDeskAuthController::class, 'forgotPassword']);
    Route::post('verify-otp', [FrontDeskAuthController::class, 'verifyOtp']);
    Route::post('reset-password', [FrontDeskAuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('guests', [GuestConfirmationController::class, 'index']);
        Route::get('guests/{id}', [GuestConfirmationController::class, 'show']);
        Route::get('guests/filter', [GuestConfirmationController::class, 'filter']);
        Route::get('guests/search', [GuestConfirmationController::class, 'search']);
        Route::post('guests/{id}/check-in', [GuestConfirmationController::class, 'checkIn']);
        Route::post('guests/{id}/check-out', [GuestConfirmationController::class, 'checkOut']);
        Route::get('guests/{id}/print-tag', [GuestConfirmationController::class, 'printTag']);

        Route::post('guests/register', [GuestRegistrationController::class, 'register']);
        Route::post('guests/register-group', [GuestRegistrationController::class, 'registerGroup']);
        Route::get('guests/search', [GuestRegistrationController::class, 'searchVisitors']);

        Route::get('history', [HistoryController::class, 'index'])->name('frontdesk.history.index');
        Route::get('notifications', [FrontDeskNotificationController::class, 'index'])->name('frontdesk.notifications.index');
        Route::get('profile', [ProfileController::class, 'index'])->name('frontdesk.profile.index');
        Route::post('logout', [FrontDeskAuthController::class, 'logout']);
    });
});

// Security Routes
Route::prefix('security/auth')->group(function () {
    Route::post('login', [SecurityAuthController::class, 'login']);
    Route::post('forgot-password', [SecurityAuthController::class, 'forgotPassword']);
    Route::post('verify-otp', [SecurityAuthController::class, 'verifyOtp']);
    Route::put('reset-password', [SecurityAuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [SecurityAuthController::class, 'logout']);
        Route::get('session', [SecurityAuthController::class, 'session']);
    });
});

Route::prefix('security/guest')->middleware('auth:sanctum')->group(function () {
    Route::post('confirm', [SecurityGuestController::class, 'confirm']);
    Route::get('{guest_id}', [SecurityGuestController::class, 'getGuestDetails']);
    Route::get('{guest_id}/belongings', [SecurityGuestController::class, 'getBelongings']);
    Route::post('confirm/status', [SecurityGuestController::class, 'confirmStatus']);
    Route::post('belongings', [SecurityGuestController::class, 'checkInBelongings']);
    Route::post('checkout', [SecurityGuestController::class, 'checkout']);
    Route::post('notify/frontdesk', [SecurityGuestController::class, 'notifyFrontDesk']);
});

Route::prefix('security/guest')->middleware('auth:sanctum')->group(function () {
    Route::post('register-guest', [SecurityGuestRegistrationController::class, 'registerGuest']);
    Route::post('register-group', [SecurityGuestRegistrationController::class, 'registerGroup']);
    Route::get('guest-list', [SecurityGuestRegistrationController::class, 'searchVisitors']);
    Route::post('create-visitor', [SecurityGuestRegistrationController::class, 'createVisitor']);
    Route::get('visit-confirmation/{guest_id}', [SecurityGuestRegistrationController::class, 'visitConfirmation']);
    Route::post('send-notification', [SecurityGuestRegistrationController::class, 'sendNotification']);
});

Route::prefix('security')->middleware('auth:sanctum')->group(function () {
    Route::get('history', [SecurityController::class, 'history']);
    Route::get('notifications', [SecurityController::class, 'notifications']);
    Route::get('user-profile', [SecurityController::class, 'userProfile']);
});

// Staff Routes
Route::prefix('staff/auth')->group(function () {
    Route::post('login', [StaffAuthController::class, 'login']);
    Route::post('logout', [StaffAuthController::class, 'logout']);
    Route::post('forgot-password', [StaffAuthController::class, 'forgotPassword']);
    Route::post('verify-otp', [StaffAuthController::class, 'verifyOtp']);
    Route::post('reset-password', [StaffAuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->prefix('staff/dashboard')->group(function () {
    Route::get('summary', [StaffDashboardController::class, 'summary']);
    Route::get('recents', [StaffDashboardController::class, 'recents']);
    Route::get('todays-guests', [StaffDashboardController::class, 'todaysGuests']);
    Route::post('guests/check-in', [StaffDashboardController::class, 'checkInGuest']);
    Route::post('guests/cancel-visit', [StaffDashboardController::class, 'cancelVisit']);
    Route::get('guests/pending', [StaffDashboardController::class, 'pendingGuests']);
});

Route::middleware('auth:sanctum')->prefix('staff/guests')->group(function () {
    Route::get('individual', [StaffGuestManagementController::class, 'getIndividualGuests']);
    Route::get('filter', [StaffGuestManagementController::class, 'filterGuests']);
    Route::delete('cancel/{guest_id}', [StaffGuestManagementController::class, 'cancelVisit']);
    Route::put('reschedule/{guest_id}', [StaffGuestManagementController::class, 'rescheduleVisit']);

    Route::post('register', [StaffGuestRegistrationController::class, 'registerIndividual']);
    Route::post('register-group', [StaffGuestRegistrationController::class, 'registerGroup']);
    Route::get('/', [StaffGuestRegistrationController::class, 'searchVisitors']);
    Route::post('notify', [StaffGuestRegistrationController::class, 'notify']);
    Route::get('{guest_id}/qr', [StaffGuestRegistrationController::class, 'generateQrCode']);

    Route::get('group', [StaffGuestManagementController::class, 'getGroupVisits']);
    Route::post('group', [StaffGuestManagementController::class, 'scheduleGroupVisit']);
    Route::put('group/{id}/reschedule', [StaffGuestManagementController::class, 'rescheduleGroupVisit']);
    Route::delete('group/{id}/cancel', [StaffGuestManagementController::class, 'cancelGroupVisit']);
});

Route::middleware('auth:sanctum')->prefix('staff')->group(function () {
    Route::get('notifications', [StaffController::class, 'notifications']);
    Route::get('profile', [StaffController::class, 'profile']);
    Route::post('send-confirmation-email/{visit_id}', [StaffController::class, 'sendConfirmationEmail']);
});
