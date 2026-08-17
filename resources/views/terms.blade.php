<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">
    <x-legal-page
        heading="Terms & Conditions"
        intro="What PurrQuery provides, what it does not, and the limits of relying on general cat care information. Written to be read, not to be skipped."
        :effective="$effective"
        :sections="$sections">
        <a href="{{ route('privacy') }}" class="btn-outline rounded-full px-6">Privacy policy</a>
    </x-legal-page>
</x-layouts.app>
