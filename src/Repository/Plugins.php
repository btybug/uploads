<?php
/**
 * Created by PhpStorm.
 * User: menq
 * Date: 7/12/17
 * Time: 9:41 PM
 */

namespace Btybug\Uploads\Repository;

//TODO replace base_path() with plugins_path()
use Btybug\btybug\Models\Templates\Units;

/**
 * Class Plugins
 * @package Avatar\Avatar\Repositories
 */
class Plugins
{
    /**
     * @var mixed
     */
    protected $mainComposer;
    /**
     * @var
     */
    protected $backUp;
    /**
     * @var array
     */
    protected $plugins;
    protected $path;
    protected $dir;
    protected $type;

    /**
     * Plugins constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->plugins();

    }

    public function plugins()
    {
        $this->path = base_path(config('avatar.plugins.path') . DS . 'composer.json');
        $this->dir = config('avatar.plugins.path');
        $this->type = 'plugin';
        $this->separator();
        return $this;
    }
    public function appPlugins()
    {
        $this->path = base_path(config('avatar.plugins.path') . DS . 'composer.json');
        $this->dir = config('avatar.plugins.path');
        $this->type = 'app-plugin';
        $this->separator();
        return $this;
    }

    protected function separator()
    {
        composer:
        if (\File::exists(base_path())) {
            $this->mainComposer = json_decode(\File::get($this->path), true);
            $this->plugins = $this->sortPlugins();
        } else {
            if (\File::makeDirectory(base_path(config('avatar.plugins.path')))) {
                if (\File::put(base_path('composer.json'), '{}')) {
                    goto composer;
                };
            }

            throw new \Exception('qaqa');
        }
    }

    /**
     * @return array
     */
    private function sortPlugins()
    {
        $plugins = $this->mainComposer['require'] ?? [];
        unset($plugins['php']);
        return $plugins;
    }

