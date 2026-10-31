<?php

test('untrusted clients cannot forge their address or HTTPS status with forwarded headers', function () {
    config(['trustedproxy.proxies' => []]);
    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
        ->withHeaders(['X-Forwarded-For' => '192.0.2.7', 'X-Forwarded-Proto' => 'https'])
        ->get(route('donation.index', absolute: false))->assertOk();
    expect(request()->ip())->toBe('203.0.113.10')->and(request()->isSecure())->toBeFalse();
});

test('the configured internal reverse proxy preserves the visitor address and HTTPS scheme', function () {
    config(['trustedproxy.proxies' => ['172.30.44.2']]);
    $this->withServerVariables(['REMOTE_ADDR' => '172.30.44.2'])
        ->withHeaders(['X-Forwarded-For' => '192.0.2.7', 'X-Forwarded-Proto' => 'https'])
        ->get(route('donation.index', absolute: false))->assertOk();
    expect(request()->ip())->toBe('192.0.2.7')->and(request()->isSecure())->toBeTrue();
});
