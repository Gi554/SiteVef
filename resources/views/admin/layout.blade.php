<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — VEF</title>
    <link rel="icon" href="{{ asset('Style1/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Style1/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('Style1/css/editorial-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('Style1/css/project-status.css') }}">
    <link rel="stylesheet" href="{{ asset('Style1/css/admin-workspace.css') }}">
    @stack('head')
</head>
<body class="vef-admin" data-dashboard-url="{{ route('admin.projets.index') }}">
<a href="#admin-content" class="admin-skip">Aller au contenu</a>
<div class="admin-container">
    @include('admin.partials.sidebar')
    <main class="admin-main" id="admin-content" tabindex="-1">
        <header class="admin-topbar">
            <div class="admin-topbar__context"><span class="admin-topbar__label">ESPACE ASSOCIATION</span><span data-admin-current>@yield('title', 'Vue d’ensemble')</span></div>
            <div class="admin-topbar__actions"><a href="{{ route('index') }}" class="admin-site-link" target="_blank" rel="noopener">Voir le site <span aria-hidden="true">↗</span></a><button type="button" class="admin-menu-button" aria-controls="admin-sidebar" aria-expanded="false" data-admin-menu>Menu <span aria-hidden="true">☰</span></button><span class="admin-avatar" title="{{ auth()->user()->name }}">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span></div>
        </header>
        <div class="admin-workspace">
            <div id="admin-feedback" class="admin-feedback" role="status" aria-live="polite" hidden></div>
            @if(session('success'))<div class="alert-success" role="status">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="validation-errors" role="alert">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="validation-errors" role="alert"><h2>Quelques informations sont à vérifier</h2><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @hasSection('workspace')
                @yield('workspace')
            @else
                <div class="panel-header"><p class="admin-eyebrow">Édition du contenu</p><h1>@yield('title')</h1><p>@yield('intro')</p></div>
                <div class="panel-content">@yield('content')</div>
            @endif
        </div>
        <footer class="admin-workspace-footer"><span>Verre d’Eau Fraîche · Administration</span><a href="{{ route('faq') }}" target="_blank" rel="noopener">Aide et questions fréquentes ↗</a></footer>
    </main>
</div>
<div class="admin-menu-backdrop" data-admin-backdrop hidden></div>
@stack('modals')
<script src="{{ asset('Style1/js/admin-workspace.js') }}" defer></script>
@stack('scripts')
</body>
</html>
