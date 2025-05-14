<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;
use App\Models\SubscriptionService;
use App\Http\Controllers\BaseController;

class UserSubscriptionController extends BaseController
{
    public function index()
    {
        $user = auth()->user();
        $userSubscription = $user->subscription('default');
        $userSubscriptionPackage = $user->subscriptionPackage;

        // dd($subscriptionPackage);

        $subscriptionPackages = SubscriptionPackage::with('subscriptionServices')->get();

        $subscriptionServices = SubscriptionService::all();

        return view('user-subscription-packages.index', compact('user', 'userSubscription', 'userSubscriptionPackage', 'subscriptionPackages', 'subscriptionServices'));
    }

    public function show(SubscriptionPackage $subscriptionPackage)
    {
        $user = auth()->user();
        $intent = $user->createSetupIntent();
        // $userSubscription = $user->subscription('default');
        // $userSubscriptionPackage = $user->subscriptionPackage;

        // return view('user-subscription-packages.show', compact('user', 'intent', 'userSubscription', 'userSubscriptionPackage', 'subscriptionPackage'));
        return view('user-subscription-packages.subscribe', compact('intent', 'subscriptionPackage'));
    }

    public function subscribe(Request $request)
    {
        // $plan = Plan::find($request->plan);

        // $subscription = $request->user()->newSubscription($request->plan, $plan->stripe_plan)
        //     ->create($request->token);

        // return view("subscription_success");


        $user = auth()->user();
        // $userSubscription = $user->subscription('default');
        // $intent = $user->createSetupIntent();
        // $userSubscriptionPackage = $user->subscriptionPackage;

        $subscriptionPackage = SubscriptionPackage::find($request->subscriptionPackage);

        $user->newSubscription($request->subscriptionPackage, $subscriptionPackage->stripe_product)->create($request->token);
        // $user->newSubscription('default', $subscriptionPackage->stripe_plan_id)->create($request->token);

        $user->subscriptionPackage()->associate($subscriptionPackage)->save();

        return view('user-subscription-packages.subscription_success')->with('success', 'You have successfully subscribed to ' . $subscriptionPackage->name . ' package.');
    }
}
