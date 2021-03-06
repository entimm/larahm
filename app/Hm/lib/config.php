<?php

/*
 * This file is part of the entimm/hm.
 *
 * (c) entimm <entimm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

ini_set('error_reporting', 'E_ALL & ~E_NOTICE & ~E_DEPRECATED');

app('data')->frm = request()->toArray();

app('data')->env = array_merge($_ENV, $_SERVER);
app('data')->env['HTTP_HOST'] = preg_replace('/^www\./', '', app('data')->env['HTTP_HOST']);

app('data')->time = Carbon::now();

$referer = isset(app('data')->env['HTTP_REFERER']) ? app('data')->env['HTTP_REFERER'] : null;
$host = app('data')->env['HTTP_HOST'];
if (! strpos($referer, '//'.$host)) {
    Cookie::queue('came_from', $referer, 43200);
}

app('data')->exchange_systems = config('hm.payments');
app('data')->settings = get_settings();

app('data')->settings['site_url'] = (is_SSL() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
