# Admin Dashboard Guide

## Overview
The admin dashboard allows you to manage all fonts, approve/reject uploads, and delete fonts from the Namma Fontsu platform.

## Accessing the Admin Dashboard
Navigate to: `http://localhost/Fontsu/admin.html`

## Features

### 1. Statistics Overview
- **Total Fonts**: Total number of fonts in the database
- **Pending Approval**: Fonts waiting for review
- **Approved**: Fonts live on the marketplace
- **Rejected**: Fonts that were rejected

### 2. Filter Tabs
- **All Fonts**: View all fonts regardless of status
- **Pending**: View only fonts awaiting approval
- **Approved**: View only approved fonts
- **Rejected**: View only rejected fonts

### 3. Font Management Actions

#### Approve Font
- Click the green **✓** button to approve a pending font
- Approved fonts will be visible to users on the marketplace
- Font status changes from "Pending" to "Approved"

#### Reject Font
- Click the red **✕** button to reject a pending font
- Rejected fonts will not appear on the marketplace
- Font status changes from "Pending" to "Rejected"

#### Unapprove Font
- Click the yellow **⊘** button on an approved font to unapprove it
- Font status changes from "Approved" to "Rejected"
- Font will be hidden from the marketplace

#### Delete Font
- Click the black **🗑** button to permanently delete a font
- **Warning**: This action cannot be undone
- Deletes both database record and uploaded file from storage

## Backend Files

### admin_get_fonts.php
- Fetches all fonts from the database (all statuses)
- Used by admin dashboard to populate the fonts table
- Returns fonts sorted by newest first

### admin_update_font.php
- Updates the status of a font (pending → approved/rejected)
- Validates status values
- Updates the `updated_at` timestamp

### admin_delete_font.php
- Permanently deletes a font from the database
- Attempts to delete the font file from Supabase Storage
- Returns success/error message

## Security Notes

⚠️ **Important**: The admin dashboard currently has no authentication. In production, you should:

1. Add admin login functionality
2. Use session management to verify admin access
3. Add CSRF protection
4. Implement rate limiting
5. Add audit logging for all admin actions

## Recommended Security Implementation

Add this at the top of each admin PHP file:

```php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
```

And create an admin login page to set `$_SESSION['admin_logged_in'] = true` after verifying credentials.

## Usage Workflow

1. Designer uploads font via `designers.html`
2. Font is created with status "Pending"
3. Admin reviews font in admin dashboard
4. Admin approves or rejects the font
5. Approved fonts appear on `fonts.html` for users to browse
6. Rejected fonts remain hidden

## Troubleshooting

### Fonts not loading
- Check browser console for errors
- Verify `admin_get_fonts.php` is accessible
- Check Supabase connection in `config.php`

### Cannot approve/reject fonts
- Check `admin_update_font.php` permissions
- Verify service_role key is set in `config.php`
- Check browser console for API errors

### Cannot delete fonts
- Verify `admin_delete_font.php` has proper permissions
- Check if storage bucket allows deletion
- Review Supabase storage policies

## Future Enhancements

- [ ] Add admin authentication
- [ ] Add bulk actions (approve/reject multiple fonts)
- [ ] Add search and advanced filtering
- [ ] Add font preview in admin panel
- [ ] Add edit functionality for font details
- [ ] Add activity logs for audit trail
- [ ] Add email notifications to designers on status change
- [ ] Add download statistics per font
