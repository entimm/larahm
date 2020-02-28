<?php

use App\Models\Log;
use App\Models\Order;
use App\Models\PlanGroup;
use App\Exceptions\HmException;
use entimm\LaravelPayeer\Payeer;
use entimm\LaravelAsmoney\Asmoney;
use Illuminate\Filesystem\Filesystem;
use entimm\LaravelPerfectMoney\PerfectMoney;

if (! function_exists('mysql_query')) {
    function mysql_query($query)
    {
        return app('mysql')->query($query);
    }
}

if (! function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result)
    {
        return $result->fetch_array();
    }
}

if (! function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($result)
    {
        return $result->fetch_assoc();
    }
}

if (! function_exists('mysql_insert_id')) {
    function mysql_insert_id()
    {
        return app('mysql')->insert_id;
    }
}

if (! function_exists('mysql_error')) {
    function mysql_error()
    {
        return app('mysql')->error;
    }
}

if (! function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($escapestr)
    {
        return app('mysql')->real_escape_string($escapestr);
    }
}

if (! function_exists('theme_list')) {
    function theme_list()
    {
        $themes = [];
        foreach (glob(dirname(base_path()).'/'.env('TEMPLATES_NAME').'/*') as $file) {
            if (is_dir($file) && ($name = basename($file)) != 'vendor') {
                $themes[] = $name;
            }
        }

        return $themes;
    }
}

if (! function_exists('old_theme')) {
    function old_theme()
    {
        return cache('last_theme');
    }
}

if (! function_exists('theme')) {
    function theme()
    {
        if (! $real_theme = config('hm.real_theme')) {
            $real_theme = $real_theme ?: 'default';
            $theme = env('THEME');
            if (in_array($theme, theme_list())) {
                $real_theme = $theme;
            }
            if ($theme == 'random') {
                $real_theme = array_rand(array_flip(theme_list()));
            }
            if ($theme == 'next') {
                $themes = theme_list();
                if ($oldTheme = old_theme()) {
                    $real_theme = $themes[(array_flip($themes)[$oldTheme] + 1) % count($themes)];
                } else {
                    $real_theme = current($themes);
                }
            }

            config(['hm.real_theme' => $real_theme]);
        }

        return $real_theme;
    }
}

if (! function_exists('refresh_theme')) {
    function refresh_theme()
    {
        foreach (glob(public_path().'/*') as $file) {
            if (strpos($file, 'index.php') !== false) {
                continue;
            }
            unlink($file);
        }

        foreach (glob(dirname(tmpl_path()).'/public/*') as $file) {
            $target = public_path().'/'.basename($file);
            symlink($file, $target);
        }
    }
}

if (! function_exists('tmpl_path')) {
    function tmpl_path()
    {
        return dirname(base_path()).'/'.env('TEMPLATES_NAME').'/'.theme().'/tmpl';
    }
}

if (! function_exists('hanlder_app')) {
    function hanlder_app($app_file)
    {
        ob_start();
        try {
            include $app_file;
        } catch (HmException $e) {
            $httpReturn = $e->resolveResponse();
        }
        $html = ob_get_clean();

        return $httpReturn ?? $html;
    }
}

if (! function_exists('is_production')) {
    function is_production()
    {
        return config('app.env') == 'production';
    }
}

if (! function_exists('view_assign')) {
    function view_assign($key, $value)
    {
        app('view_data')->put($key, $value);
    }
}

if (! function_exists('view_execute')) {
    function view_execute($view)
    {
        $view_data = [
            'tag' => crc32(theme()),
            'csrf_token' => csrf_token(),
            'app_name' => env('APP_NAME'),
            'app_full_name' => env('APP_FULL_NAME'),
            'app_site' => env('APP_SITE'),
            'app_url' => env('APP_URL'),
        ];
        $view_data = array_merge($view_data, app('view_data')->all());

        app('smarty')->assign($view_data);
        $html = app('smarty')->fetch($view);
        echo config('hm.auto_blade') ? blade_string($html, $view_data) : $html;
    }
}

if (! function_exists('blade')) {
    function blade($view, $data = [])
    {
        echo view($view, $data)->render();
    }
}

