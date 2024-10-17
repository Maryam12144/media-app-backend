<?php

namespace Modules\User\Entities\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Role;
use Modules\Subscription\Entities\Invoice;
use Modules\Subscription\Entities\Plan;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Laravel\Cashier\Exceptions\PaymentFailure;
use Laravel\Cashier\Subscription;

trait HasSubscriptions
{
    /**
     * Get user's payment invoices
     *
     * @return HasMany
     */
    public function paymentInvoices()
    {
        return $this->hasMany(Invoice::class,
            'stripe_customer_id', 'stripe_id');
    }

    /**
     * Purchase a new subscription for user with
     * the given product identifier and interval
     *
     * @param string $productIdentifier
     * @param string $interval
     * @param null|string $paymentMethod
     * @param null $promo
     * @return Subscription|null
     * @throws PaymentActionRequired
     * @throws PaymentFailure
     */
    public function purchaseSubscription(string $productIdentifier, string $interval,
                                         $paymentMethod = null, $promo = null)
    {
        // fetch the plan by product and interval
        $plan = Plan::fetchPriceId(
            $productIdentifier, $interval
        );

        if (!$plan) return null;

        // update user's default payment method if one passed
        if ($paymentMethod) $this->updateDefaultPaymentMethod($paymentMethod);

        /*
         * Activate the subscription and charge the user
         * then clear the trial period for user
         */
        $subscription = $this->newSubscription('default', $plan)
            ->withPromotionCode($promo)
            ->create();

        $this->clearTrial();

        return $subscription;
    }

    /**
     * Get the plans that user has subscribed to
     *
     * @return BelongsToMany
     */
    public function plans()
    {
        return $this->belongsToMany(
            Plan::class,
            'subscriptions',
            'user_id',
            'stripe_plan',
            'id',
            'stripe_price_id'
        )
            ->where(function ($query) {
                return $query
                    ->where(DB::raw('subscriptions.ends_at'))
                    ->orWhere(DB::raw('subscriptions.ends_at'), '>', now());
            })
            ->withoutGlobalScope('active');
    }

    /**
     * Activate 30 days trial for user
     *
     * @return bool
     */
    public function activateTrial()
    {
        return $this->update([
            'trial_ends_at' => now()->addMonth()
        ]);
    }

    /**
     * Clear trial for user
     *
     * @return bool
     */
    public function clearTrial()
    {
        return $this->update([
            'trial_ends_at' => null
        ]);
    }

    /**
     * Apply trial subscription status to users
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeTrial($query)
    {
        return $query->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now());
    }
}