<?php

namespace App\Http\Controllers;

use DB;
use SteelyWing\Chinese\Chinese;
use App\{Post, Catagory, Tag, State, Favorite};

class PostController extends Controller
{
    protected $locations = [
        'ny' => '纽约', 'San Francisco' => '圣弗朗西期', 'Los Angeles' => '洛杉矶',
        'Atlanta' => '亚特兰大', 'Chicago' => '芝加哥', 'Boston' => '波士顿',
        'Houston' => '休斯顿', 'Seattle' => '西雅图', 'Dallas' => '达拉斯',
        'Austin' => '奥斯丁', 'nj' => '新泽西', 'Washington' => '华盛顿',
        'San Diego' => 'San Diego', 'Bellevue' => 'Bellevue', 'San Jose' => 'San Jose',
        'Mountian View' => 'Mountian View', 'Palo Alto' => 'Palo Alto', 'Columbus' => 'Columbus',
        'Richardson' => 'Richardson'
    ];

    public function index()
    {
        $categories = (new Catagory)->orderByMostUsed();

        $newJobs = Post::with('company')->whereRaw('end_at >= '.date('Y-m-d'))->latest()->take(9)->get();

        $recommendedJobs = Post::select('chinese_title', 'title', 'identity')->where('recommended', 1)->take(10)->get();
        
        //$locations = Post::select(DB::raw('count(id) as total'), 'location')->groupBy('location')->orderBy('total', 'desc')->take(9)->get(); 
        
        $locations = array_splice($this->locations, 0, 9);

        $hotTags = DB::table('tags')->select('name')->whereIn('id', 
            DB::table('post_tag')->select('tag_id')->orderByRaw('count(tag_id)', 'desc')->groupBy('tag_id')
        )->take(10)->get()->pluck('name');

        return view('welcome', compact('categories', 'newJobs', 'locations', 'hotTags', 'recommendedJobs'));
    }

    public function searchBarJob()
    {
        return Post::select('title', 'chinese_title', 'identity', 'created_at', 'end_at')
            ->where('title', 'LIKE', request('s').' %')
            ->orWhere('title', 'LIKE', '% '.request('s'))
            ->orWhere('title', 'LIKE', '% '.request('s').' %')
            ->orWhere('chinese_title', 'LIKE', '%'.request('s').'%')
            ->take(5)->get()->unique('title');
    }

    public function searchBarLocation()
    {
        return Post::where('location', 'LIKE', '%'.request('s'))
            ->orWhere('location', 'LIKE', request('s').'%')
            ->take(5)->get()->unique('location')->pluck('location');
    }

