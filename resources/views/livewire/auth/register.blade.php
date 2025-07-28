<?php

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    // User details
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Organization details
    public string $organization_name = '';
    public string $organization_domain = '';

    // Step tracking
    public int $currentStep = 1;
    public int $totalSteps = 2;

    /**
     * Navigate to the next step
     */
    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validateUserDetails();
            $this->currentStep++;
        }
    }

    /**
     * Navigate to the previous step
     */
    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Validate user details (step 1).
     */
    protected function validateUserDetails(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);
    }

    /**
     * Validate organization details (step 2).
     */
    protected function validateOrganizationDetails(): array
    {
        return $this->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_domain' => ['required', 'string', 'max:255', 'unique:domains,domain', 'regex:/^[a-z0-9-]+$/'],
        ], [
            'organization_name.required' => 'Please enter your organization name.',
            'organization_name.max' => 'Organization name must be less than 255 characters.',
            'organization_domain.required' => 'Please enter a domain for your organization.',
            'organization_domain.unique' => 'This domain is already taken. Please choose another.',
            'organization_domain.regex' => 'Domain can only contain lowercase letters, numbers, and hyphens.',
            'organization_domain.max' => 'Domain must be less than 255 characters.',
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Validate all data.
        $this->validateUserDetails();
        $organizationData = $this->validateOrganizationDetails();

        // Create tenant first.
        $tenant = Tenant::create([
            'name' => $this->organization_name,
            'domain' => $this->organization_domain,
        ]);
        $tenant->createDomain($this->organization_domain . '.' . config('app.url'));
        $userValues = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];
        tenancy()->initialize($tenant);
        // Create a record in the tenant.
        $user = User::create($userValues);
        tenancy()->end();
        // Create a central record.
        $user = User::create($userValues);

        event(new Registered($user));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Create an account')"
        :description="$currentStep === 1 ? __('Enter your personal details') : __('Set up your organization')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <!-- Progress indicator -->
    <div class="flex items-center justify-center gap-2">
        <div class="flex items-center gap-2">
            <div @class([
                'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium',
                'bg-violet-600 text-white' => $currentStep >= 1,
                'bg-zinc-200 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400' => $currentStep < 1,
            ])>
                1
            </div>
            <span @class([
                'text-sm',
                'text-zinc-900 dark:text-white font-medium' => $currentStep === 1,
                'text-zinc-500 dark:text-zinc-400' => $currentStep !== 1,
            ])>User Details</span>
        </div>

        <div class="w-12 h-0.5 bg-zinc-200 dark:bg-zinc-700">
            <div class="h-full bg-violet-600 transition-all duration-300" :style="`width: ${$currentStep > 1 ? '100%' : '0%'}`"></div>
        </div>

        <div class="flex items-center gap-2">
            <div @class([
                'w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium',
                'bg-violet-600 text-white' => $currentStep >= 2,
                'bg-zinc-200 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400' => $currentStep < 2,
            ])>
                2
            </div>
            <span @class([
                'text-sm',
                'text-zinc-900 dark:text-white font-medium' => $currentStep === 2,
                'text-zinc-500 dark:text-zinc-400' => $currentStep !== 2,
            ])>Organization</span>
        </div>
    </div>

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Step 1: User Details -->
        <div x-show="$wire.currentStep === 1" x-transition class="flex flex-col gap-6">
            <!-- Name -->
            <flux:input
                wire:model="name"
                label="Name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Name"
            />

            <!-- Email Address -->
            <flux:input
                wire:model="email"
                :label="__('Email address')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="button" wire:click="nextStep" variant="primary" color="violet" class="w-full h-12">
                    {{ __('Continue') }}
                </flux:button>
            </div>
        </div>

        <!-- Step 2: Organization Details -->
        <div x-show="$wire.currentStep === 2" x-transition class="flex flex-col gap-6">
            <!-- Organization Name -->
            <flux:input
                wire:model="organization_name"
                wire:change="$set('organization_domain', $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').substring(0, 30))"
                label="Organization name"
                type="text"
                required
                autofocus
                placeholder="Acme Inc."
                :invalid="$errors->has('organization_name')"
            />

            <!-- Organization Domain -->
            <flux:field>
                <flux:label>Organization domain</flux:label>
                <flux:description>This will be your unique subdomain (e.g., acme.zapix.com)</flux:description>
                <flux:input.group>
                    <flux:input
                        wire:model="organization_domain"
                        type="text"
                        required
                        placeholder="acme"
                        :invalid="$errors->has('organization_domain')"
                    />
                    <flux:input.group.suffix>.zapix.com</flux:input.group.suffix>
                </flux:input.group>
                <flux:error name="organization_domain" />
            </flux:field>

            <div class="flex items-center gap-3">
                <flux:button type="button" wire:click="previousStep" variant="ghost" class="flex-1 h-12">
                    {{ __('Back') }}
                </flux:button>
                <flux:button type="submit" variant="primary" color="violet" class="flex-1 h-12">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
