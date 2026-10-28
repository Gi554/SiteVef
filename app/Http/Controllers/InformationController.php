<?php

namespace App\Http\Controllers;

class InformationController extends Controller
{
    public function show(string $page)
    {
        $content = config('public_content.pages.'.$page);
        abort_unless(is_array($content), 404);

        return view('pages.information', compact('content', 'page'));
    }
}
