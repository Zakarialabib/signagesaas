<x-nav-link :href="route('screen.concepts')" :active="request()->routeIs('screen.concepts')">
    <x-icon name="template" class="w-5 h-5 mr-2" />
    {{ __('Screen Concepts') }}
</x-nav-link> 