    public function all()
    {
        $locationTitleChinese = '';

        $filtered = array_where(request()->all(), function ($value, $key) {
            return !is_null($value);
        });

        //if(!array_except($filtered, ['page'])) return redirect('/');

        $query = Post::with('company.visaJobs');

        $chinese = new Chinese();

        foreach($filtered as $key => $value) {
            $filtered[$key] = $chinese->to(Chinese::CHS, $value);
        }

        if(request('s')) {
            $query->where(function($query) use($filtered) {
                $query->where('title', $filtered['s'])
                    ->orWhere('title', 'LIKE', $filtered['s'].' %')
                    ->orWhere('title', 'LIKE', '% '.$filtered['s'])
                    ->orWhere('title', 'LIKE', '% '.$filtered['s'].' %')
                    ->orWhere('chinese_title', 'LIKE', '%'.$filtered['s'].'%');
            });
        }

        if($query->count() === 0 && isset($filtered['s'])) {
            $query = Post::with('company.visaJobs');
            $query->whereIn('company_id', DB::table('companies')->select('id')->where('name', 'LIKE', $filtered['s']."%"));
        }

        if($query->count() === 0 && isset($filtered['s'])) {
            $query = Post::with('company.visaJobs');
            $query->whereIn(
                'id', DB::table('post_tag')->select('post_id')->whereIn(
                    'tag_id', DB::table('tags')->select('id')->where('name', 'LIKE', $filtered['s']."%")
                )
            );
        }

        if($query->count() === 0 && isset($filtered['s'])) {
            $query = Post::with('company.visaJobs');
            $query->whereIn('catagory_id', DB::table('catagories')->select('id')->where('name', $filtered['s']));
        }

        if(request('tp')) {
            $query = $query->where('job_type', $filtered['tp']);
        }

        if(request('l')) $location = $filtered['l'];
        elseif(request('s') && $query->count() === 0) $location = $filtered['s'];

        if(isset($location)) {
            $state = State::where('simplified_name', 'LIKE', $location.'%')->orWhere('STATE_NAME', 'LIKE', $location.'%')->first();
            if($state) {
                $location = $state->STATE_CODE;
                $locationTitleChinese = $state->simplified_name;
            }
            $query = $query->where(function($query) use($location) {
                $query->where('location', 'LIKE', '%,'.$location)->orWhere('location', 'LIKE', $location.'%');
            });
        }

        if(request('ct')) {
            $query = $query->whereIn('catagory_id', DB::table('catagories')->select('id')->where('name', $filtered['ct']));
        }

        if(request('t')) {
            $tag = Tag::where('name', $filtered['t'])->first();
            if($tag) {
                $query = $query->whereExists(function($q) use($tag) {
                    $q->select('post_id')->from('post_tag')->where('tag_id', $tag->id)->whereRaw('post_tag.post_id = posts.id');
                });
            }
        }
 
        $posts = $query->latest()->paginate(10);

        // $usedTags = Tag::whereExists(function($q) {
        //     $q->select('tag_id')->from('post_tag')->whereRaw('post_tag.tag_id = tags.id');
        // })->get();

        $categories = (new Catagory)->orderByMostUsed();

        $locations = $this->locations;

        $types = ['Full-time', 'Part-time', 'Internship'];

        return view('posts', compact('posts', 'categories', 'types', 'locations', 'locationTitleChinese'));
    }

    public function show(Post $post)
    {
        $post->load([
            'catagory', 
            'company.visaJobs' => function($query) {
                $query->orderBy('year', 'asc');
            }
        ]);

        return view('post', compact('post'));
    }

    public function allAdmin()
    {
        if(request('id')) $posts[] = Post::find(request('id'))->load('tags');
        else $posts = Post::with('company')->latest()->get();

        $tags = Tag::all();
        $states = State::all();
        
        return view('admin.posts', compact('posts', 'tags', 'states'));
    }

    public function save()
    {
        $category = Catagory::findOrFail(request('catagory_id'));

        $data = request()->all();
        $data['user_id'] = auth()->id();
        $data['location'] = $this->formatLocation();
        $data['sponsor_odds'] = ($category->rfe + 45 + request('sponsor_odds') + rand(70, 100)) * 0.25;

        if(app()->environment() !== 'testing') {
            $data['identity'] = str_random(50).md5(time());
        }

        $post = Post::create($data);

        if(request('tags')) {
            $post->tags()->attach(request('tags'));
        }

        return back();
    }

    public function update(Post $post)
    {
        $category = Catagory::findOrFail(request('catagory_id'));

        $data = request()->all();

        $data['location'] = $this->formatLocation();

        $data['sponsor_odds'] = ($category->rfe + 45 + request('sponsor_odds') + rand(70, 100)) * 0.25;

        $post->update($data);

        $post->tags()->sync(request('tags'));

        return back()->with('updated', trans('admin.updated'));
    }

    public function search()
    {
        $posts = Post::where('location', 'LIKE', '%'.request('s').'%')->take(5)->get();
        
        return $posts->pluck('location');
    }

    public function delete(Post $post)
    {
        $post->remove();
    }

    public function recommend(Post $post)
    {
        $post->recommended = !$post->recommended;
        $post->save();
    }

    private function formatLocation()
    {
        if(request('city') && request('state')) return request('city').','.request('state');

        return request('city') ?: request('state');
    }
}