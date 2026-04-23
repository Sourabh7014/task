<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ShortUrlService
{
    public function createShortUrl($originalUrl)
    {
        $user = Auth::user();
        
        if ($user->role === 'SuperAdmin') {
            abort(403, 'SuperAdmin cannot create short URLs.');
        }

        return ShortUrl::create([
            'original_url' => $originalUrl,
            'short_code' => Str::random(6),
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ]);
    }

    public function resolveShortUrl($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();
        $shortUrl->increment('clicks');
        return $shortUrl->original_url;
    }
}
