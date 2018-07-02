<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GreenCard extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $dates = ['check_at'];

    public function titleToChinese($value)
    {
        $filter = [
            '1st' => '第一优先，杰出人才，研究人员教授，跨国公司主管',
            '2nd' => '第二优先，高等学位专业人才',
            '3rd' => '第三优先，技术劳工及专业人士',
            'Other Workers' => '非技术劳工',
            '4th' => '第四优先，特殊人员',
            'Certain Religious Workers' => '宗教人士',
            '5th Non-Regional Center (C5 and T5)' => '第五优先，投资移民 (C5 and T5)',
            '5th Regional Center (I5 and R5)' => '第五优先，投资移民 (I5 and R5)',
        ];

        return isset($filter[$value]) ? $filter[$value] : $value;
    }

    public function countryToChinese($value)
    {
        $filter = [
            'all' => '全球/港澳台',
            'china' => '中国大陆'
        ];

        return isset($filter[$value]) ? $filter[$value] : $value;
    }

    public function setFilingAtAttribute($value)
    {
        if($value == 'C') {
            $this->attributes['filing_at'] = null;
            return;
        }

        preg_match('/^(\d{2})([A-Z]{3})(\d{2})/', $value, $matches);

        $this->attributes['filing_at'] = Carbon::createFromFormat('y-M-d', $matches[3].'-'.$matches[2].'-'.$matches[1])->format('Y-M-d');
    }

    public function setActionAtAttribute($value)
    {
        if(starts_with($value, 'C')) {
            $this->attributes['action_at'] = null;
            return;
        }

        preg_match('/^(\d{2})([A-Z]{3})(\d{2})/', $value, $matches);
        
        $this->attributes['action_at'] = Carbon::createFromFormat('y-M-d', $matches[3].'-'.$matches[2].'-'.$matches[1])->format('Y-M-d');
    }
}
