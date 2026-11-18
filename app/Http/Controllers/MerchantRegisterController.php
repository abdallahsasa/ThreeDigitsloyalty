<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\Subscription;
use App\Models\User;
use App\Services\FunnelTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class MerchantRegisterController extends Controller
{
    public function show(Request $request, FunnelTracker $tracker)
    {
        if (Auth::check() && Auth::user()->shop_id) {
            return redirect("/shop/" . Auth::user()->shop_id);
        }

        $plans = Plan::where('status', 'active')->orderBy('price', 'asc')->get();
        $selectedPlanSlug = Str::slug((string) $request->query('plan', 'starter'));

        $tracker->track('registration_started', [
            'plan' => $selectedPlanSlug ?: 'starter',
        ]);

        return view('auth.register-shop', compact('plans', 'selectedPlanSlug'));
    }

    public function store(Request $request, FunnelTracker $tracker)
    {
        $validated = $request->validate([
            'shop_name' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'plan' => ['nullable', 'string', 'max:60'],
        ]);

        $planSlug = Str::slug((string) ($validated['plan'] ?? 'starter'));
        $plans = Plan::where('status', 'active')->orderBy('price', 'asc')->get();
        $selectedPlan = $plans->first(fn (Plan $p) => Str::slug($p->name) === $planSlug) ?? $plans->first();

        [$shop, $user] = DB::transaction(function () use ($validated, $selectedPlan) {
            $shop = Shop::create([
                'name' => trim($validated['shop_name']),
                'plan_id' => $selectedPlan?->id,
                'status' => 'active',
                'default_language' => app()->getLocale() === 'ar' ? 'ar' : 'en',
            ]);

            if ($selectedPlan) {
                Subscription::create([
                    'shop_id' => $shop->id,
                    'plan_id' => $selectedPlan->id,
                    'starts_at' => now()->toDateString(),
                    'ends_at' => config('loyalty.launch_offer_ends_at', '2026-12-31'),
                    'status' => 'active',
                ]);
            }

            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => $validated['password'],
                'shop_id' => $shop->id,
            ]);

            $role = Role::firstOrCreate(['name' => 'Shop Owner', 'guard_name' => 'web']);
            $user->assignRole($role);

            return [$shop, $user];
        });

        Auth::login($user);
        $request->session()->regenerate();

        $tracker->track('registration_completed', [
            'shop_id' => $shop->id,
            'plan' => $selectedPlan?->name ?? 'none',
        ]);

        return redirect("/shop/{$shop->id}/getting-started");
    }
}
