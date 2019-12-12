<?php

namespace App\Http\Middleware;

class VerifyAnyCsrfToken extends VerifyCsrfToken
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    protected function isReading($request)
    {
        return in_array($request->method(), ['HEAD', 'OPTIONS']);
    }

}
