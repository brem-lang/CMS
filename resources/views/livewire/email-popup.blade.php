<div
    x-data="emailPopup({
        subscribeUrl: {{ \Illuminate\Support\Js::from($subscribeUrl) }},
        csrfToken: {{ \Illuminate\Support\Js::from($csrfToken) }}
    })"
    x-init="init()"
>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <div
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="email-popup-overlay"
        style="position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); padding: 20px;"
    >
        <div
            class="email-popup-card"
            style="background: #fff; border-radius: 12px; padding: 32px; max-width: 420px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.2); position: relative;"
            @click.outside="close()"
        >
            <button
                type="button"
                @click="close()"
                aria-label="Close"
                style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer; color: #666; line-height: 1; padding: 0; width: 32px; height: 32px;"
            >&times;</button>

            <h3 style="margin: 0 0 8px; font-size: 1.5rem; font-weight: 700; color: #111;">Stay in the loop</h3>
            <p style="margin: 0 0 24px; color: #666; font-size: 0.95rem;">Subscribe for updates and offers.</p>

            <form @submit.prevent="submitForm">
                @csrf
                <input
                    type="email"
                    x-model="email"
                    name="email"
                    placeholder="Your email address"
                    :disabled="loading"
                    style="width: 100%; padding: 14px 16px; border: 1px solid #e5e5e5; border-radius: 8px; font-size: 16px; margin-bottom: 12px; box-sizing: border-box;"
                />
                <p x-show="error" x-cloak style="margin: 0 0 12px; font-size: 13px; color: #dc3545;" x-text="error"></p>
                <button
                    type="submit"
                    :disabled="loading"
                    style="width: 100%; padding: 14px; background: #e53637; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;"
                >
                    <span x-show="!loading">Subscribe</span>
                    <span x-show="loading" x-cloak>Subscribing...</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('emailPopup', (config = {}) => ({
            show: false,
            timerDone: false,
            scrollDone: false,
            suppressed: false,
            email: '',
            error: '',
            loading: false,
            subscribeUrl: config.subscribeUrl || '',
            csrfToken: config.csrfToken || '',

            init() {
                const SUBSCRIBED_KEY = 'newsletter_subscribed';
                const CLOSED_KEY = 'newsletter_closed';
                const THIRTY_DAYS_MS = 30 * 24 * 60 * 60 * 1000;
                const SEVEN_DAYS_MS = 7 * 24 * 60 * 60 * 1000;

                const subscribedAt = localStorage.getItem(SUBSCRIBED_KEY);
                if (subscribedAt && (Date.now() - parseInt(subscribedAt, 10) < THIRTY_DAYS_MS)) {
                    this.suppressed = true;
                    return;
                }
                const closedAt = localStorage.getItem(CLOSED_KEY);
                if (closedAt && (Date.now() - parseInt(closedAt, 10) < SEVEN_DAYS_MS)) {
                    this.suppressed = true;
                    return;
                }

                setTimeout(() => {
                    this.timerDone = true;
                    this.maybeShow();
                }, 10000);

                window.addEventListener('scroll', () => {
                    if (window.scrollY > 100) this.scrollDone = true;
                    this.maybeShow();
                }, { passive: true });
            },

            maybeShow() {
                if (this.suppressed) return;
                if (this.timerDone && this.scrollDone) this.show = true;
            },

            close() {
                localStorage.setItem('newsletter_closed', Date.now().toString());
                this.suppressed = true;
                this.show = false;
            },

            async submitForm() {
                this.error = '';
                const email = this.email?.trim();
                if (!email) {
                    this.error = 'Please enter your email address.';
                    return;
                }
                const url = this.subscribeUrl;
                const csrf = this.csrfToken;
                if (!url || !csrf) {
                    this.error = 'Something went wrong. Please refresh the page.';
                    return;
                }
                this.loading = true;
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ email }),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (res.ok && data.success) {
                        localStorage.setItem('newsletter_subscribed', Date.now().toString());
                        this.suppressed = true;
                        this.email = '';
                        this.show = false;
                    } else if (res.status === 422 && data.errors?.email) {
                        // Email already in DB: treat like success, close popup and refresh
                        localStorage.setItem('newsletter_subscribed', Date.now().toString());
                        this.suppressed = true;
                        this.show = false;
                        window.location.reload();
                    } else {
                        this.error = data.message || data.errors?.email?.[0] || 'Something went wrong. Please try again.';
                    }
                } catch (e) {
                    this.error = 'Unable to subscribe. Please try again.';
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
