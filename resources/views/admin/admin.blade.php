@extends('admin.layout')
@section('title', 'Vue d’ensemble')
@section('workspace')
    @include('admin.partials.workspace-panels')
@endsection
@push('modals')
    @include('admin.partials.workspace-modals')
@endpush