if (! function_exists('blade_string')) {
    function blade_string($string, $data = [])
    {
        $name = sha1($string);
        $filesystem = app(Filesystem::class);
        if (! view()->exists("_{$name}")) {
            $filesystem->put(config('hm.blade_path')."/_{$name}.blade.php", $string);
        }

        return view("_{$name}", $data)->render();
    }
}

if (! function_exists('smarty_blade_block')) {
    function smarty_blade_block($params, $content, $smarty, &$repeat, $template)
    {
        if (! $repeat && $content) {
            return blade_string($content, $params);
        }
    }
}

if (! function_exists('send_money_to_perfectmoney')) {
    function send_money_to_perfectmoney($amount, $recipient, $memo)
    {
        $config = psconfig_all('pm');

        Log::create([
            'type' => 'send_money_to_perfectmoney',
            'data' => [
                'gate' => gate(),
                'amount' => $amount,
                'recipient' => $recipient,
                'memo' => $memo,
            ],
        ]);

        $res = (new PerfectMoney($config))->sendMoney($recipient, abs($amount), $memo);

        return $res['payment_batch_num'];
    }
}

if (! function_exists('send_money_to_payeer')) {
    function send_money_to_payeer($amount, $recipient, $memo)
    {
        $config = psconfig_all('pe');

        Log::create([
            'type' => 'send_money_to_payeer',
            'data' => [
                'gate' => gate(),
                'amount' => $amount,
                'recipient' => $recipient,
                'memo' => $memo,
            ],
        ]);

        return (new Payeer($config))->transfer($recipient, abs($amount), $memo);
    }
}

if (! function_exists('send_money_to_bitcoin')) {
    function send_money_to_bitcoin($amount, $recipient, $memo)
    {
        $config = psconfig_all('am');

        Log::create([
            'type' => 'send_money_to_bitcoin',
            'data' => [
                'gate' => gate(),
                'amount' => $amount,
                'recipient' => $recipient,
                'memo' => $memo,
            ],
        ]);

        return (new Asmoney($config))->transferBTC($recipient, abs($amount), $memo);
    }
}

if (! function_exists('generate_id')) {
    function generate_id()
    {
        $userId = auth()->id();
        $gateNum = gate() == 'low' ? 1 : 2;

        $id = implode('', [
            $gateNum,
            mt_rand(10, 99),
            substr(time(), 3),
            str_pad($userId % 100, 3, 0, STR_PAD_LEFT),
            mt_rand(100, 999),
        ]);

        return $id;
    }
}

if (! function_exists('add_deposit_order')) {
    function add_deposit_order($amount, $ps, $data)
    {
        $orderNo = generate_id();
        Order::create([
            'order_no' => $orderNo,
            'amount' => $amount * 100,
            'user_id' => auth()->id(),
            'data' => $data,
            'ps' => $ps,
            'type' => Order::TYPE_DEPOSIT,
            'status' => Order::STATUS_START,
        ]);

        return $orderNo;
    }
}

if (! function_exists('psconfig_all')) {
    function psconfig_all($ps, $gate = '')
    {
        $gate = $gate ?: gate();

        return array_merge(config('ps')['common'][$ps], config('ps')[$gate][$ps]);
    }
}

if (! function_exists('psconfig')) {
    function psconfig($key, $gate = '')
    {
        list($ps, $key) = explode('.', $key, 2);

        $gate = $gate ?: gate();

        return config('ps')[$gate][$ps][$key] ?? config('ps')['common'][$ps][$key];
    }
}

if (! function_exists('plan_group_list')) {
    function plan_group_all()
    {
        $groups = gate() == 'low' ? [0, 1] : [0, 2];

        return PlanGroup::whereIn('group', $groups)->with(['plans'])->get()->transform(function ($item) {
            return [
                'days' => $item->q_days,
                'period' => $item->period(),
                'percent' => $item->plans->max('percent'),
                'min' => $item->plans->min('min_deposit'),
                'max' => $item->plans->max('max_deposit'),
            ];
        })->sortBy('percent');
    }
}

if (! function_exists('gate')) {
    function gate()
    {
        $key = env('FORCE_GATE') ?: (app('data')->identity > 0 ? 'low' : 'high');

        return $key;
    }
}
