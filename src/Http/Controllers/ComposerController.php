<?php
/**
 * Copyright (c) 2017. All rights Reserved BtyBug TEAM
 */

namespace Btybug\Uploads\Http\Controllers;


use Btybug\Uploads\Repository\Plugins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\Console\Tests\Input\StringInput;


class ComposerController extends Controller
{
    protected $path;

    public function __construct()
    {
        $this->path = config('avatar.pluginsDir');
    }

    public function getIndex(Request $request)
    {
        $plugin = $request->get('p');
        $path = $this->path;
        return view('uploads::Composer.index', compact('plugin', 'path'));
    }


    public function getStatus()
    {
        $output = array(
            'composer' => file_exists(__DIR__ . '/../../../composer.phar'),
            'composer_extracted' => file_exists(__DIR__ . '/../../../composer/extracted'),
            'installer' => file_exists(__DIR__ . '/../../../installer.php'),
        );
        return \Response::json($output);

    }

    public function getMain(Request $request)
    {
        $function = $request->get('function');
        return call_user_func_array([$this, $function], $request->all());
    }

    public function downloadComposer()
    {
        $installerURL = 'https://getcomposer.org/installer';
        $installerFile = __DIR__ . '/../../../installer.php';
        putenv('COMPOSER_HOME=' . __DIR__ . '/../../../composer/extracted/bin/composer');
        if (!file_exists($installerFile)) {

            echo 'Downloading ' . $installerURL . PHP_EOL;
            flush();
            $ch = curl_init($installerURL);
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/../../../composer/cacert.pem');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_FILE, fopen($installerFile, 'w+'));
            if (curl_exec($ch)) {

                return 'Success downloading ' . $installerURL . PHP_EOL;

            } else {
                return 'Error downloading ' . $installerURL . PHP_EOL;
            }
            flush();
        }
        echo 'Installer found : ' . $installerFile . PHP_EOL . '\r\n' . 'Starting installation...' . PHP_EOL;
        flush();
        $argv = array();
        include $installerFile;
        flush();
    }

    public function command($path, $package, $command)
    {
        $path = str_replace('\\', '\\\\', $path);
        command:
        set_time_limit(-1);
        ini_set('memory_limit', '2048M');
        putenv('COMPOSER_HOME=' . __DIR__ . '/../../../extracted/bin/composer');
        if (!file_exists($_POST['path'])) {

            echo $_POST['path'];
            die();
        }
        if (file_exists(__DIR__ . '/../../../composer/extracted')) {
            require_once(__DIR__ . '/../../../composer/extracted/extracted/vendor/autoload.php');
            $input = new \Symfony\Component\Console\Input\StringInput($command . ' ' . $package . ' -vvv -d ' . $path);
            $output = new \Symfony\Component\Console\Output\StreamOutput(fopen('php://output', 'w'));
            $app = new \Composer\Console\Application();
            $app->run($input, $output);
        } else {
            echo 'Composer not extracted.';
            $this->extractComposer();
            goto command;
        }
    }

    public function extractComposer()
    {
        if (file_exists(__DIR__ . '/../../../composer.phar')) {
            echo 'Extracting composer.phar ...' . PHP_EOL;
            flush();
            $composer = new Phar(__DIR__ . '/../../../composer.phar');
            $composer->extractTo(__DIR__ . '/../../../composer/extracted');
            return 'Extraction complete.' . PHP_EOL;
        }
        return 'composer.phar does not exist';
    }

    public function getOnOff(Request $request)
    {
        $plugin = new Plugins();
        return \Response::json(['error' => !$plugin->onOff($request->all())]);
    }
}