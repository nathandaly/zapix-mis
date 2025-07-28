<img
    x-data="{
        isDark: window.matchMedia('(prefers-color-scheme: dark)').matches,
        init() {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                this.isDark = e.matches;
            });
        }
    }"
    :src="isDark ? '{{ asset('img/logo.png') }}' : '{{ asset('img/logo-light.png') }}'"
    alt="{{ config('app.name', 'Laravel') }}"
    {{ $attributes }}>
