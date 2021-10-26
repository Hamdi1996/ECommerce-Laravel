<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;
    protected $table = 'main_categories';
    protected $fillable = [
        'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at'
    ];

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function scopeSelection($query)
    {
       return $query->select('id','translation_lang','translation_of','name', 'slug', 'photo', 'active');
    }

    public function getPhotoAttribute($val)
    {
       return ($val !==null) ? asset('assets/'.$val):"";
    }

    public function getActive()
    {
       return $this->active == 0 ?'غير مفعل':' مفعل';
    }

    public function categories()
    {
        return $this->hasMany(self::class,'translation_of');
    }
    public function vendors()
    {
        return $this->hasMany('App\Models\Vendor','category_id');
    }

}
