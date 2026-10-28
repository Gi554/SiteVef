@props(['name'])
<svg {{ $attributes->merge(['class' => 'admin-icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('overview')<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>@break
        @case('donation')<path d="M12 3v18M16 7H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/><path d="M4 3h16"/>@break
        @case('projects')<path d="M3 7h7l2 2h9v11H3Z"/><path d="M3 7V4h7l2 3h8v2"/>@break
        @case('news')<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h4M8 18h8"/>@break
        @case('heart')<path d="m12 21-8-8A5.5 5.5 0 0 1 12 5a5.5 5.5 0 0 1 8 8Z"/>@break
        @case('message')<path d="M4 4h16v13H9l-5 4Z"/><path d="M8 8h8M8 12h5"/>@break
        @case('application')<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 2h6v4H9ZM9 11h6M9 15h6"/>@break
        @case('team')<circle cx="9" cy="8" r="3"/><path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M18 15a5 5 0 0 1 3 5"/>@break
        @case('quote')<path d="M4 6h6v7a5 5 0 0 1-5 5M14 6h6v7a5 5 0 0 1-5 5"/>@break
        @default<path d="M9 4H4v16h5M13 8l4 4-4 4M8 12h13"/>
    @endswitch
</svg>
