<x-mail::message>
{{ $body }}

—
{{ config('app.name') }}

<x-mail::panel>
On {{ $original->created_at->format('M j, Y') }}, you wrote:

{{ $original->message }}
</x-mail::panel>
</x-mail::message>
