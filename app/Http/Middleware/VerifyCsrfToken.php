<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
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
        //'http://192.168.20.62:26001/customers/page/*',
        'http://192.168.20.62:26001/customers/insertBySuper8',
        'http://192.168.20.62:26001/customers/updateBySuper8',
        'http://192.168.20.62:26001/customers/insertCrm',
        'http://192.168.20.62:26001/customers/addCrm',
        'http://192.168.20.62:26001/customers/test',
        'http://192.168.20.62:26001/customers/test2',
        'http://192.168.20.62:26001/action/updateCrmByEmployee',
        //'http://192.168.1.186:26001/customers/page/*',
        'http://192.168.1.186:26001/customers/insertBySuper8',
        'http://192.168.1.186:26001/customers/updateBySuper8',
        'http://192.168.1.186:26001/customers/insertCrm',
        'http://192.168.1.186:26001/customers/addCrm',
        'http://192.168.1.186:26001/customers/test2',
    ];
}
