<?php
$components = [
    'nav' => <<<'HTML'
<nav class="absolute w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="font-heading font-extrabold text-2xl tracking-tight text-indigo-600 flex items-center gap-2">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    ThreeDigits
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse">
                <a href="#features" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">{{ __('landing.nav_features') }}</a>
                <a href="{{ url('/shop/login') }}" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">{{ __('landing.nav_login') }}</a>
                
                <div class="flex items-center gap-4 border-s rtl:border-s-0 rtl:border-r border-gray-200 ps-4 rtl:ps-0 rtl:pr-4">
                    <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="text-gray-500 hover:text-indigo-600 font-bold transition-colors">
                        {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
                    </a>
                    <a href="{{ url('/admin') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-full font-medium transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        {{ __('landing.nav_admin') }}
                    </a>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="mx-4 text-gray-500 font-bold">
                    {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
                </a>
            </div>
        </div>
    </div>
</nav>
HTML,

    'hero' => <<<'HTML'
<div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 bg-grid">
    <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/50 to-white pointer-events-none"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold mb-8 animate-fade-in-up">
            <span class="flex h-2 w-2 relative">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            Modern SaaS Loyalty
        </div>
        
        <h1 class="font-heading text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6 max-w-4xl mx-auto">
            {{ __('landing.hero_title') }}
        </h1>
        
        <p class="text-xl md:text-2xl text-gray-500 mb-10 max-w-3xl mx-auto leading-relaxed font-light">
            {{ __('landing.hero_subtitle') }}
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-20">
            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold px-8 py-4 rounded-full transition-all shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:shadow-[0_0_30px_rgba(79,70,229,0.6)] transform hover:-translate-y-1">
                {{ __('landing.hero_cta_1') }}
            </a>
            <a href="#features" class="bg-white border border-gray-200 hover:border-gray-300 text-gray-700 text-lg font-semibold px-8 py-4 rounded-full transition-all shadow-sm hover:shadow-md transform hover:-translate-y-1">
                {{ __('landing.hero_cta_2') }}
            </a>
        </div>

        <!-- Dashboard & Phone Mockup -->
        <div class="relative max-w-5xl mx-auto mt-16">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-20 float-animation"></div>
            
            <div class="relative flex justify-center items-end gap-6 float-animation">
                <!-- Dashboard Mockup -->
                <div class="hidden md:block w-3/4 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-10">
                    <div class="bg-gray-50 border-b border-gray-100 px-4 py-3 flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Dashboard&background=random&size=800" alt="Dashboard" class="w-full h-[400px] object-cover opacity-50 grayscale mix-blend-multiply">
                </div>

                <!-- Phone Mockup -->
                <div class="w-[280px] bg-gray-900 rounded-[3rem] p-3 shadow-2xl border-4 border-gray-800 relative z-20 md:-ml-20">
                    <div class="absolute top-0 inset-x-0 h-6 bg-gray-900 rounded-b-3xl w-40 mx-auto z-30"></div>
                    <div class="bg-white h-[550px] rounded-[2.25rem] overflow-hidden relative">
                        <div class="p-6 bg-gradient-to-b from-indigo-600 to-purple-700 h-full text-white flex flex-col items-center pt-12">
                            <div class="w-16 h-16 bg-white/20 rounded-full mb-4 flex items-center justify-center font-bold text-2xl border border-white/30 backdrop-blur-sm">C</div>
                            <h3 class="font-heading font-bold text-xl mb-6">Coffee House</h3>
                            
                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 w-full text-center shadow-lg">
                                <div class="text-3xl tracking-widest text-yellow-400 mb-2">⭐⭐⭐<span class="text-gray-400 opacity-50">☆</span></div>
                                <p class="text-sm opacity-80 mb-4">3 / 4 Purchases</p>
                                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-200 h-full w-3/4"></div>
                                </div>
                                <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-green-300">Next Reward: Free Coffee</p>
                            </div>
                            
                            <div class="mt-auto bg-white p-2 rounded-xl">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=example" class="w-32 h-32 rounded-lg" alt="QR">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
HTML,

    'trusted-by' => <<<'HTML'
<div class="py-16 bg-white border-y border-gray-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-8">{{ __('landing.trusted_title') }}</p>
        
        <div class="flex flex-wrap justify-center gap-4 md:gap-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-700">
            @foreach(['Coffee Shops', 'Restaurants', 'Salons', 'Gyms', 'Pharmacies', 'Bakeries'] as $type)
            <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-full border border-gray-200 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 transition-colors cursor-default">
                <span class="font-medium">{{ $type }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
HTML,

    'how-it-works' => <<<'HTML'
<div class="py-24 bg-gray-50" id="how-it-works">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="font-heading text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ __('landing.how_it_works_title') }}</h2>
            <div class="w-20 h-1 bg-indigo-600 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Connecting Line -->
            <div class="hidden md:block absolute top-12 left-1/8 right-1/8 h-0.5 bg-gradient-to-r from-indigo-200 via-purple-200 to-indigo-200 z-0"></div>

            @php
                $steps = [
                    ['icon' => 'heroicon-o-user-plus', 'title' => __('landing.how_step_1')],
                    ['icon' => 'heroicon-o-qr-code', 'title' => __('landing.how_step_2')],
                    ['icon' => 'heroicon-o-device-phone-mobile', 'title' => __('landing.how_step_3')],
                    ['icon' => 'heroicon-o-gift', 'title' => __('landing.how_step_4')],
                ];
            @endphp

            @foreach($steps as $index => $step)
            <div class="relative z-10 flex flex-col items-center group text-center">
                <div class="w-24 h-24 bg-white rounded-2xl shadow-xl border border-gray-100 flex items-center justify-center mb-6 transform group-hover:-translate-y-2 group-hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                    <div class="absolute inset-0 bg-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="absolute top-2 right-2 text-xs font-bold text-gray-300">0{{ $index + 1 }}</span>
                    @svg($step['icon'], 'w-10 h-10 text-indigo-600 relative z-10')
                </div>
                <h3 class="font-heading text-xl font-semibold text-gray-900">{{ $step['title'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</div>
HTML,

    'features' => <<<'HTML'
<div class="py-24 bg-white" id="features">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="font-heading text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ __('landing.features_title') }}</h2>
            <p class="text-xl text-gray-500">{{ __('landing.features_subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $features = [
                    ['title' => 'Digital Cards', 'desc' => 'Replace paper cards with secure QR codes.', 'icon' => 'heroicon-o-credit-card', 'color' => 'indigo'],
                    ['title' => 'Multi-Branch', 'desc' => 'Manage all your shop branches from one place.', 'icon' => 'heroicon-o-building-storefront', 'color' => 'purple'],
                    ['title' => 'Analytics', 'desc' => 'Track customer behavior and campaign performance.', 'icon' => 'heroicon-o-chart-bar', 'color' => 'blue'],
                    ['title' => 'Staff Roles', 'desc' => 'Assign Cashiers and Managers with scoped access.', 'icon' => 'heroicon-o-users', 'color' => 'green'],
                    ['title' => 'Automated Rewards', 'desc' => 'Automatically notify cashiers when rewards are ready.', 'icon' => 'heroicon-o-sparkles', 'color' => 'yellow'],
                    ['title' => 'Multi-Language', 'desc' => 'Full RTL Arabic and English support.', 'icon' => 'heroicon-o-language', 'color' => 'red'],
                ];
            @endphp

            @foreach($features as $feature)
            <div class="group bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl hover:border-{{ $feature['color'] }}-100 transition-all duration-300 transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-{{ $feature['color'] }}-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    @svg($feature['icon'], 'w-7 h-7 text-' . $feature['color'] . '-600')
                </div>
                <h3 class="font-heading text-xl font-semibold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                <p class="text-gray-500">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
HTML,

    'dashboard-showcase' => <<<'HTML'
<div class="py-24 bg-gray-900 bg-grid-dark text-white relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-[100px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h2 class="font-heading text-3xl md:text-5xl font-bold mb-4">{{ __('landing.dashboard_title') }}</h2>
            <p class="text-gray-400 text-xl">Monitor your business growth in real-time.</p>
        </div>

        <div class="bg-gray-800 rounded-3xl border border-gray-700 shadow-2xl overflow-hidden backdrop-blur-xl">
            <div class="flex items-center gap-2 bg-gray-800/80 px-4 py-3 border-b border-gray-700">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Sidebar -->
                <div class="hidden md:flex flex-col gap-4 border-r rtl:border-r-0 rtl:border-l border-gray-700 pr-6 rtl:pr-0 rtl:pl-6">
                    @foreach(['Dashboard', 'Customers', 'Campaigns', 'Transactions', 'Reports'] as $item)
                        <div class="py-2 px-4 rounded-lg {{ $loop->first ? 'bg-indigo-500/20 text-indigo-400' : 'text-gray-400 hover:bg-gray-700/50' }} transition">
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
                
                <!-- Main Content -->
                <div class="md:col-span-3 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-700/50 p-6 rounded-2xl border border-gray-600">
                            <p class="text-gray-400 text-sm mb-2">Total Customers</p>
                            <p class="text-3xl font-bold">1,248</p>
                            <p class="text-green-400 text-sm mt-2">↑ 12% this month</p>
                        </div>
                        <div class="bg-gray-700/50 p-6 rounded-2xl border border-gray-600">
                            <p class="text-gray-400 text-sm mb-2">Active Cards</p>
                            <p class="text-3xl font-bold">892</p>
                        </div>
                        <div class="bg-gray-700/50 p-6 rounded-2xl border border-gray-600">
                            <p class="text-gray-400 text-sm mb-2">Rewards Redeemed</p>
                            <p class="text-3xl font-bold">345</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-700/50 p-6 rounded-2xl border border-gray-600 h-64 flex items-center justify-center">
                        <span class="text-gray-500">Analytics Chart View</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
HTML,

    'card-preview' => <<<'HTML'
<!-- Combined with Hero in this design for visual punch, but leaving stub -->
<div class="hidden"></div>
HTML,

    'comparison' => <<<'HTML'
<div class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="font-heading text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ __('landing.compare_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Traditional -->
            <div class="bg-red-50 rounded-3xl p-8 border border-red-100">
                <h3 class="font-heading text-2xl font-bold text-red-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    {{ __('landing.compare_traditional') }}
                </h3>
                <ul class="space-y-4 text-red-800/80">
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Paper cards easily lost</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> No customer data collected</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Fraud and fake stamps</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Zero analytics</li>
                </ul>
            </div>
            
            <!-- ThreeDigits -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 shadow-2xl text-white transform md:-translate-y-4 border border-indigo-400 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <h3 class="font-heading text-2xl font-bold mb-6 flex items-center gap-2 relative z-10">
                    <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('landing.compare_threedigits') }}
                </h3>
                <ul class="space-y-4 relative z-10 font-medium">
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 100% Digital & Always with customer</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Rich customer database built instantly</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Secure QR scanning by cashiers</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Powerful reporting dashboard</li>
                </ul>
            </div>
        </div>
    </div>
</div>
HTML,

    'benefits' => <<<'HTML'
<div class="hidden"></div>
HTML,

    'pricing' => <<<'HTML'
<div class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="font-heading text-3xl md:text-5xl font-bold text-gray-900 mb-4">{{ __('landing.pricing_title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto items-center">
            <!-- Starter -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900">{{ __('landing.pricing_starter') }}</h3>
                <div class="mt-4 flex items-baseline text-4xl font-extrabold">
                    $29<span class="ml-1 text-xl font-medium text-gray-500">/mo</span>
                </div>
                <ul class="mt-8 space-y-4 text-sm text-gray-600">
                    <li class="flex gap-2"><span>✔️</span> 1 Branch</li>
                    <li class="flex gap-2"><span>✔️</span> Up to 500 Customers</li>
                    <li class="flex gap-2"><span>✔️</span> 1 Active Campaign</li>
                </ul>
                <a href="#" class="mt-8 block w-full bg-indigo-50 text-indigo-700 font-semibold py-3 px-4 rounded-xl text-center hover:bg-indigo-100 transition">Get Started</a>
            </div>

            <!-- Business (Highlighted) -->
            <div class="bg-indigo-600 rounded-3xl p-8 border border-indigo-500 shadow-2xl shadow-indigo-200 transform md:scale-105 relative text-white">
                <div class="absolute top-0 inset-x-0 flex justify-center -mt-3">
                    <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Most Popular</span>
                </div>
                <h3 class="text-xl font-bold">{{ __('landing.pricing_business') }}</h3>
                <div class="mt-4 flex items-baseline text-4xl font-extrabold">
                    $79<span class="ml-1 text-xl font-medium text-indigo-200">/mo</span>
                </div>
                <ul class="mt-8 space-y-4 text-sm text-indigo-100">
                    <li class="flex gap-2 text-white"><span>✔️</span> Up to 5 Branches</li>
                    <li class="flex gap-2 text-white"><span>✔️</span> Unlimited Customers</li>
                    <li class="flex gap-2 text-white"><span>✔️</span> Unlimited Campaigns</li>
                    <li class="flex gap-2 text-white"><span>✔️</span> Advanced Analytics</li>
                </ul>
                <a href="#" class="mt-8 block w-full bg-white text-indigo-600 font-bold py-3 px-4 rounded-xl text-center hover:bg-gray-50 transition shadow-lg">Start Free Trial</a>
            </div>

            <!-- Enterprise -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900">{{ __('landing.pricing_enterprise') }}</h3>
                <div class="mt-4 flex items-baseline text-4xl font-extrabold">
                    Custom
                </div>
                <ul class="mt-8 space-y-4 text-sm text-gray-600">
                    <li class="flex gap-2"><span>✔️</span> Unlimited Branches</li>
                    <li class="flex gap-2"><span>✔️</span> Custom Integrations</li>
                    <li class="flex gap-2"><span>✔️</span> Dedicated Support</li>
                </ul>
                <a href="#" class="mt-8 block w-full bg-indigo-50 text-indigo-700 font-semibold py-3 px-4 rounded-xl text-center hover:bg-indigo-100 transition">Contact Sales</a>
            </div>
        </div>
    </div>
</div>
HTML,

    'faq' => <<<'HTML'
<div class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-heading text-3xl md:text-5xl font-bold text-gray-900 mb-12 text-center">{{ __('landing.faq_title') }}</h2>
        
        <div class="space-y-4" x-data="{ active: null }">
            @php
                $faqs = [
                    'Do customers need to download an app?' => 'No! Customers simply open a secure web link (QR code) which they can save to their phone\'s home screen. No downloads required.',
                    'Can I manage multiple branches?' => 'Yes, our Business and Enterprise plans fully support multiple branches, managers, and distinct reporting per location.',
                    'Does it support Arabic?' => 'Yes, the entire platform including the shop dashboard and customer card fully supports RTL Arabic and LTR English.',
                ];
            @endphp

            @foreach($faqs as $q => $a)
            <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white">
                <button @click="active = active === {{ $loop->index }} ? null : {{ $loop->index }}" class="w-full flex justify-between items-center p-6 text-left focus:outline-none hover:bg-gray-50 transition-colors">
                    <span class="font-semibold text-gray-900">{{ $q }}</span>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{'rotate-180': active === {{ $loop->index }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === {{ $loop->index }}" x-collapse class="p-6 pt-0 text-gray-600 bg-gray-50">
                    {{ $a }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
HTML,

    'cta' => <<<'HTML'
<div class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-indigo-600"></div>
    <div class="absolute inset-0 bg-grid-dark opacity-20"></div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="font-heading text-4xl md:text-6xl font-extrabold mb-8">{{ __('landing.final_cta_title') }}</h2>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="#" class="bg-white text-indigo-600 text-lg font-bold px-8 py-4 rounded-full transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                {{ __('landing.hero_cta_1') }}
            </a>
        </div>
    </div>
</div>
HTML,

    'footer' => <<<'HTML'
<footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2 md:col-span-1">
                <span class="font-heading font-extrabold text-2xl text-indigo-400">ThreeDigits</span>
                <p class="mt-4 text-gray-400 text-sm">The modern loyalty platform for modern shops.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">{{ __('landing.footer_product') }}</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Features</a></li>
                    <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">{{ __('landing.footer_company') }}</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">About</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">{{ __('landing.footer_legal') }}</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white transition">Privacy</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
            &copy; 2026 ThreeDigits Loyalty. All rights reserved.
        </div>
    </div>
</footer>
HTML,
];

foreach ($components as $name => $html) {
    file_put_contents("resources/views/components/landing/{$name}.blade.php", $html);
}
echo "Components built.\n";
