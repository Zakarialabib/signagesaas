<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Tenant\Models\Tenant;
use Throwable;

#[Layout('layouts.auth')]
final class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /** Setup the component */
    public function mount()
    {
        // Check for impersonation
        $impersonationToken = request()->cookie('impersonation_token');

        if ($impersonationToken) {
            $this->redirect(route('impersonation.check'));

            return;
        }

        // Check if user is already logged in
        if (Auth::check()) {
            $this->redirect(route('dashboard'));
        }
    }

    public function login(): void
    {
        $credentials = $this->validate();

        try {
            // Attempt authentication
            if (Auth::attempt([
                'email'    => $credentials['email'],
                'password' => $credentials['password'],
            ], $this->remember)) {
                // Regenerate session to prevent fixation
                Session::regenerate();

                // Retrieve the authenticated user
                $user = Auth::user();

                // Ensure user is associated with a tenant
                $tenant = $user->tenant ?? null;

                if ( ! $tenant instanceof Tenant) {
                    Auth::logout();
                    $this->addError('email', 'No tenant associated with this account.');

                    return;
                }

                // Optionally, check if tenant is active, on trial, or has a valid subscription
                if ( ! $tenant->hasActiveSubscription() && ! $tenant->isOnTrial()) {
                    Auth::logout();
                    $this->addError('email', 'Your subscription is inactive or expired.');

                    return;
                }

                // Get the current tenant's primary domain
                $domain = $tenant->domains()->first()?->domain;

                if ( ! $domain) {
                    Auth::logout();
                    $this->addError('email', 'No domain configured for your tenant.');

                    return;
                }

                // Redirect to the tenant's dashboard
                $this->redirect("https://{$domain}/dashboard", navigate: true);

                return;
            }

            // Authentication failed
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->errors());
        } catch (Throwable $e) {
            report($e);
            $this->addError('email', 'An error occurred while trying to log in. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
