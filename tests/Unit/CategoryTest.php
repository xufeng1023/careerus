<?php

namespace Tests\Unit;

use App\{Catagory};
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_ordered_by_most_used()
    {
        $cat1 = create('Catagory');
        $cat2 = create('Catagory');

        create('Post', ['catagory_id' => $cat1->id]);
        create('Post', ['catagory_id' => $cat2->id]);
        create('Post', ['catagory_id' => $cat2->id]);

        $category = (new Catagory)->orderByMostUsed()->first(); // only category name

        $this->assertEquals($cat2->name, $category);
    }
}
