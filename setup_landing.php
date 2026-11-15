<?php
$en = [
    'nav_features' => 'Features',
    'nav_login' => 'Shop Login',
    'nav_admin' => 'Admin Panel',
    
    'hero_title' => 'Turn First-Time Customers Into Loyal Customers',
    'hero_subtitle' => 'Create digital loyalty cards, reward repeat visits, and increase customer retention using QR codes, automated rewards, and a powerful dashboard.',
    'hero_cta_1' => 'Start Free Trial',
    'hero_cta_2' => 'Book a Demo',

    'trusted_title' => 'Perfect for Every Business',
    'how_it_works_title' => 'How It Works',
    'how_step_1' => 'Customer Registers',
    'how_step_2' => 'QR Loyalty Card Created',
    'how_step_3' => 'Cashier Scans QR',
    'how_step_4' => 'Reward Unlocked',

    'features_title' => 'Everything You Need to Grow',
    'features_subtitle' => 'Powerful tools to run a successful loyalty program.',
    
    'dashboard_title' => 'Powerful Analytics & Dashboard',
    
    'card_preview_title' => 'Beautiful Digital Cards',

    'compare_title' => 'Why Choose ThreeDigits?',
    'compare_traditional' => 'Traditional Loyalty',
    'compare_threedigits' => 'ThreeDigits Loyalty',
    
    'benefits_title' => 'Designed to Grow Your Business',

    'pricing_title' => 'Simple, Transparent Pricing',
    'pricing_starter' => 'Starter',
    'pricing_business' => 'Business',
    'pricing_enterprise' => 'Enterprise',

    'faq_title' => 'Frequently Asked Questions',
    
    'final_cta_title' => 'Start Building Customer Loyalty Today',
    
    'footer_product' => 'Product',
    'footer_company' => 'Company',
    'footer_legal' => 'Legal',
];

$ar = [
    'nav_features' => 'المميزات',
    'nav_login' => 'دخول المتاجر',
    'nav_admin' => 'لوحة الإدارة',

    'hero_title' => 'حوّل زوارك إلى عملاء دائمين',
    'hero_subtitle' => 'أنشئ بطاقات ولاء رقمية، وكافئ الزيارات المتكررة، وزد ولاء العملاء باستخدام رموز QR، والمكافآت التلقائية، ولوحة تحكم قوية.',
    'hero_cta_1' => 'ابدأ تجربتك المجانية',
    'hero_cta_2' => 'احجز عرضاً توضيحياً',

    'trusted_title' => 'مثالي لكل نشاط تجاري',
    'how_it_works_title' => 'كيف يعمل النظام',
    'how_step_1' => 'يسجل العميل',
    'how_step_2' => 'إصدار بطاقة QR',
    'how_step_3' => 'مسح الكاشير للبطاقة',
    'how_step_4' => 'استلام المكافأة',

    'features_title' => 'كل ما تحتاجه للنمو',
    'features_subtitle' => 'أدوات قوية لإدارة برنامج ولاء ناجح.',

    'dashboard_title' => 'لوحة تحكم وتقارير متقدمة',

    'card_preview_title' => 'بطاقات رقمية جذابة',

    'compare_title' => 'لماذا تختار ThreeDigits؟',
    'compare_traditional' => 'الولاء التقليدي',
    'compare_threedigits' => 'ThreeDigits',

    'benefits_title' => 'مصمم لتنمية أعمالك',

    'pricing_title' => 'باقات أسعار مرنة',
    'pricing_starter' => 'البداية',
    'pricing_business' => 'الأعمال',
    'pricing_enterprise' => 'الشركات',

    'faq_title' => 'الأسئلة الشائعة',

    'final_cta_title' => 'ابدأ في بناء ولاء عملائك اليوم',

    'footer_product' => 'المنتج',
    'footer_company' => 'الشركة',
    'footer_legal' => 'قانوني',
];

file_put_contents('lang/en/landing.php', "<?php\n\nreturn " . var_export($en, true) . ";\n");
file_put_contents('lang/ar/landing.php', "<?php\n\nreturn " . var_export($ar, true) . ";\n");

echo "Language files created.\n";

$components = [
    'hero', 'trusted-by', 'how-it-works', 'features', 
    'dashboard-showcase', 'card-preview', 'comparison', 
    'benefits', 'pricing', 'faq', 'cta', 'footer', 'nav'
];

foreach ($components as $comp) {
    $path = "resources/views/components/landing/{$comp}.blade.php";
    if (!file_exists($path)) {
        file_put_contents($path, "<!-- {$comp} component -->\n<div class=\"py-12\">\n    <div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8\">\n        <h2 class=\"text-2xl font-bold text-center\">{$comp}</h2>\n    </div>\n</div>\n");
    }
}
echo "Component stubs created.\n";
