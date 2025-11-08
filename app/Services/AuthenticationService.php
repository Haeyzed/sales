<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * AuthenticationService
 *
 * Service class for handling authentication-related business logic.
 */
class AuthenticationService
{
    /**
     * Attempt to authenticate a user by email or name.
     *
     * @param string $identifier The email or name identifier
     * @param string $password The password
     * @param bool $remember Whether to remember the user
     * @return User
     * @throws ValidationException
     */
    public function attemptLogin(string $identifier, string $password, bool $remember = false): User
    {
        $fieldType = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $user = User::where($fieldType, $identifier)
            ->where('is_active', true)
            ->where('is_deleted', false)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'name' => __('The provided credentials are incorrect.'),
            ]);
        }

        return $user;
    }

    /**
     * Register a new user.
     *
     * @param array<string, mixed> $data The registration data
     * @return User
     */
    public function register(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'role_id' => $data['role_id'] ?? null,
            'biller_id' => $data['biller_id'] ?? null,
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'is_active' => $data['is_active'] ?? false,
            'is_deleted' => false,
            'password' => Hash::make($data['password']),
        ];

        $user = User::create($userData);

        // If role is customer (role_id = 5), create a customer record
        if (isset($data['role_id']) && $data['role_id'] == 5) {
            $this->createCustomerFromUser($user, $data);
        }

        return $user;
    }

    /**
     * Create a customer record from user registration.
     *
     * @param User $user The user instance
     * @param array<string, mixed> $data The registration data
     * @return Customer
     */
    private function createCustomerFromUser(User $user, array $data): Customer
    {
        $customerData = [
            'name' => $data['customer_name'] ?? $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone,
            'company_name' => $user->company_name,
            'user_id' => $user->id,
            'customer_group_id' => $data['customer_group_id'] ?? null,
            'is_active' => true,
        ];

        return Customer::create($customerData);
    }

    /**
     * Check if user account is active.
     *
     * @param User $user The user instance
     * @return bool
     */
    public function isUserActive(User $user): bool
    {
        return $user->is_active && !$user->is_deleted;
    }

    /**
     * Get the login field type (email or name).
     *
     * @param string $identifier The login identifier
     * @return string
     */
    public function getLoginFieldType(string $identifier): string
    {
        return filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    }
}

