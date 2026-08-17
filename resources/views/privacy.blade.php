<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">
    <x-legal-page
        heading="Privacy Policy"
        intro="What we collect, why, and what we do not do. The short version: almost nothing, and none of it is shared."
        :effective="$effective"
        :sections="$sections"
        :summary="$summary">
        <a href="{{ route('terms') }}" class="btn-outline rounded-full px-6">Terms &amp; conditions</a>
    </x-legal-page>
</x-layouts.app>
