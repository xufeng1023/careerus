<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyData extends Model
{
    protected $table = 'company_data';

    protected $guarded = [];

    public $timestamps = false;

    protected $appends = ['totalSponsor', 'scale', 'fullAddress'];

    public function visaJobs()
    {
        return $this->hasMany(CompanySponsorData::class, 'company_id');
    }

    public function getFullAddressAttribute()
    {
        return $this->address.', '.$this->city.', '.$this->state.' '.$this->zip; 
    }

    public function getTotalSponsorAttribute()
    {
        $sponsored = $this->visaJobs();
        
        $sum = $sponsored->where('year', 2017)->sum('sponsor_number');

        if($sum === 0) $sum = $sponsored->where('year', 2016)->sum('sponsor_number');

        return $sum;
    }

    public function getNameAttribute($value)
    {
        return html_entity_decode($value);
    }

    public function getScaleAttribute()
    {
        switch($people = 2 * $this->totalSponsor) {
            case 0 <= $people && $people < 10:
                return '10人以下';
                break;
            case 10 <= $people && $people < 20:
                return '10-20人';
                break;
            case 20 <= $people && $people < 50:
                return '20-50人';
                break;
            case 50 <= $people && $people < 100:
                return '50-100人';
                break;
            case 100 <= $people && $people < 200:
                return '100-200人';
                break;
            case 200 <= $people && $people < 500:
                return '200-500人';
                break;
            case 500 <= $people && $people < 1000:
                return '500-1000人';
                break;
            case 1000 <= $people && $people < 10000:
                return '1000-1万人';
                break;
            case 10000 <= $people:
                return '1万人以上';
                break;
        }
    }
}
