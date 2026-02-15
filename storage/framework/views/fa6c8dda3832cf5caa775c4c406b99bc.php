<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام إدارة المستخدمين - User Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <div class="text-3xl font-bold">👻</div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white font-poppins">نظام الإدارة</h1>
                </div>
                <div class="flex items-center gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="px-4 py-2 rounded-lg bg-gradient-primary text-white font-medium hover:shadow-lg transition-all">
                            لوحة التحكم
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-purple-600 font-medium transition">
                            <?php echo e(__('Login')); ?>

                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                            <a href="<?php echo e(route('register')); ?>" class="px-4 py-2 rounded-lg bg-gradient-primary text-white font-medium hover:shadow-lg transition-all">
                                <?php echo e(__('Register')); ?>

                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-4 py-12 gradient-primary text-white">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8 animate-bounce">
                <span class="text-7xl">🎯</span>
            </div>
            <h2 class="text-5xl md:text-7xl font-bold mb-6">
                نظام إدارة المستخدمين
            </h2>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto">
                منصة احترافية وسهلة الاستخدام لإدارة المستخدمين والقطاعات التعليمية بكفاءة عالية
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="px-8 py-4 bg-white text-purple-600 font-bold rounded-lg hover:shadow-2xl transition-all text-lg">
                        ⚡ الذهاب إلى لوحة التحكم
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-8 py-4 bg-white text-purple-600 font-bold rounded-lg hover:shadow-2xl transition-all text-lg">
                        🔐 تسجيل الدخول
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="px-8 py-4 border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition-all text-lg">
                            ✨ إنشاء حساب جديد
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-4xl md:text-5xl font-bold text-center mb-16 gradient-text">المميزات الرئيسية</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="p-8 bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="text-5xl mb-4">👥</div>
                    <h4 class="text-2xl font-bold mb-3">إدارة المستخدمين</h4>
                    <p class="text-gray-600 dark:text-gray-300">
                        إضافة وتعديل وحذف المستخدمين مع إمكانية تعيين الأدوار والصلاحيات المختلفة بسهولة
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="p-8 bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="text-5xl mb-4">🏢</div>
                    <h4 class="text-2xl font-bold mb-3">إدارة القطاعات</h4>
                    <p class="text-gray-600 dark:text-gray-300">
                        تنظيم القطاعات التعليمية والمؤسسات وربطها بكفاءة مع المستخدمين
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="p-8 bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="text-5xl mb-4">🔒</div>
                    <h4 class="text-2xl font-bold mb-3">الأمان والصلاحيات</h4>
                    <p class="text-gray-600 dark:text-gray-300">
                        نظام صلاحيات متقدم للتحكم الكامل في الوصول والبيانات بأمان عالي
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="p-8 bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="text-5xl mb-4">📊</div>
                    <h4 class="text-2xl font-bold mb-3">التقارير والإحصائيات</h4>
                    <p class="text-gray-600 dark:text-gray-300">
                        تقارير شاملة وإحصائيات تفصيلية عن جميع العمليات والأنشطة
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="p-8 bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="text-5xl mb-4">⚙️</div>
                    <h4 class="text-2xl font-bold mb-3">سهلة الاستخدام</h4>
                    <p class="text-gray-600 dark:text-gray-300">
                        واجهة حديثة وبديهية مصممة لتسهيل جميع المهام الإدارية
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="p-8 bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300">
                    <div class="text-5xl mb-4">🌐</div>
                    <h4 class="text-2xl font-bold mb-3">دعم متعدد اللغات</h4>
                    <p class="text-gray-600 dark:text-gray-300">
                        دعم كامل للغة العربية والإنجليزية مع واجهة ديناميكية
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 px-4 gradient-primary text-white">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-4xl font-bold text-center mb-16">الإحصائيات</h3>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="p-8 bg-white/10 rounded-xl backdrop-blur">
                    <div class="text-5xl font-bold mb-4">+1000</div>
                    <p class="text-xl opacity-90">مستخدم نشط</p>
                </div>
                <div class="p-8 bg-white/10 rounded-xl backdrop-blur">
                    <div class="text-5xl font-bold mb-4">+50</div>
                    <p class="text-xl opacity-90">قطاع تعليمي</p>
                </div>
                <div class="p-8 bg-white/10 rounded-xl backdrop-blur">
                    <div class="text-5xl font-bold mb-4">99.9%</div>
                    <p class="text-xl opacity-90">توفر النظام</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 bg-white dark:bg-gray-800">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-4xl md:text-5xl font-bold mb-8 gradient-text">هل أنت مستعد؟</h3>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-12">
                انضم إلينا الآن واستمتع بتجربة إدارة احترافية وسهلة
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="px-8 py-4 gradient-primary text-white font-bold rounded-lg hover:shadow-2xl transition-all text-lg">
                        الذهاب إلى لوحة التحكم
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="px-8 py-4 gradient-primary text-white font-bold rounded-lg hover:shadow-2xl transition-all text-lg">
                        تسجيل الدخول الآن
                    </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="px-8 py-4 border-2 border-purple-600 text-purple-600 dark:text-purple-400 dark:border-purple-400 font-bold rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all text-lg">
                            إنشاء حساب جديد
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4">
        
        <div class="max-w-6xl mx-auto mt-2 pt-8 border-t border-gray-800 text-center text-gray-400">
            <p>&copy; 2026 نظام إدارة المستخدمين. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script>
        // Dark mode toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Check for dark mode preference
            if (localStorage.getItem('darkMode') === 'true' || 
                (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\projects\laravel12-user-management\resources\views/welcome.blade.php ENDPATH**/ ?>