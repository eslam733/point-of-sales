<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\FeatureItem;
use App\Models\Item;
use App\Models\Notification;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\FeatureItemPolicy;
use App\Policies\ItemPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Item::class => ItemPolicy::class,
        FeatureItem::class => FeatureItemPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
       
    }
}
