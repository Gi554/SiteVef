@php($isWorkspace = request()->routeIs('admin.projets.index'))
<aside class="admin-sidebar" id="admin-sidebar" aria-label="Navigation de l’administration">
    <div class="sidebar-header"><a href="{{ route('admin.projets.index') }}" class="admin-brand"><img src="{{ asset('Style1/images/logo.png') }}" alt="VEF" width="42" height="42"><span>Verre d’Eau Fraîche<strong>Administration</strong></span></a><button type="button" class="admin-menu-close" aria-label="Fermer le menu" data-admin-menu-close>×</button></div>
    <nav class="sidebar-nav" aria-label="Administration">
        @foreach([
            'PILOTER' => ['dashboard' => ['Vue d’ensemble', 'overview'], 'dons' => ['Dons et collectes', 'donation']],
            'PUBLIER' => ['projets' => ['Projets', 'projects'], 'actualites' => ['Actualités', 'news'], 'parrainage' => ['Parrainage', 'heart']],
            'ÉCHANGER' => ['messages' => ['Messages', 'message'], 'candidatures' => ['Candidatures', 'application']],
            'L’ASSOCIATION' => ['membres' => ['Notre équipe', 'team'], 'temoignages' => ['Témoignages', 'quote']],
        ] as $group => $items)
            <p class="admin-nav-group">{{ $group }}</p><ul>
                @foreach($items as $panel => [$label, $icon])
                    <li><a href="{{ $panel === 'parrainage' ? route('admin.parrainage.edit') : route('admin.projets.index').'#'.$panel }}" class="nav-item {{ $panel === 'parrainage' && request()->routeIs('admin.parrainage.*') ? 'active' : '' }}"
                        @if($isWorkspace && $panel !== 'parrainage') data-panel="{{ $panel }}" @endif
                        @if($panel === 'parrainage' && request()->routeIs('admin.parrainage.*')) aria-current="page" @endif>
                        <x-admin-icon :name="$icon" /><span>{{ $label }}</span>
                        @if($panel === 'messages' && isset($stats))<span class="admin-nav-count" data-unread-badge @if(!$stats['messages_non_lus']) hidden @endif>{{ $stats['messages_non_lus'] }}</span>@endif
                    </a></li>
                @endforeach
            </ul>
        @endforeach
    </nav>
    <div class="sidebar-footer"><div class="admin-account"><span class="admin-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>Compte administrateur</small></span></div><form method="post" action="{{ route('logout') }}">@csrf<button type="submit" class="admin-logout"><x-admin-icon name="logout" /> Se déconnecter</button></form></div>
</aside>
