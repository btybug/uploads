<?php
/**
 * Copyright (c) 2017.
 * *
 *  * Created by PhpStorm.
 *  * User: Edo
 *  * Date: 10/3/2016
 *  * Time: 10:44 PM
 *
 */

namespace Btybug\Uploads\Models;

use Illuminate\Database\Eloquent\Model;
use Btybug\btybug\Helpers\helpers;

/**
 * Class StyleItems
 * @package Btybug\Resources\Models
 */
class Style extends Model
{
    /**
     * @var string
     */
    public static $ext = '.css';
    /**
     * @var string
     */
    public static $main_css = 'core_styles.css';
    /**
     * @var
     */
    public static $uf;
    /**
     * @var array
     */
    public $messages = [
        'unique_whit_colume' => 'The :attribute is unique',
    ];
    /**
     * @var string
     */
    protected $table = 'style';
    /**
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = ['html'];

    /**
     * @param $file
     * @return array|bool
     */
    public static function uplaodStyle($file)
    {
        self::$uf = config('paths.styles_upl');
        $originalFileName = $file->getClientOriginalName();
        $fileNmae = str_replace(self::$ext, "", $originalFileName);
        $file->move(self::$uf, $originalFileName);
        if (\File::exists(self::$uf . $originalFileName)) {
            $css = \File::get(self::$uf . $originalFileName);
            $slug = helpers::between('.', '{', $css);
            \File::delete(self::$uf . $originalFileName);

            return ['slug' => $slug, 'css_data' => $css, 'name' => $fileNmae];
        }

        return false;
    }

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            self::makeCss();

            return $model;
        });

        static::updated(function ($model) {
            self::makeCss();

            return $model;
        });

        static::deleted(function ($model) {
            self::makeCss();

            return $model;
        });
    }

    /**
     *
     */
    public static function makeCss()
    {
        $css_file = config('paths.css') . '/' . self::$main_css;

        $styles = self::all();
        $content = '';
        if (count($styles)) {
            foreach ($styles as $item) {
                $content .= $item->css_data;
            }
        }

        \File::put($css_file, $content);
    }

    /**
     * @param $query
     */
    public function scopeDefaults($query)
    {
        $query->where('is_default', 1);
    }

    public function getHtmlAttribute()
    {

        return self::getDemoHtml($this->type, $this->slug);
    }

    public static function getDemoHtml($key, $class)
    {

        $demoHtml = [
            'text' => "<span  class='$class item-to-change'>Demo text</span>",
            'image' => "<img src='public/img/ajax-loader5.gif' alt='demoimg' class='$class'>",
            'container' => "<div class='$class'></div>",
            'buttons' => "<button class='$class'></button>",
            'fields' => "<form class='$class'></form>",
            'breadcrumb' => "<ul class='$class'>
                                <li><a href=\"#\">Home</a></li>
                                <li><a href=\"#\">Pictures</a></li>
                                <li><a href=\"#\">Summer</a></li>
                                <li>Italy</li>
                            </ul>",
            'menu' => "<ul class='$class'>
                            <li>Home</li>
                            <li>About</li>
                            <li>Contact us</li>
                        </ul>",
            'animation' => "<div class='$class'></div>",
            'notification' => "<span class='$class'>Demo Text</span>"

        ];
        return $demoHtml[$key];

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classe()
    {
        return $this->belongsTo('Btybug\Uploads\Models\Styles', 'style_id');
    }
}

