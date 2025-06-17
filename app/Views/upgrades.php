<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20 pt-2">
    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Upgrade Your Experience</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Choose a package to unlock premium features</p>
        </div>

        <!-- Packages List -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <?php $packages = [
                [
                    'name' => 'Basic',
                    'price' => '$4.99/mo',
                    'features' => ['Access to basic features', 'Standard support', 'Limited group joins'],
                    'id' => 'basic',
                ],
                [
                    'name' => 'Pro',
                    'price' => '$9.99/mo',
                    'features' => ['All Basic features', 'Priority support', 'Unlimited group joins', 'Advanced analytics'],
                    'id' => 'pro',
                ],
                [
                    'name' => 'Elite',
                    'price' => '$19.99/mo',
                    'features' => ['All Pro features', '1-on-1 onboarding', 'Beta features access', 'Custom integrations'],
                    'id' => 'elite',
                ],
            ]; ?>
            <?php foreach ($packages as $pkg): ?>
                <div class="card p-6 flex flex-col items-center text-center">
                    <h2 class="text-xl font-bold text-blue-700 dark:text-blue-300 mb-2"><?= $pkg['name'] ?></h2>
                    <div class="text-3xl font-extrabold mb-4 text-gray-900 dark:text-white"><?= $pkg['price'] ?></div>
                    <ul class="mb-6 space-y-2 text-gray-600 dark:text-gray-300">
                        <?php foreach ($pkg['features'] as $feature): ?>
                            <li>• <?= $feature ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="btn-primary px-6 py-2 subscribe-btn" data-package="<?= $pkg['id'] ?>">Subscribe</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Subscribe Modal -->
    <div id="subscribeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 text-center">
            <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Complete Your Purchase</h3>
                    <button type="button" id="closeSubscribeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="subscribeForm">
                    <input type="hidden" id="selectedPackage" name="package" value="">
                    <div class="mb-4">
                        <label for="cardNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card Number</label>
                        <input type="text" id="cardNumber" name="cardNumber" required maxlength="19" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry</label>
                            <input type="text" id="expiry" name="expiry" required maxlength="5" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="MM/YY">
                        </div>
                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CVC</label>
                            <input type="text" id="cvc" name="cvc" required maxlength="4" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="CVC">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name on Card</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Full Name">
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" id="cancelSubscribe" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none">Cancel</button>
                        <button type="submit" class="btn-primary px-4 py-2">Pay & Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Modal open/close logic
const subscribeBtns = document.querySelectorAll('.subscribe-btn');
const subscribeModal = document.getElementById('subscribeModal');
const closeSubscribeModal = document.getElementById('closeSubscribeModal');
const cancelSubscribe = document.getElementById('cancelSubscribe');
const subscribeForm = document.getElementById('subscribeForm');
const selectedPackage = document.getElementById('selectedPackage');

subscribeBtns.forEach(btn => {
    btn.addEventListener('click', function () {
        selectedPackage.value = btn.getAttribute('data-package');
        subscribeModal.classList.remove('hidden');
    });
});
if (closeSubscribeModal) closeSubscribeModal.onclick = () => subscribeModal.classList.add('hidden');
if (cancelSubscribe) cancelSubscribe.onclick = () => subscribeModal.classList.add('hidden');

// Handle form submission (simulate purchase)
if (subscribeForm) {
    subscribeForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn = subscribeForm.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Processing...';
        setTimeout(() => {
            alert('Subscription successful!');
            subscribeModal.classList.add('hidden');
            subscribeForm.reset();
            btn.disabled = false;
            btn.textContent = 'Pay & Subscribe';
        }, 1500);
    });
}
</script> 