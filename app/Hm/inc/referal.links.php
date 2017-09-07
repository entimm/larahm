<?php

/*
 * This file is part of the entimm/hm.
 *
 * (c) entimm <entimm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

view_assign('site_name', app('data')->settings['site_name']);
  if (app('data')->settings[use_names_in_referral_links] == 1) {
      $userinfo[name] = preg_replace('/\\s+/', '_', $userinfo[name]);
      $userinfo[username] = $userinfo[name];
  }

  view_assign('user', $userinfo);
  view_execute('referal_links.blade.php');
