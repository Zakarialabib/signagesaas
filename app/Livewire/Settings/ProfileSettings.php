<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Services\OnboardingProgressService;
use App\Tenant\Models\OnboardingProgress;
use App\Tenant\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
final class ProfileSettings extends Component
{
    use WithFileUploads;

    // User profile information
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    // Password fields
    #[Rule('nullable|string|min:8|confirmed')]
    public ?string $password = null;

    #[Rule('nullable|string')]
    public ?string $password_confirmation = null;

    // Other profile fields
    #[Rule('nullable|string|max:255')]
    public ?string $job_title = null;

    #[Rule('nullable|timezone')]
    public ?string $timezone = null;

    #[Rule('nullable|string|in:en,ar')]
    public ?string $language = null;

    // Profile photo
    #[Rule('nullable|image|max:1024')]
    public $photo = null;

    // State properties
    public bool $showSuccessAlert = false;

    public function mount(): void
    {
        $user = Auth::user();

        // Load user data
        $this->name = $user->name;
        $this->email = $user->email;
        $this->job_title = $user->job_title ?? null;
        $this->timezone = $user->timezone ?? config('app.timezone');
        $this->language = $user->language ?? app()->getLocale();
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,'.Auth::id(),
            'job_title' => 'nullable|string|max:255',
            'timezone'  => 'nullable|timezone',
            'language'  => 'nullable|string|in:en,ar',
            'photo'     => 'nullable|image|max:1024',
        ]);

        $user = Auth::user();

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->job_title = $validated['job_title'];
        $user->timezone = $validated['timezone'];
        $user->language = $validated['language'];

        // Handle profile photo upload
        if ($this->photo) {
            // In a real app, we'd store this in S3/R2 with the proper tenant isolation
            // For demo purposes, storing in public disk
            $path = $this->photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        // Mark onboarding step as complete
        $onboardingProgress = OnboardingProgress::firstOrCreate(['tenant_id' => $user->tenant_id]);
        if (!$onboardingProgress->profile_completed) {
            app(OnboardingProgressService::class)->completeStep($onboardingProgress, 'profile_completed');
        }

        // Update session locale if language changed
        if ($validated['language'] !== app()->getLocale()) {
            session()->put('locale', $validated['language']);
            app()->setLocale($validated['language']);
        }

        $this->showSuccessAlert = true;
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        $this->password = null;
        $this->password_confirmation = null;

        $this->showSuccessAlert = true;
    }

    public function dismissAlert(): void
    {
        $this->showSuccessAlert = false;
    }

    public function render()
    {
        return view('livewire.settings.profile-settings');
    }
}
