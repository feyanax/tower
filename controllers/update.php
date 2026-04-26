<?
auth('normal');

set_time_limit(0);

define('REMOTE_URL', 'https://simroulette.com/tower/distributor.php');
define('TARGET_DIR', __DIR__ . '/download');

$lang  = (isset($_GET['lang']) && $_GET['lang'] === 'en') ? 'en' : 'ru';
$reset = !empty($_GET['reset']);

$t = array(
  'ru' => array(
    'title' => 'Загрузка обновления…',
    'ready' => 'Готово. Все файлы загружены.',
    'init_fail' => 'Инициализация не удалась',
    'downloaded' => 'Загружено',
    'retry' => 'Повтор через мгновение…',
    'cant_create_dir' => 'Не удалось создать директорию',
    'see_log' => 'Смотри лог',
  ),
  'en' => array(
    'title' => 'Downloading update…',
    'ready' => 'Done. All files downloaded.',
    'init_fail' => 'Initialization failed',
    'downloaded' => 'Downloaded',
    'retry' => 'Retrying shortly…',
    'cant_create_dir' => 'Failed to create directory',
    'see_log' => 'See log',
  ),
);

$stateFile = rtrim(TARGET_DIR, '/\\') . '/.sync_state.json';
$logFile   = rtrim(TARGET_DIR, '/\\') . '/.fetcher.log';

/* ---------- Лог и ловушка фаталов ---------- */
function logMsg($msg){
    global $logFile;
    if (!is_dir(dirname($logFile))) @mkdir(dirname($logFile), 0777, true);
    @file_put_contents($logFile, '['.date('Y-m-d H:i:s').'] '.$msg.PHP_EOL, FILE_APPEND);
}
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new Exception("PHP error[$errno]: $errstr at $errfile:$errline");
});
register_shutdown_function(function(){
    $e = error_get_last();
    if ($e && in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))) {
        logMsg('FATAL: '.$e['message'].' at '.$e['file'].':'.$e['line']);
        if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><meta charset="utf-8"><div style="font-family:system-ui;max-width:640px;margin:40px auto;color:#b00020;font-size:75%">';
        echo '<h3>Fatal:</h3><pre style="white-space:pre-wrap">'.htmlspecialchars($e['message'].' at '.$e['file'].':'.$e['line'], ENT_QUOTES, 'UTF-8').'</pre>';
        echo '</div>';
    }
});

/* ---------- Утилиты ---------- */
function ensureDir($dir){
    if (is_dir($dir)) return true;
    return @mkdir($dir, 0777, true);
}
function loadState($file){
    if (!is_file($file)) return array();
    $s = @file_get_contents($file);
    if ($s === false) return array();
    $j = json_decode($s, true);
    return is_array($j) ? $j : array();
}
function saveState($file, $data){
    $tmp = $file.'.tmp';
    @file_put_contents($tmp, json_encode($data));
    @rename($tmp, $file);
}
function httpGetString($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Fetcher/1.0');
    $res = curl_exec($ch);
    if ($res === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception('cURL GET error: '.$err);
    }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code < 200 || $code >= 300) {
        throw new Exception('HTTP '.$code.' for '.$url);
    }
    return $res;
}
function downloadOne($relPath, $md5Expected, $sizeExpected){
    $remote = REMOTE_URL . (strpos(REMOTE_URL,'?')!==false ? '&' : '?') . 'action=get&path=' . rawurlencode($relPath);
    $dst = rtrim(TARGET_DIR,'/\\') . '/' . str_replace('\\','/',$relPath);
    $dir = dirname($dst);
    if (!is_dir($dir) && !ensureDir($dir)) {
        throw new Exception('mkdir failed: '.$dir);
    }
    $existing = is_file($dst) ? filesize($dst) : 0;

    // если уже корректно скачан — пропустить
    if (is_file($dst) && $md5Expected && @md5_file($dst) === $md5Expected) {
        return;
    }

    $fp = fopen($dst, $existing>0 ? 'ab' : 'wb');
    if (!$fp) throw new Exception('open failed: '.$dst);

    $ch = curl_init($remote);
    $headers = array();
    if ($existing > 0) $headers[] = 'Range: bytes='.$existing.'-';
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Fetcher/1.0');
    if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $ok = curl_exec($ch);
    if ($ok === false) {
        $err = curl_error($ch);
        curl_close($ch);
        fclose($fp);
        throw new Exception('cURL download error: '.$err);
    }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);

    if ($code!=200 && $code!=206) {
        throw new Exception('HTTP '.$code.' on '.$relPath);
    }
    if ($sizeExpected !== null && filesize($dst) != $sizeExpected) {
        throw new Exception('size mismatch for '.$relPath.' (expect '.$sizeExpected.', got '.filesize($dst).')');
    }
    if ($md5Expected) {
        $md5 = @md5_file($dst);
        if ($md5 !== $md5Expected) {
            throw new Exception('md5 mismatch for '.$relPath.' (expect '.$md5Expected.', got '.$md5.')');
        }
    }
}

