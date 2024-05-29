<?php



if (! function_exists('input')) {
    /**
     * @param mixed|null $key
     * @param mixed|null $default
     * @return mixed|null
     */
    function input($key = null, $default = null)
    {
        /*
        $requestData = $_REQUEST;

        // raw post
        $input = file_get_contents('php://input');
        $jsonData = json_decode($input, true);
        $jsonData = $jsonData ?: [];

        $mergedData = array_merge($requestData, $jsonData);

        if ($key) {
            return isset($mergedData[$key]) ? $mergedData[$key] : $default;
        }

        return $mergedData;
        */

        $request = App\Helpers\RequestHelper::getGlobalRequest();

        $queryParams = $request->query->all();

        $postParams = $request->request->all();

        $jsonData = json_decode($request->getContent(), true);

        if ($jsonData === null && $request->isMethod('POST')) {
            $jsonData = [];
        }

        $allParams = array_merge($queryParams, $postParams, $jsonData);

        if ($key) {
            return isset($allParams[$key]) ? $allParams[$key] : $default;
        }

        return $allParams;
    }
}


if (! function_exists('request')) {
    /**
     * get request
     * @return \Symfony\Component\HttpFoundation\Request
     */
    function request()
    {
        return App\Helpers\RequestHelper::getGlobalRequest();
    }
}


if (! function_exists('dd')) {
    /**
     * @param $var
     * @return void
     */
    function dd($var)
    {
        dump($var);
        die(1);
    }
}

if (! function_exists('config')) {
    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    function config($key, $default = null)
    {
        $keys = explode('.', $key);
        $numKeys = count($keys);

        $filename = $keys[0];
        $configKeys = array_slice($keys, 1);

        $configFile = BASE_PATH . '/config/' . $filename . '.php';

        if (! file_exists($configFile)) {
            return $default;
        }

        $config = require $configFile;

        if ($numKeys < 2) {
            return $config;
        }

        $result = $config;
        foreach ($configKeys as $k) {
            if (is_array($result) && isset($result[$k])) {
                $result = $result[$k];
            } else {
                return $default;
            }
        }

        return $result;
    }
}


if (! function_exists('arrayToXml')) {
    function arrayToXml($data, &$xmlData) {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                $sub_node = $xmlData->addChild((string)$key);
                arrayToXml($value, $sub_node);
            } else {
                $xmlData->addChild((string)$key, htmlspecialchars((string)$value));
            }
        }
    }
}


if (! function_exists('responseXML')) {
    function responseXML($data = [])
    {
        header('Content-Type: application/xml');
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');
        arrayToXml($data, $xml);
        return $xml->asXML();
    }
}

if (! function_exists('responseJSON')) {
    /**
     * @param $data
     * @return false|string
     */
    function responseJSON($data = [])
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }
}


if (! function_exists('response')) {
    /**
     * @param $content
     * @param $httpStatusCode
     * @param $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function response($content, $httpStatusCode = 200, $headers = [])
    {
        return \App\Helpers\ResponseHelper::getResponse($content, $httpStatusCode, $headers);
    }
}


if (! function_exists('success')) {
    function success($data = [], $message = 'success', $code = 1, $responseType = 'json')
    {
        if ($responseType === 'json') {

            return response(json_encode([
                'returnResult' => $data,
                'returnMsg' => $message,
                'returnCode' => $code
            ]), 200, ['Content-Type' => 'application/json']);
            /*
            return  responseJSON([
                'returnResult' => $data,
                'returnMsg' => $message,
                'returnCode' => $code
            ]);
            */

        }

        $content =  responseXML([
            'returnResult' => $data,
            'returnMsg' => $message
        ]);

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}


