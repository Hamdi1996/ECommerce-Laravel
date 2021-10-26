<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use HasFactory,Notifiable;

    protected $table = 'vendors';
    protected $fillable = [
        'id', 'name', 'mobile', 'address', 'email', 'active','logo','password','category_id','created_at', 'updated_at'
    ];

    protected $hidden = ['category_id','password'];

    
 public function scopeActive($query)
    {
     
        return $query->where('active',1);
    }
    public function getLogoAttribute($val)
    {
       return ($val !==null) ? asset('assets/'.$val):"";
    }
    public function scopeSelection($query)
    {
       return $query->select('id','category_id','name','logo','email', 'active','mobile');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\MainCategory','category_id');
    }
    public function getActive()
    {
       return $this->active == 0 ?'غير مفعل':' مفعل';
    }
    public function setPasswordAttribute($password)
    {
        if(!empty($password))
         $this->attributes['password']=bcrypt($password);
    }
}
