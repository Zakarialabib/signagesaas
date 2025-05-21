<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Tenant\Models\Plan;
use App\Tenant\Models\Subscription;
use App\Tenant\Models\Tenant;
use App\Tenant\Models\UsageQuota;
use App\Tenant\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;
use Throwable;

#[Layout('layouts.auth')]
final class Register extends Component
{
    #[Validate('required|string|min:3')]
    public string $company = '';

    #[Validate('required|string|min:3')]
    public string $name = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|min:8')]
    public string $password = '';

    #[Validate('required|string|same:password')]
    public string $password_confirmation = '';

    // Selected plan and billing cycle
    public ?string $planId = null;
    public string $billingCycle = 'monthly';
    public ?Plan $selectedPlan = null;

    public function mount(?string $plan = null, ?string $billing_cycle = null): void
    {
        // Set plan ID from URL parameter if it exists
        if ($plan) {
            $this->planId = $plan;
            // Load the plan details
            $this->selectedPlan = Plan::find($plan);
        }

        // Set billing cycle from URL parameter if it exists
        if ($billing_cycle && in_array($billing_cycle, ['monthly', 'yearly'])) {
            $this->billingCycle = $billing_cycle;
        }
    }

    public function register(): void
    {
        $validated = $this->validate();

        DB::beginTransaction();

        try {
            // Generate a unique tenant ID using slug and random string
            $tenantId = str($this->company)
                ->slug()
                ->append('-'.Str::random(6))
                ->toString();

            // Create the tenant
            $tenant = Tenant::create([
                'id'       => $tenantId,
                'name'     => $this->company,
                'email'    => $this->email,
                'plan'     => $this->planId ? null : 'free', // Only set default plan if no specific plan was selected
                'settings' => [
                    'timezone' => config('app.timezone', 'UTC'),
                    'language' => app()->getLocale(),
                ],
                'data' => [
                    'settings' => [
                        'timezone' => config('app.timezone', 'UTC'),
                        'language' => app()->getLocale(),
                    ],
                ],
            ]);

            // Create the primary domain for the tenant
            $tenant->domains()->create([
                'domain'    => "{$tenantId}.".config('app.domain'),
                'tenant_id' => $tenant->id,
            ]);

            // Initialize tenancy context for this tenant
            Tenancy::initialize($tenant);

            // Create the admin user for the tenant
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => $validated['password'], // User model casts 'password' as 'hashed'
                'role'      => 'admin',
            ]);

            // Set up subscription with selected plan if provided, otherwise use free plan
            $planToUse = $this->selectedPlan ?? Plan::where('slug', 'free')->first();

            if ($planToUse) {
                // Create subscription
                $subscription = Subscription::create([
                    'tenant_id'                => $tenant->id,
                    'plan_id'                  => $planToUse->id,
                    'status'                   => 'active',
                    'billing_cycle'            => $this->billingCycle,
                    'current_period_starts_at' => Carbon::now(),
                    'current_period_ends_at'   => $this->billingCycle === 'monthly'
                        ? Carbon::now()->addMonth()
                        : Carbon::now()->addYear(),
                ]);

                // Create usage quota
                UsageQuota::create([
                    'tenant_id'         => $tenant->id,
                    'subscription_id'   => $subscription->id,
                    'devices_count'     => 0,
                    'screens_count'     => 0,
                    'users_count'       => 1, // At least the admin user
                    'storage_used_mb'   => 0,
                    'bandwidth_used_mb' => 0,
                ]);
            }

            // Set up roles and permissions for the tenant
            try {
                // Run the tenant:setup-roles command asynchronously
                Artisan::queue('tenant:setup-roles', ['tenant' => $tenant->id]);
            } catch (Exception $e) {
                // Log the error but don't fail registration if roles setup fails
                report($e);
            }

            Tenancy::end();
            DB::commit();

            // Get the tenant's primary domain for redirection
            $domain = $tenant->domains()->first()?->domain;

            if ( ! $domain) {
                throw ValidationException::withMessages([
                    'company' => 'No domain configured for your tenant.',
                ]);
            }

            // Redirect to the new tenant's dashboard
            $this->redirect("https://{$domain}/dashboard", navigate: true);
        } catch (ValidationException $e) {
            DB::rollBack();
            $this->setErrorBag($e->errors());
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            $this->addError('registration', 'Failed to create account. Please try again.');
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.register');
    }
}
