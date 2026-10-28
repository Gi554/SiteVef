<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
@yield('title', 'Administration') — VEF</title>
<link rel="icon" href="{{ asset('Style1/images/logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('Style1/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('Style1/css/editorial-admin.css') }}">
<link rel="stylesheet" href="{{ asset('Style1/css/project-status.css') }}">
</head>
<body>
<div class="admin-container">
<aside class="admin-sidebar">
<div class="sidebar-header">
<img src="{{ asset('Style1/images/logo.png') }}" alt="VEF" width="64">
<p>Administration</p>
</div>
<nav class="sidebar-nav" aria-label="Administration">
<ul>
@foreach(['dashboard'=>'Tableau de bord','projets'=>'Projets','actualites'=>'Actualités','dons'=>'Dons','membres'=>'Notre équipe','temoignages'=>'Témoignages','messages'=>'Messages','candidatures'=>'Candidatures'] as $panel=>$label)<li>
<a class="nav-item" href="{{ route('admin.projets.index') }}#{{ $panel }}">{{ $label }}</a>
</li>
@endforeach
<li><a href="{{ route('admin.parrainage.edit') }}" class="nav-item {{ request()->routeIs('admin.parrainage.*') ? 'active' : '' }}" @if(request()->routeIs('admin.parrainage.*')) aria-current="page" @endif>Parrainage</a></li>
</ul>
</nav>
<div class="sidebar-footer">
<a href="{{ route('index') }}">← Retour au site</a>
<form method="post" action="{{ route('logout') }}">
@csrf<button class="btn btn-secondary">Déconnexion</button>
</form>
</div>
</aside>
<main class="admin-main">
<div class="panel-header">
<h1>
@yield('title')</h1>
<p>
@yield('intro')</p>
</div>
<div class="panel-content">
@if(session('success'))<div class="alert-success" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())<div class="validation-errors" role="alert">
<h2>Vérifiez les champs suivants</h2>
<ul>
@foreach($errors->all() as $error)<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
@yield('content')</div>
</main>
</div>
</body>
</html>
