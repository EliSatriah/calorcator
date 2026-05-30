<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-brandOrange border border-transparent rounded-full font-bold text-sm text-white tracking-widest hover:bg-orange-500 shadow-lg shadow-brandOrange/30 hover:shadow-brandOrange/50 focus:outline-none focus:ring-2 focus:ring-brandOrange focus:ring-offset-2 transition ease-in-out duration-300 transform hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
