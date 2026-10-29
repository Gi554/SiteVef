<?php

return [
    // Adresse interne de Caddy en production ; vide sur un accès direct.
    'proxies' => array_values(array_filter(array_map('trim', explode(',', (string) env('TRUSTED_PROXIES', ''))))),
];
