<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Biller;
use App\Models\CustomerGroup;
use App\Models\Roles;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\AuthenticationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

/**
 * RegisteredUserController
 *
 * Handles user registration.
 */
class RegisteredUserController extends Controller
{
    /**
     * The authentication service instance.
     *
     * @var AuthenticationService
     */
    private AuthenticationService $authenticationService;

    /**
     * Create a new controller instance.
     *
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Show the registration page.
     *
     * @return Response
     */
    public function create(): Response
    {
        $roles = Roles::where('is_active', true)->get();
        $customerGroups = CustomerGroup::where('is_active', true)->get();
        $billers = Biller::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $numberOfUserAccounts = User::where('is_active', true)->count();

        return Inertia::render('auth/register', [
            'roles' => $roles,
            'customerGroups' => $customerGroups,
            'billers' => $billers,
            'warehouses' => $warehouses,
            'numberOfUserAccounts' => $numberOfUserAccounts,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('is_deleted', false);
                }),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
            'biller_id' => ['nullable', 'integer', 'exists:billers,id'],
            'warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'customer_group_id' => ['nullable', 'integer', 'exists:customer_groups,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['is_active'] = false; // New registrations are inactive by default

        $user = $this->authenticationService->register($validated);

        event(new Registered($user));

        // Note: In Sales Pro, new users are not auto-logged in
        // They need admin approval first (is_active = false)
        // Uncomment below if you want to auto-login after registration
        // Auth::login($user);
        // $request->session()->regenerate();

        return redirect()->route('login')->with('status', __('Registration successful! Please wait for admin approval.'));
    }
}
