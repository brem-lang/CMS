<x-app-layout>
    <div class="max-w-2xl mx-auto py-12">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Chat Pass Checkout</h1>

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Chat Pass</h2>
                <p class="text-gray-600 mb-4">Get unlimited access to our chat feature</p>
                <p class="text-3xl font-bold text-indigo-600">₱30.00</p>
            </div>

            <form method="POST" action="{{ route('checkout.create') }}">
                @csrf
                <input type="hidden" name="room_id" value="{{ request('room_id') }}">
                <input type="hidden" name="quantity" value="{{ request('quantity', 1) }}">
                <input type="hidden" name="total_amount" value="{{ request('total_amount', 3000) }}">
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition">
                    Subscribe
                </button>
            </form>

            <p class="text-sm text-gray-500 mt-4">
                You will be redirected to Stripe to complete your payment securely.
            </p>
        </div>
    </div>
</x-app-layout>
