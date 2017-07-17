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

namespace App\Modules\Uploads\Models;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    /**
     * @var string
     */
    protected $table = 'profiles';
    /**
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function styles()
    {
        return $this->belongsToMany('App\Modules\Uploads\Models\StyleItems', 'profile_styles',
            'profile_id', 'style_item_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Modules\Users\User','user_id','id');
    }

    protected static function boot ()
    {
        parent::boot();
        static::created(function ($model) {
            self::assignDefaultStyles($model);
            return $model;
        });
    }

    public static function assignDefaultStyles($model){
        $items = StyleItems::defaults()->pluck('id','id')->toArray();

        $model->styles()->attach($items);
    }
}
