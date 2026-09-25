<?php

namespace App\Models;

use App\Models\Admin\Heading;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['id', 'name', 'is_default', 'code', 'rtl', 'customer_keywords'];

    public function basic_setting()
    {
        return $this->hasOne('App\Models\BasicSetting')->withDefault(function ($instance, $parent) {
            return \App\Models\BasicSetting::first() ?: new \App\Models\BasicSetting([
                'website_title' => 'LaunchShop',
                'base_color' => '007bff',
            ]);
        });
    }

    public function basic_extended()
    {
        return $this->hasOne('App\Models\BasicExtended', 'language_id')->withDefault(function ($instance, $parent) {
            return \App\Models\BasicExtended::first() ?: new \App\Models\BasicExtended([
                'base_currency_symbol' => '₹',
                'base_currency_text' => 'INR',
                'base_currency_symbol_position' => 'left',
                'contact_numbers' => '7200770351',
            ]);
        });
    }

    public function seo()
    {
        return $this->hasOne('App\Models\Seo');
    }

    public function menus()
    {
        return $this->hasMany('App\Models\Menu');
    }

    public function features()
    {
        return $this->hasMany('App\Models\Feature');
    }

    public function testimonials()
    {
        return $this->hasMany('App\Models\Testimonial');
    }

    public function partners()
    {
        return $this->hasMany('App\Models\Partner');
    }


    public function ulinks()
    {
        return $this->hasMany('App\Models\Ulink');
    }

    public function pages()
    {
        return $this->hasMany('App\Models\Page');
    }

    public function faqs()
    {
        return $this->hasMany('App\Models\Faq');
    }

    public function bcategories()
    {
        return $this->hasMany('App\Models\Bcategory');
    }

    public function processes()
    {
        return $this->hasMany('App\Models\Process');
    }

    public function blogs()
    {
        return $this->hasMany('App\Models\Blog');
    }

    public function popups()
    {
        return $this->hasMany('App\Models\Popup');
    }

    public function pageName()
    {
        return $this->hasOne(Heading::class, 'language_id');
    }
}
