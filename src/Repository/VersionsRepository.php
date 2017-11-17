<?php

namespace Btybug\Uploads\Repository;

use Btybug\btybug\Repositories\GeneralRepository;
use Btybug\Framework\Models\Versions;

class VersionsRepository extends GeneralRepository
{

    public function updateWhere($id, $condition = "=", $data)
    {
        return $this->model()->where('id', $condition, $id)->update($data);
    }

    public function model()
    {
        return new Versions();
    }

    public function wherePluck(string $attribute, string $attrVal, string $key, string $value)
    {
        return $this->model->where($attribute, $attrVal)->pluck($key, $value);
    }

    public function getJS()
    {
        return $this->model->where('type', 'js')->where('active', 1)->get();
    }

    public function getJQuery()
    {
        return $this->model->where('type', 'jquery')->where('active', 1)->get();
    }

    public function getCss()
    {
        return $this->model->where('type', 'css')->get();
    }

    public function getByExcept(string $attribute, string $value, string $except, string $exceptValue)
    {
        return $this->model()->where($attribute, $value)->where($except, '!=', $exceptValue)->get();
    }

    public function updateWithAttribute($attr, $condition = "=", $value, $data)
    {
        return $this->model->where($attr, $condition, $value)->update($data);
    }

    public function getLocalData($section = "is_generated",$type = "js")
    {
        return $this->model->where('type', $type)->where('active', 1)->where('env', "local")->where($section,1)->get();
    }

    public function getJSLiveLinks($pluck = false)
    {
        $query = $this->model->where('type', "js")->where('env', 1);

        if($pluck){
            return $query->pluck("name","id");
        }

        return $query->get();
    }
}