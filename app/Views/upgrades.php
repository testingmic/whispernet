<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                    Premium Features
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    Unlock Your Full Potential
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Choose the perfect plan to enhance your experience with advanced features, 
                    priority support, and exclusive content.
                </p>
            </div>
        </div>
    </div>

    <!-- Pricing Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <?php 
            $packages = [
                [
                    'name' => 'Starter',
                    'price' => '$4.99',
                    'period' => 'per month',
                    'description' => 'Perfect for getting started',
                    'features' => [
                        'Access to basic features',
                        'Standard support response',
                        'Up to 5 group joins',
                        'Basic analytics',
                        'Standard content quality'
                    ],
                    'id' => 'starter',
                    'popular' => false,
                    'color' => 'blue'
                ],
                [
                    'name' => 'Professional',
                    'price' => '$9.99',
                    'period' => 'per month',
                    'description' => 'Most popular choice',
                    'features' => [
                        'All Starter features',
                        'Priority support (24h response)',
                        'Unlimited group joins',
                        'Advanced analytics & insights',
                        'HD content quality',
                        'Custom themes',
                        'Ad-free experience'
                    ],
                    'id' => 'professional',
                    'popular' => true,
                    'color' => 'purple'
                ],
                [
                    'name' => 'Enterprise',
                    'price' => '$19.99',
                    'period' => 'per month',
                    'description' => 'For power users & teams',
                    'features' => [
                        'All Professional features',
                        '1-on-1 onboarding session',
                        'Beta features access',
                        'Custom integrations',
                        'API access',
                        'White-label options',
                        'Dedicated account manager'
                    ],
                    'id' => 'enterprise',
                    'popular' => false,
                    'color' => 'indigo'
                ]
            ]; 
            ?>
            
            <?php foreach ($packages as $pkg): ?>
                <div class="relative group">
                    <?php if ($pkg['popular']): ?>
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-full text-sm font-semibold shadow-lg">
                                Most Popular
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="relative h-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 <?= $pkg['popular'] ? 'ring-2 ring-purple-500 ring-opacity-50' : '' ?>">
                        <!-- Package Header -->
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-<?= $pkg['color'] ?>-500 to-<?= $pkg['color'] ?>-600 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php if ($pkg['id'] === 'starter'): ?>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    <?php elseif ($pkg['id'] === 'professional'): ?>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <?php else: ?>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    <?php endif; ?>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2"><?= $pkg['name'] ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6"><?= $pkg['description'] ?></p>
                            
                            <!-- Pricing -->
                            <div class="mb-8">
                                <div class="flex items-baseline justify-center">
                                    <span class="text-5xl font-bold text-gray-900 dark:text-white"><?= $pkg['price'] ?></span>
                                    <span class="text-gray-500 dark:text-gray-400 ml-2"><?= $pkg['period'] ?></span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Billed monthly • Cancel anytime</p>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-4 mb-8">
                            <?php foreach ($pkg['features'] as $feature): ?>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-5 h-5 mt-0.5">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-gray-700 dark:text-gray-300"><?= $feature ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- CTA Button -->
                        <button class="w-full py-4 px-6 rounded-xl font-semibold text-white bg-gradient-to-r from-<?= $pkg['color'] ?>-500 to-<?= $pkg['color'] ?>-600 hover:from-<?= $pkg['color'] ?>-600 hover:to-<?= $pkg['color'] ?>-700 transform transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-<?= $pkg['color'] ?>-500 focus:ring-offset-2 subscribe-btn" data-package="<?= $pkg['id'] ?>">
                            Get Started
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Additional Info -->
        <div class="mt-16 text-center">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 max-w-4xl mx-auto">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Can I cancel anytime?</h4>
                        <p class="text-gray-600 dark:text-gray-400">Yes, you can cancel your subscription at any time. No long-term commitments required.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">What payment methods do you accept?</h4>
                        <p class="text-gray-600 dark:text-gray-400">We accept all major credit cards, PayPal, and Apple Pay for secure payments.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Is there a free trial?</h4>
                        <p class="text-gray-600 dark:text-gray-400">Yes! All plans come with a 7-day free trial. No credit card required to start.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">How do I upgrade or downgrade?</h4>
                        <p class="text-gray-600 dark:text-gray-400">You can change your plan anytime from your account settings. Changes take effect immediately.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Subscribe Modal -->
    <div id="subscribeModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden" role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 text-center">
            <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Complete Your Purchase</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">You're just one step away from unlocking premium features</p>
                    </div>
                    <button type="button" id="closeSubscribeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Selected Plan Display -->
                <div id="selectedPlanDisplay" class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white" id="planName">Professional Plan</h4>
                            <p class="text-gray-600 dark:text-gray-400" id="planPrice">$9.99/month</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <form id="subscribeForm" class="space-y-6">
                    <input type="hidden" id="selectedPackage" name="package" value="">
                    
                    <!-- Card Number -->
                    <div>
                        <label for="cardNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Card Number</label>
                        <div class="relative">
                            <input type="text" id="cardNumber" name="cardNumber" required maxlength="19" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                                   placeholder="1234 5678 9012 3456">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry and CVC -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                            <input type="text" id="expiry" name="expiry" required maxlength="5" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                                   placeholder="MM/YY">
                        </div>
                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVC</label>
                            <input type="text" id="cvc" name="cvc" required maxlength="4" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                                   placeholder="123">
                        </div>
                    </div>

                    <!-- Name on Card -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name on Card</label>
                        <input type="text" id="name" name="name" required 
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200" 
                               placeholder="Full Name">
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Your payment information is encrypted and secure. We use industry-standard SSL encryption to protect your data.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="button" id="cancelSubscribe" 
                                class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                            Complete Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced Modal Management
