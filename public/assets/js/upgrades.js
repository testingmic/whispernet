function handleUpgradeFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Processing...';
    setTimeout(() => {
        alert('Subscription successful!');
        document.getElementById('subscribeModal').classList.add('hidden');
        form.reset();
        btn.disabled = false;
        btn.textContent = 'Pay & Subscribe';
    }, 1500);
}

document.addEventListener('DOMContentLoaded', function () {
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

    // Attach the dedicated handler
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', handleUpgradeFormSubmit);
    }
}); 