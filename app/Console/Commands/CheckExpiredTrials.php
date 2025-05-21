<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tenant\Models\Plan;
use App\Tenant\Models\Subscription;

class CheckExpiredTrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-trials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired trial subscriptions and update their status.';

    /** Execute the console command. */
    public function handle()
    {
        // Get all trialing subscriptions where trial has ended
        $expiredSubscriptions = Subscription::where('status', 'trialing')
            ->where('trial_ends_at', '<=', now())
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('No expired trial subscriptions found.');

            return;
        }

        $this->info("Found {$expiredSubscriptions->count()} expired trial subscription(s).");

        $defaultPlan = Plan::where('slug', 'free')->first();

        if ( ! $defaultPlan) {
            $this->error('Default "Free" plan not found. Cannot proceed with downgrading.');

            return;
        }

        foreach ($expiredSubscriptions as $subscription) {
            // Option 1: Downgrade to Free plan
            $subscription->update([
                'plan_id'       => $defaultPlan->id,
                'status'        => 'active',
                'trial_ends_at' => null,
                'billing_cycle' => 'monthly',
            ]);

            // Optional: Log or dispatch event for email notification
            $this->info("Downgraded subscription ID: {$subscription->id} to Free plan.");
        }

        $this->info('Expired trial subscriptions processed successfully.');
    }
}