const subscribeBtns = document.querySelectorAll('.subscribe-btn');
const subscribeModal = document.getElementById('subscribeModal');
const closeSubscribeModal = document.getElementById('closeSubscribeModal');
const cancelSubscribe = document.getElementById('cancelSubscribe');
const subscribeForm = document.getElementById('subscribeForm');
const selectedPackage = document.getElementById('selectedPackage');
const selectedPlanDisplay = document.getElementById('selectedPlanDisplay');
const planName = document.getElementById('planName');
const planPrice = document.getElementById('planPrice');

// Plan details mapping
const planDetails = {
    'starter': { name: 'Starter Plan', price: '$4.99/month' },
    'professional': { name: 'Professional Plan', price: '$9.99/month' },
    'enterprise': { name: 'Enterprise Plan', price: '$19.99/month' }
};

// Enhanced button click handlers
subscribeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        const packageId = btn.getAttribute('data-package');
        const plan = planDetails[packageId];
        
        selectedPackage.value = packageId;
        planName.textContent = plan.name;
        planPrice.textContent = plan.price;
        
        // Add entrance animation
        subscribeModal.classList.remove('hidden');
        subscribeModal.querySelector('.inline-block').classList.add('animate-fadeIn');
    });
});

// Close modal handlers
[closeSubscribeModal, cancelSubscribe].forEach(btn => {
    if (btn) {
        btn.addEventListener('click', () => {
            subscribeModal.classList.add('hidden');
            subscribeModal.querySelector('.inline-block').classList.remove('animate-fadeIn');
        });
    }
});

// Click outside to close
subscribeModal.addEventListener('click', (e) => {
    if (e.target === subscribeModal) {
        subscribeModal.classList.add('hidden');
        subscribeModal.querySelector('.inline-block').classList.remove('animate-fadeIn');
    }
});

// Enhanced form submission
if (subscribeForm) {
    subscribeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = subscribeForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;
        
        // Simulate payment processing
        setTimeout(() => {
            // Show success state
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 text-white mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Success!
            `;
            submitBtn.classList.remove('from-blue-500', 'to-purple-600', 'hover:from-blue-600', 'hover:to-purple-700');
            submitBtn.classList.add('from-green-500', 'to-green-600');
            
            // Close modal after success
            setTimeout(() => {
                subscribeModal.classList.add('hidden');
                subscribeForm.reset();
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                submitBtn.classList.remove('from-green-500', 'to-green-600');
                submitBtn.classList.add('from-blue-500', 'to-purple-600', 'hover:from-blue-600', 'hover:to-purple-700');
                
                // Show success notification
                showNotification('Subscription activated successfully! Welcome to premium!', 'success');
            }, 1500);
        }, 2000);
    });
}

// Card number formatting
const cardNumber = document.getElementById('cardNumber');
if (cardNumber) {
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });
}

// Expiry date formatting
const expiry = document.getElementById('expiry');
if (expiry) {
    expiry.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });
}

// CVC formatting
const cvc = document.getElementById('cvc');
if (cvc) {
    cvc.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    notification.classList.add(bgColor, 'text-white');
    
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script> 
</script> 