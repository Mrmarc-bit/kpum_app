<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * UserPolicy - RBAC Implementation
 * 
 * CRITICAL SECURITY RULES:
 * 1. Users CANNOT modify their own role
 * 2. Users CANNOT modify other users' roles
 * 3. Only Admin can manage users
 * 4. Panitia cannot be promoted to Admin (except by Admin)
 * 5. KPPS cannot access user management
 * 
 * Authorization Matrix:
 * ┌──────────────┬────────┬──────────┬──────┐
 * │ Action       │ Admin  │ Panitia  │ KPPS │
 * ├──────────────┼────────┼──────────┼──────┤
 * │ viewAny      │   ✅   │    ❌    │  ❌  │
 * │ view         │   ✅   │    ⚠️    │  ❌  │
 * │ create       │   ✅   │    ❌    │  ❌  │
 * │ update       │   ✅   │    ⚠️    │  ❌  │
 * │ delete       │   ✅   │    ❌    │  ❌  │
 * │ changeRole   │   ✅   │    ❌    │  ❌  │
 * │ verify       │   ✅   │    ❌    │  ❌  │
 * └──────────────┴────────┴──────────┴──────┘
 * 
 * ⚠️  Panitia can only view/update their OWN profile, NOT others
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view the user list
     */
    public function viewAny(User $user): bool
    {
        // Only Admin or Super Admin can view user list (user management)
        return in_array($user->role, ['admin', 'super_admin']);
    }

    /**
     * Determine if user can view a specific user
     */
    public function view(User $user, User $model): bool
    {
        // Admin or Super Admin can view any user
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        // Panitia and KPPS can only view their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine if user can create new users
     */
    public function create(User $user): bool
    {
        // Only Admin or Super Admin can create new users (Panitia/KPPS accounts)
        return in_array($user->role, ['admin', 'super_admin']);
    }

    /**
     * Determine if user can update user records
     * 
     * CRITICAL: Prevent privilege escalation
     */
    public function update(User $user, User $model): bool
    {
        // Admin or Super Admin can update any user
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        // Panitia/KPPS can ONLY update their own profile
        // AND they CANNOT change their role
        if ($user->id === $model->id) {
            // This is handled in controller: $guarded = ['role']
            return true;
        }

        return false;
    }

    /**
     * Determine if user can delete users
     */
    public function delete(User $user, User $model): bool
    {
        // Only Admin or Super Admin can delete users
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return false;
        }

        // Admin CANNOT delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine if user can verify email
     */
    public function verify(User $user, User $model): bool
    {
        // Only Admin or Super Admin can manually verify emails
        return in_array($user->role, ['admin', 'super_admin']) && $user->id !== $model->id;
    }

    /**
     * Determine if user can change roles
     * 
     * ULTRA-CRITICAL: This prevents horizontal privilege escalation
     */
    public function changeRole(User $user, User $model): bool
    {
        // ONLY Admin or Super Admin can change roles
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return false;
        }

        // Admin cannot change their own role
        // This prevents accidental self-demotion
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine if user can enable 2FA for others
     */
    public function manage2FA(User $user, User $model): bool
    {
        // Admin or Super Admin can manage any user's 2FA
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        // Users can only manage their own 2FA
        return $user->id === $model->id;
    }

    /**
     * Determine if user can reset password for others
     */
    public function resetPassword(User $user, User $model): bool
    {
        // Only Admin or Super Admin can reset passwords for other users
        if (in_array($user->role, ['admin', 'super_admin']) && $user->id !== $model->id) {
            return true;
        }

        // Users can reset their own password (via forgot password flow)
        return $user->id === $model->id;
    }
}