/* хэш манифеста для авто-обновления при изменениях на раздачике */
function manifest_hash($files){
    // Достаточно стабильного хэша по ключевым полям
    $acc = '';
    foreach ($files as $f){
        $p = isset($f['path']) ? $f['path'] : '';
        $s = isset($f['size']) ? $f['size'] : '';
        $m = isset($f['md5'])  ? $f['md5']  : '';
        $acc .= $p.'|'.$s.'|'.$m."\n";
    }
    return md5($acc);
}

function render($pct, $msg, $auto){
    echo '<!doctype html><html lang="ru"><meta charset="utf-8"><title>'.htmlspecialchars($msg,ENT_QUOTES,'UTF-8').'</title>';
    echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:40px;background:#f7f7f7;color:#111;font-size:75%}
    .box{max-width:640px;margin:0 auto}
    h1{font-size:16px;margin:0 0 12px}
    .bar{height:18px;background:#e5e5e5;border-radius:10px;overflow:hidden;box-shadow:inset 0 1px 3px rgba(0,0,0,.08)}
    .fill{height:100%;width:'.intval($pct).'%;background:#4caf50;transition:width .2s}
    .caption{margin-top:8px;font-size:12px;color:#555;word-break:break-all}
    .err{color:#b00020;white-space:pre-wrap}
    </style><body><div class="box">';
    echo '<h1>'.$msg.' '.intval($pct).'%</h1>';
    echo '<div class="bar"><div class="fill"></div></div>';
    echo '<div class="caption" id="cap"></div>';
    echo '</div>';
    if ($auto) {
        echo '<script>setTimeout(function(){location.reload();},80);</script>';
    }
    echo '</body></html>';
}

/* ---------- Исполнение ---------- */

// ensure target
if (!ensureDir(TARGET_DIR)) {
    if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
    echo '<h3>'.htmlspecialchars($t[$lang]['cant_create_dir'],ENT_QUOTES,'UTF-8').': '.htmlspecialchars(TARGET_DIR,ENT_QUOTES,'UTF-8').'</h3>';
    exit;
}

/* reset по запросу */
if ($reset && is_file($stateFile)) {
    @unlink($stateFile);
    logMsg('manual reset: state cleared');
}

try {
    // state
    $state = loadState($stateFile);

    // --- каждый тик — получаем свежий манифест и сверяем хэш ---
    $manifestJson = httpGetString(REMOTE_URL.(strpos(REMOTE_URL,'?')!==false ? '&':'?').'action=list');
    $manifest = json_decode($manifestJson, true);
    if (!is_array($manifest) || !isset($manifest['files']) || !is_array($manifest['files'])) {
        throw new Exception('bad manifest');
    }
    $files = $manifest['files'];
    usort($files, function($a,$b){ return strcmp($a['path'],$b['path']); });
    $mh = manifest_hash($files);

    $needInit = (
        !isset($state['files']) || !is_array($state['files']) ||
        !isset($state['remote']) || $state['remote'] !== REMOTE_URL ||
        !isset($state['manifest_hash']) || $state['manifest_hash'] !== $mh
    );

    if ($needInit) {
        // если список изменился — начать заново
        $state = array(
            'remote'        => REMOTE_URL,
            'files'         => $files,
            'manifest_hash' => $mh,
            'index'         => 0,
            'total'         => count($files),
        );
        saveState($stateFile, $state);
        logMsg('init/refresh: total='.$state['total'].' hash='.$mh);
    }

    // all done?
    if ($state['index'] >= $state['total']) {
        $messageFile = rtrim(TARGET_DIR,'/\\').'/message.html';
        if (is_file($messageFile)) {
            if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
            readfile($messageFile); // чистый вывод HTML
        } else {
            if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
            echo '<h3 style="font-family:system-ui;font-size:14px">'.$t[$lang]['ready'].'</h3>';
        }
        exit;
    }

    // next file
    $idx = intval($state['index']);
    $cur = $state['files'][$idx];
    $rel = $cur['path'];
    $md5 = isset($cur['md5']) ? $cur['md5'] : null;
    $size = isset($cur['size']) ? intval($cur['size']) : null;

    try {
        downloadOne($rel, $md5, $size);
        $state['index'] = $idx + 1;
        saveState($stateFile, $state);
        logMsg('ok: '.$rel);
        $pct = $state['total']>0 ? floor($state['index']*100/$state['total']) : 0;
        $msg = ($lang==='en'?$t['en']['title']:$t['ru']['title']).' — '.($lang==='en'?$t['en']['downloaded']:$t['ru']['downloaded']).': '.$rel;
        render($pct, $msg, true);
        exit;

    } catch (Exception $e) {
        logMsg('err: '.$rel.' :: '.$e->getMessage());
        $pct = $state['total']>0 ? floor($state['index']*100/$state['total']) : 0;
        $msg = ($lang==='en'?$t['en']['retry']:$t['ru']['retry']).' '.$rel."\n".$e->getMessage()."\n".$t[$lang]['see_log'].': '.$logFile;
        if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><meta charset="utf-8">';
        echo '<div style="font-family:system-ui;max-width:640px;margin:40px auto;font-size:75%">';
        echo '<h1 style="font-size:16px;margin:0 0 12px">'.htmlspecialchars($t[$lang]['title'].' '.$pct.'%',ENT_QUOTES,'UTF-8').'</h1>';
        echo '<div style="height:18px;background:#e5e5e5;border-radius:10px;overflow:hidden"><div style="height:18px;background:#4caf50;width:'.intval($pct).'%;"></div></div>';
        echo '<pre class="err" style="color:#b00020;white-space:pre-wrap;margin-top:8px">'.htmlspecialchars($msg,ENT_QUOTES,'UTF-8').'</pre>';
        echo '<script>setTimeout(function(){location.reload();},800);</script>';
        echo '</div>';
        exit;
    }

} catch (Exception $e) {
    logMsg('fatal: '.$e->getMessage());
    if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
    $msg = $t[$lang]['init_fail'].":\n".$e->getMessage()."\n".$t[$lang]['see_log'].': '.$logFile;
    echo '<!doctype html><meta charset="utf-8">';
    echo '<div style="font-family:system-ui;max-width:640px;margin:40px auto;font-size:75%">';
    echo '<h3>'.htmlspecialchars($t[$lang]['init_fail'],ENT_QUOTES,'UTF-8').'</h3>';
    echo '<pre class="err" style="color:#b00020;white-space:pre-wrap">'.htmlspecialchars($msg,ENT_QUOTES,'UTF-8').'</pre>';
    echo '<script>setTimeout(function(){location.reload();},1500);</script>';
    echo '</div>';
    exit;
}
