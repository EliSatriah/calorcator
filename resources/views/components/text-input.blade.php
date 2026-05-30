@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-brandBlue focus:ring-brandBlue rounded-xl shadow-sm transition-colors duration-200 px-4 py-3']) }}>
