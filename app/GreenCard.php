<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GreenCard extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $dates = ['check_at'];

    public function titleToChinese()
    {
        $filter = [
            '1st' => '第一优先，杰出人才，研究人员教授，跨国公司主管',
            '2nd' => '第二优先，高等学位专业人才',
            '3rd' => '第三优先，技术劳工及专业人士',
            'Other' => '非技术劳工',
            '4th' => '第四优先，特殊人员',
            'Certain' => '宗教人士',
            '5th' => '第五优先，投资移民 (C5 and T5)',
            //'5th' => '第五优先，投资移民 (I5 and R5)',
        ];
        $array = explode(' ', $this->title);
        return isset($filter[$array[0]]) ? $filter[$array[0]] : '第五优先，投资移民 (I5 and R5)';
    }

    public function titleToEb()
    {
        $filter = [
            '1st' => 'EB-1',
            '2nd' => 'EB-2',
            '3rd' => 'EB-3',
            'Other' => 'Other Workers',
            '4th' => 'EB-4',
            'Certain' => 'Certain Religious Workers',
            '5th' => 'EB-5',
        ];
        $array = explode(' ', $this->title);
        return isset($filter[$array[0]]) ? $filter[$array[0]] : 'EB-5';
    }

    public function changesInDays($compare, $field = 'action_at')
    {
        $from = Carbon::createFromFormat('Y-M-d', $this->{$field});
        $compare = Carbon::createFromFormat('Y-M-d', $compare);
        $diff = $from->diffInDays($compare, false);

        if($diff < 0) return '<span class="badge badge-pill badge-success">前进了'.abs($diff).'天</span>';
        else return '<span class="badge badge-pill badge-danger">倒退了'.$diff.'天</span>';
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

        if(starts_with($value, 'U')) {
            $this->attributes['action_at'] = 'U';
            return;
        }

        preg_match('/^(\d{2})([A-Z]{3})(\d{2})/', $value, $matches);
        
        $this->attributes['action_at'] = Carbon::createFromFormat('y-M-d', $matches[3].'-'.$matches[2].'-'.$matches[1])->format('Y-M-d');
    }
}
