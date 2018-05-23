<?php

use Illuminate\Database\Seeder;

class CatagoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['会计', 90], ['行政和文职', 48], ['市场营销', 49], ['金融分析', 56], ['管理', 68], ['软件工程', 83],
            ['网站设计', 78], ['图形设计', 95], ['服装设计', 63], ['教师', 91], ['研究员', 93], ['电子工程', 86], 
            ['生物工程', 89], ['化学工程', 84], ['信息安全', 85], ['公共关系', 57], ['材料工程', 84], ['牙医', 88], 
            ['统计分析', 67], ['建筑设计', 64], ['工业设计', 63], ['商务分析', 69], ['数据分析', 75], ['中医针灸', 90], 
            ['心理咨询', 75], ['教育咨询', 68], ['信息安全', 70], ['人力资源', 54], ['法律', 90], ['其他', 60]
        ];
        
        foreach($categories as $category) {
            factory(App\Catagory::class)->create([
                'name' => $category[0],
                'rfe' => $category[1],
            ]);
        }
    }
}