    public function modules()
    {
        $this->path = base_path('vendor' . DS . 'btybug' . DS . 'btybug' . DS . 'composer.json');
        $this->dir = config('avatar.modules.path');
        $this->type = 'module';
        $this->separator();
        return $this;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function onOff(array $data)
    {
        $result = false;
        switch ($data['action']) {
            case 'on':
                $result = $this->enable($data['namespace']);
                break;
            case 'off':
                $result = $this->disable($data['namespace']);
                break;
        }
        return $result;
    }

    /**
     * @param $pluginPath
     * @return bool
     */
    public function enable($pluginPath)
    {
        $plugins = $this->getInstaleds();
        foreach ($plugins as $key => $plugin) {
            if ($plugin['name'] == $pluginPath) {
//                $plugin=$plugins[$pluginPath];
                $store = $this->getStorage();
                $plugins[$key]['autoload'] = $store[$pluginPath];
                unset($store[$pluginPath]);
                $this->addStorage($store);
                $this->setInstaleds($plugins);
                continue;

            }
        }
        return $this->command('dump-autoload');

    }

    /**
     * @return mixed
     */
    public function getInstaleds()
    {
        if (\File::exists(base_path($this->dir .DS.'vendor'. DS . 'composer' . DS . 'installed.json'))) {
            return json_decode(\File::get(base_path($this->dir .DS.'vendor'. DS . 'composer' . DS . 'installed.json')), true);
        }

    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        if (\File::exists(storage_path('packagis.txt'))) {
            return json_decode(\File::get(storage_path('packagis.txt')), true);
        }
    }

    /**
     * @param array $data
     */
    public function addStorage(array $data)
    {
        \File::put(storage_path('packagis.txt'), json_encode($data, true));
    }

    /**
     * @param $data
     * @return int
     */
    public function setInstaleds($data)
    {
        if (\File::exists(base_path($this->dir.DS.'vendor' . DS . 'composer' . DS . 'installed.json'))) {
            return \File::put(base_path($this->dir.DS.'vendor' . DS . 'composer' . DS . 'installed.json'), json_encode($data, true));
        }

    }

    /**
     * @param $command
     * @return bool
     */
    public function command($command)
    {
        $path = str_replace('\\', '\\\\', base_path());
        set_time_limit(-1);
        putenv('COMPOSER_HOME=' . __DIR__ . '/../../extracted/bin/composer');
        if (!file_exists($path)) {
            return false;
        }
        if (file_exists(__DIR__ . '/../../composer/extracted')) {
            require_once(__DIR__ . '/../../composer/extracted/extracted/vendor/autoload.php');
            $input = new \Symfony\Component\Console\Input\StringInput($command . ' -vvv -d ' . $path);
            $output = new \Symfony\Component\Console\Output\StreamOutput(fopen('php://output', 'w'));
            $app = new \Composer\Console\Application();
            $app->run($input, $output);
            return true;
        }
        return false;
    }

    /**
     * @param $pluginPath
     * @return bool
     */
    public function disable($pluginPath)
    {
        $plugins = $this->getInstaleds();
        if($plugins && count($plugins)){
            foreach ($plugins as $key => $plugin) {
                if ($plugin['name'] == $pluginPath) {
//                $plugin=$plugins[$pluginPath];
                    $store = $this->getStorage();
                    $store[$pluginPath] = $plugin['autoload'];
                    $this->addStorage($store);
                    unset($plugins[$key]['autoload']);
                    $this->setInstaleds($plugins);
                    continue;
                }
            }
        }


        return $this->command('dump-autoload');
    }

    /**
     * @param $package
     * @return bool
     */
    public function composerRequireDev($package)
    {
        $plugin = explode(':', $package);
        $this->mainComposer['require'][$plugin[0]] = $plugin[1];
        \File::put($this->path, json_encode($this->mainComposer, true));
        return $this->command('update --dev --no-interaction');
    }

    /**
     * @param $package
     * @return bool
     */
    public function composerRemoveDev($package)
    {
        if (!isset($this->mainComposer['require-dev'][$package])) {
            echo 'Warning wrong package name!!!';
            exit;
        }
        unset($this->mainComposer['require-dev'][$package]);
        \File::put(base_path('composer.json'), json_encode($this->mainComposer));
        return $this->command('update --dev --no-interaction');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function units()
    {
        $config = $this->config;
        if ($config && isset($config['units']) && is_array($config['units'])) {
            $units = [];
            foreach ($config['units'] as $slug) {
                $units[] = Units::find($slug);
            }
            return collect($units);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __get($name)
    {
        return $this->attributes[$name] ?? false;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function children()
    {
        $children = [];

        foreach ($this->getPlugins(true) as $plugin) {
            if (isset($plugin['parent']) && $plugin['parent'] == $this->name) {
                $children[] = $this->find($plugin['name']);
            }
        }

        return collect($children);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPlugins($all = false)
    {
        $plugins = [];

        foreach ($this->plugins as $pluginPath => $version) {
            if (\File::exists($this->pluginPath($pluginPath))) {
                $plugin = json_decode(\File::get($this->pluginPath($pluginPath)), true);
                if ($plugin['type'] == $this->type || $all) {
                    $plugins[$pluginPath] = $plugin;
                    $plugins[$pluginPath]['path'] = $this->path;
                    $plugins[$pluginPath]['version'] = $version;

                }
            }
        }
        return collect($plugins);
    }

    /**
     * @param $plugin
     * @return string
     */
    private function pluginPath($plugin)
    {
        return base_path($this->dir . DS . 'vendor' . DS . $plugin . DS . 'composer.json');
    }

    /**
     * @param $package
     * @return $this|null
     */
    public function find($package)
    {
        $plugins = $this->getPlugins(true);
        if (isset($plugins[$package])) {
            $this->attributes = $plugins[$package];
            return $this;
        }
        return null;
    }

    public function getPath($path = null)
    {
        return base_path($this->dir . DS . $this->name . $path);
    }

    public function parent()
    {
        return $this->find($this->parent);
    }
}