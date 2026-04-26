<?php
 function page_header($in, $js = '', $nomenu = 0) { goto N0OQx; N0OQx: global $sv_login, $sv_user_id, $lang, $ll, $langList, $rewrite, $sv_user_level, $search; goto wQhum; AJGOV: if ($sv_user_id) { ?><img src="/img/logo-w.svg"><?php  } goto dKn8M; DC7O6: if (!$nomenu) { foreach ($menu2 as $data) { goto pM0qt; Ynsn3: if (strpos('/' . $rewrite, $a[2]) !== false) { goto FpBTL; FpBTL: $c1 = ' class="active"'; goto fwNLa; fwNLa: $c2 = ' active'; goto Rht9M; Rht9M: $c11 = ' style="color: #fff"'; goto Ey6bQ; Ey6bQ: } else { goto ipTmk; nn0Cf: $c11 = ''; goto s5fSc; s5fSc: $c22 = ''; goto N38U4; W80SJ: $c2 = ''; goto nn0Cf; ipTmk: $c1 = ''; goto W80SJ; N38U4: } goto qnVmA; pM0qt: $a = explode(';', $data); goto Ynsn3; qnVmA: if ($a[0] != 'sub') { echo '<li' . $c1 . '><a href="' . lang2url($a[2]) . '"' . $c11 . ' class="no-wrap">' . str_replace('_', '&nbsp;', $a[1]) . '</a></li>'; } else { echo '<li class="submenu' . $c2 . '"><a href="' . lang2url($a[2]) . '"' . $c11 . ' class="no-wrap">' . str_replace('_', '&nbsp;', $a[1]) . '</a></li>'; } goto Wqgjg; Wqgjg: } echo '<li class="point">•</li>'; } goto DzxbT; vRW8I: if ($sv_user_id) { ?><img src="/img/logo-w.svg"><?php  } goto G9IAY; mvWLh: for ($i = 0; $i < cnt($menu); $i++) { $b = explode(';', $menu[$i]); $menu[$i] = explode(',', $menu[$i]); $menu[$i] = $menu[$i][0]; $b = explode(',', $b[count($b) - 1]); foreach ($b as $data) { if (strpos($rewrite, trim($data, '/')) !== false) { $menu[$i] .= ';active'; } } } goto DPNS1; DzxbT: if ($sv_user_id) { echo '<li style="white-space: nowrap;"><a href="javascript:void(0)" class="to-lang"><img src="/icons/' . $ll . '.svg" style="height: 10px;"></a> &nbsp; <a href="/profile" style="margin-right: 10px;">' . $sv_login . '</a><a href="' . $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'mode=logout"><img src="/icons/exit.svg" style="position: absolute; width: 20px; margin-top:1px;"><span id="exit" style="margin-left: 17px;"> ' . $lang['auth_exit'] . '</span></a></li>'; } else { echo '<li style="white-space: nowrap;"><a href="javascript:void(0)" class="to-login">' . $lang['auth_entry'] . '</a> &nbsp; <a href="javascript:void(0)" class="to-lang"><img src="/icons/' . $ll . '.svg" style="height: 10px;"></a></li>'; } goto uq0T2; XlLAq: ?>'"><?php  goto vRW8I; kmUf7: ?></title>
    <link rel="shortcut icon" href="/img/favicon.svg"/>
    <link rel="stylesheet" href="/css/_styles.css">
    <link rel="stylesheet" href="/css/_bootstrap.css">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script src="/js/jquery-3.6.4.min.js"></script>
    <script src="/js/_login.js" defer></script>
    <script src="/js/_logic.js"></script>
<?php  goto gOTMZ; DPNS1: $menu2 = explode('
', $lang['menu2']); goto LOOhZ; Y7oJZ: echo $in['title']; goto kmUf7; HBDYI: ?>	
</div>

<?php  goto VYovY; UDZbA: if ($sv_user_id) { echo '<li><a href="/profile" style="margin-right: 10px;">' . $sv_login . '</a><a href="' . $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'mode=logout"><img src="/icons/exit_b.svg" class="icon-exit"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $lang['auth_exit'] . '</span></a></li>'; } else { echo '<li><a href="javascript:void(0)" class="to-login">' . $lang['auth_entry'] . '</a></li>'; } goto NgtW4; dKn8M: ?></div>
                <div id="logo-sm" class="c-pointer" onclick="document.location.href='<?php  goto gZ4YZ; G9IAY: ?></div>
                <nav id="menu">
                    <ul>
<?php  goto DC7O6; VUfrA: ?>

    <div id="panelTop">
        <aside class="panelTopLeft" style="display: flex; align-items: center;">
            <button id="burger-menu">☰</button>
        </aside>
        <main id="panelHeader">
            <div class="content-wrapper">
                <div id="logo" class="c-pointer" onclick="document.location.href='<?php  goto ixyz9; S4yfA: ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php  goto Y7oJZ; txNDM: ?>
    <script src="https://yastatic.net/share2/share.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

<div class="overlay" id="overlay"></div>

<div class="modal login-modal" id="langModal">
<?php  goto iLmbX; gZ4YZ: echo lang2url('/'); goto XlLAq; uq0T2: ?>
                    </ul>
                </nav>
            </div>
        </main>
        <aside class="panelTopRight"></aside>
    </div>

    <div class="main-content">
        <aside id="panelLeft" class="hidden scrollable">
            <nav class="side-menu">
<?php  goto lgB13; NgtW4: ?>
                </ul>
            </nav>

            <div class="side-content" style="margin-top: -20px;">
		

	    </div>
<?php  goto ik7Za; lgB13: if (!$nomenu) { goto nX1Ha; NyRBq: foreach ($menu as $data) { goto dejJf; rZr05: if (!$c1 || !cnt($in['menu'])) { if ($c2) { echo '<li' . $c1 . '><a href="' . lang2url($a[2]) . '" class="link">' . $a[1] . '</a></li>'; } else { echo '<li' . $c1 . '><a href="' . lang2url($a[2]) . '"' . $c11 . '>' . $a[1] . '</a></li>'; } } else { if ($c2) { goto gtYIv; iPDFm: echo '</li>'; goto ta6Vy; bZveH: foreach ($in['menu'] as $data) { echo '<div class="submenu"><a href="' . $data['url'] . '">' . $data['title'] . '</a></div>'; } goto iPDFm; gtYIv: echo '<li class="submenu' . $c2 . '">' . $a[1]; goto bZveH; ta6Vy: } else { echo '<li class="submenu' . $c2 . '"><a href="' . lang2url($a[2]) . '"' . $c11 . '>' . $a[1] . '</a></li>'; } } goto wHQGZ; yATIf: if ($a[3]) { goto HV6Ob; HV6Ob: $c1 = ' class="active"'; goto f_huh; f_huh: $c2 = ' active'; goto ClUf4; ClUf4: $c11 = ' style="color: #fff"'; goto NVTpA; NVTpA: } else { goto xfPcn; WVVl_: $c11 = ''; goto LAhXk; p0sUZ: $c2 = ''; goto WVVl_; LAhXk: $c22 = ''; goto flwt6; xfPcn: $c1 = ''; goto p0sUZ; flwt6: } goto rZr05; dejJf: $a = explode(';', $data); goto yATIf; wHQGZ: } goto n2hXc; nX1Ha: ?>
                <ul>
<?php  goto NyRBq; n2hXc: ?>
                </ul>
            </nav>
<?php  goto kqLvH; kqLvH: } goto D4jPX; Cmjr5: ?>

	<br><br></div>

        </aside>

        <main id="panelContent">
<?php  goto tFrOP; ixyz9: echo lang2url('/'); goto A3CD5; gOTMZ: echo $js; goto txNDM; D4jPX: ?>
	<div class="ext" style="margin-top:-10px;">	

            <nav class="side-menu">
                <ul>
<?php  goto UDZbA; A3CD5: ?>'"><?php  goto AJGOV; VYovY: if (!$sv_user_id) { goto a3pDE; a3pDE: ?>

<div class="modal login-modal" id="loginModal">
    <h2><?php  goto sf0hG; Q1hoW: ?></label>
        <input type="text" id="login" name="login" required>

        <label for="password"><?php  goto nEkKe; vDxFo: ?></h2>
    <form id="loginForm">
        <label for="login"><?php  goto iWJyz; iWJyz: echo $lang['auth_login_or_email']; goto Q1hoW; MqJmP: echo lang2url('/forgot_password'); goto m0Iao; K4K1h: ?></label>
        <input type="password" id="password" name="password" required>
        <div class="password-info">
            <a href="<?php  goto MqJmP; sf0hG: echo $lang['auth_entry']; goto vDxFo; gk21w: ?></a>
        </div>

        <button type="submit" class="btn"><?php  goto m9j3Y; nEkKe: echo $lang['auth_password']; goto K4K1h; kLB3n: ?></button>
    </form>
</div>

<?php  goto F1LdH; eVBWV: echo $lang['auth_forgot_password']; goto gk21w; m0Iao: ?>" class="fs--1"><?php  goto eVBWV; m9j3Y: echo $lang['auth_log_in']; goto kLB3n; F1LdH: } goto VUfrA; wQhum: $menu = explode('
', $lang['menu']); goto mvWLh; iLmbX: foreach ($langList as $l => $language) { echo '<div class="lang"><a href="/' . $l . '"><img src="/icons/' . $l . '.svg" style="height: 20px;"></a><div><a href="/' . $l . '" class="link">' . $language . '</a></div></div>'; } goto HBDYI; LOOhZ: if (!$in['title']) { $in['title'] = $lang['phrase_panel']; } goto S4yfA; ik7Za: foreach ($langList as $l => $language) { echo '<div class="lang"><a href="/' . $l . '"><img src="/icons/' . $l . '.svg" style="height: 20px;position: absolute;"></a><div><a href="/' . $l . '" class="link">' . $language . '</a></div></div>'; } goto Cmjr5; tFrOP: } function page_footer($footer) { goto AVjQ7; Behlt: ?>"><img src="/icons/upW.svg"></button>
</body>
</html>
<?php  goto XFWzf; CNzxl: ?>
        </main>

        <aside id="panelRight" class="scrollable">
           <div class="side-content">
<div align="right">
<?php  goto Kyiw5; SbyUN: ?></p>
    </footer>
<button id="scrollTopBtn" title="<?php  goto NvX59; oSTY0: ?>
</div>
           <div id="about">
<?php  goto qsPTl; u433B: echo ($sv_user_id ? $lang['copyright'] . $vers : '') . '. ' . $ip . '
' . $footer['js']; goto SbyUN; qsPTl: echo $right ? '<br>' . $right : ''; goto IH0Rm; IH0Rm: ?>
           </div>
        </aside>
    </div>

    <footer id="panelFooter">
        <p><?php  goto u433B; NvX59: echo $lang['phrase_up']; goto Behlt; AVjQ7: global $search, $lang, $vers, $right, $sv_user_id, $ip; goto CNzxl; Kyiw5: if ($sv_user_id && file_exists('sysMsg.html')) { echo file_get_contents('sysMsg.html'); } goto oSTY0; XFWzf: }