if (! function_exists('errors')) {
    function errors($message = 'An exception occurred. Please try again later.', $responseType = 'json')
    {
        if ($responseType === 'json') {
            return response([
                'returnMsg' => $message,
                'returnCode' => 0
            ], 200, ['Content-Type' => 'application/json']);
        }

        $content = responseXML([
            'returnMsg' => $message
        ]);

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}

if (! function_exists('cache')) {
    /**
     * @return \Illuminate\Cache\Repository
     */
    function cache()
    {
        static $cache;
        if ($cache) {
            return $cache;
        }
        $cache = (new \App\Tools\Cache())->getCache();
        return $cache;
    }
}

if (! function_exists('record_logs')) {
    function record_logs($info, $debug = 'info')
    {
        $path = BASE_PATH . '/storage/log';

        if (! is_dir($path) || ! file_exists($path)) {

            if (! mkdir($path, 0755, true) && !is_dir($path)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
            }
        }

        $logfile = $path . '/' . date('Y-m-d') . "-{$debug}.log";

        $logEntry = date("H:i:s") . " - " . $info . PHP_EOL;

        file_put_contents($logfile, $logEntry, FILE_APPEND);
    }
}


if (! function_exists('view')) {
    /**
     * @param $path
     * @param $assign
     * @return string
     */
    function view($path, $assign = [])
    {
        try {
            static $template;
            if ($template) {
                return $template->render($path, $assign);
            }

            $template = \App\Tools\Template::getTemplate();
            return $template->render($path, $assign);

        } catch (\Twig\Error\LoaderError $e) {
            throw new \RuntimeException(sprintf('Template error "%s" ', $e->getMessage()));

        }catch (\Twig\Error\RuntimeError $e) {
            throw new \RuntimeException(sprintf('Template error "%s" ', $e->getMessage()));

        }catch (\Twig\Error\SyntaxError $e) {
            throw new \RuntimeException(sprintf('Template error "%s" ', $e->getMessage()));
        }
    }
}

if (! function_exists('split_url')) {
    /**
     * @param $url
     * @return array
     */
    function split_url($url) {
        $parts = parse_url($url);
        $protocol = isset($parts['scheme']) ? $parts['scheme'] : '';
        $domain = isset($parts['host']) ? $parts['host'] : '';
        if (isset($parts['path'])) {
            $domain .= $parts['path'];
        }
        return [$protocol, $domain];
    }
}

if (! function_exists('chmod_recursive')) {
    function chmod_recursive($dir_path) {
        $files = scandir($dir_path);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $file_path = $dir_path . '/' . $file;

            if (is_dir($file_path)) {
                chmod($file_path, 0777);
                chmod_recursive($file_path);
            } else {
                chmod($file_path, 0777);
            }
        }
    }
}


if (! function_exists('addFilesToZip')) {
    function addFilesToZip($dir, $zip, $zipPath = '') {
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file !== "." && $file !== "..") {
                        if (is_dir($dir . $file)) {
                            $zip->addEmptyDir($zipPath . $file);
                            addFilesToZip($dir . $file . '/', $zip, $zipPath . $file . '/');
                        } else {
                            $zip->addFile($dir . $file, $zipPath . $file);
                        }
                    }
                }
                closedir($dh);
            }
        }
    }
}

if (! function_exists('delete_directory')) {
    function delete_directory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}

if (! function_exists('upload_file_exists')) {
    function upload_file_exists($fileKey){
        if (isset($_FILES[$fileKey])) {
            return $_FILES[$fileKey]['name'];
        }

        return false;
    }
}

if (! function_exists('upload_to')) {
    /**
     * @param $fileKey
     * @param $saveName
     * @return bool
     */
    function upload_to($fileKey, $saveName = '')
    {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$fileKey];
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];

            $upload = BASE_PATH . '/storage/uploads/';
            if (!is_dir($upload) && !mkdir($upload, 0755, true) && !is_dir($upload)) {
                return false;
            }

            if (! empty($saveName)) {
                $name = $saveName;
            }
            $move_file = $upload . $name;
            move_uploaded_file($tmp_name, $move_file);

            if (file_exists($move_file)) {
                return $move_file;
            }

            return false;
        }

        return false;
    }
}

if (! function_exists('build_tree')) {
    /**
     * @param array $data
     * @param $parentId
     * @return array
     */
    function build_tree(array $data, $parentId = 0)
    {
        $tree = [];
        foreach ($data as $item) {
            if ($item['pid'] == $parentId) {
                $children = build_tree($data, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }
}

if (! function_exists('copy_directory')) {
    /**
     * @param $source
     * @param $destination
     * @param $overwrite
     * @return void
     */
    function copy_directory($source, $destination, $overwrite = false) {
        $dir = opendir($source);

        if (! is_dir($destination) && ! mkdir($destination, 0777, true) && ! is_dir($destination)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $destination));
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source . '/' . $file)) {
                    copy_directory($source . '/' . $file, $destination . '/' . $file, $overwrite);
                } else {
                    if (!file_exists($destination . '/' . $file) || $overwrite) {
                        copy($source . '/' . $file, $destination . '/' . $file);
                    }
                }
            }
        }

        closedir($dir);
    }
}


if (! function_exists('stdclass2array')) {
    /**
     * @param $data
     * @return array
     */
    function stdclass2array($data) {
        return array_map(static function ($item) {
            return json_decode(json_encode($item), true);
        }, $data);
    }
}


if (! function_exists('get_request_full_path')) {
    function get_request_full_path()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = $_SERVER['REQUEST_URI'];
        return $protocol . "://" . $host . $path;
    }
}