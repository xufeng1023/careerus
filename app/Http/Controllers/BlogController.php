<?php

namespace App\Http\Controllers;

use DB;
use Cache;
use App\Mail\crawlWechatBlocked;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\{Blog, CrawlBlog};

class BlogController extends Controller
{
    private $http;

    public function __construct()
    {
        $this->http = new \GuzzleHttp\Client;
    }
    public function all()
    {
        $blogs = CrawlBlog::all();//Blog::latest()->get();

        return view('blogs', compact('blogs'));
    }

    public function allAdmin()
    {
        if(request('id')) $blogs[] = Blog::whereTitle(request('id'))->first();
        else $blogs = Blog::latest()->get();

        return view('admin.blog', compact('blogs'));
    }

    public function save()
    {
        Blog::create([
            'user_id' => auth()->id(),
            'title' => preg_replace('/\//', '', request('title')),
            'content' => request('content'),
            'description' => request('description')
        ]);

        return '/admin/blog';
    }

    public function crawlWeChatBlog()
    { 
        if(!$whatToCrawl = cache('dreamgo-collegs')) return;

        libxml_use_internal_errors(true);
        $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>';
        
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;

        $query = array_shift($whatToCrawl);
        $search = rtrim($query->name, '新闻');
        
        //////
        $html = $this->http->get('http://weixin.sogou.com/weixin?query='.urlencode($search).'&type=2');
       // dd((string)$r->getBody());
        ////
        $page = (string) $html->getBody();
        //$page = file_get_contents('http://weixin.sogou.com/weixin?query='.urlencode($search).'&type=2');
        
        /* 攻克验证码 */
        if(stripos($page, '验证码') !== false) {
            preg_match('/tc=([\d]*)/', $page, $time);

            if(isset($time[1])) {
                preg_match('/"(%2.*)"/', $page, $url);
                \Mail::raw($url[1], function ($message) {
                    $message->to('xfeng@dreamgo.com')
                      ->subject('crawlWechatBlocked url');
                });
                \Mail::raw('https://weixin.sogou.com/antispider/util/seccode.php?tc='.$time[1]."<br>careerus.com/unlockcrawl?r=".$url[1], function ($message) {
                    $message->to('xfeng@dreamgo.com')
                      ->subject('crawlWechatBlocked');
                });
            }
            return;
        }
        /*************/

        $page = preg_replace("/[\n\r\t]+/", '', $page);
        preg_match('/<ul class="news-list".*<\/ul>/', $page, $matches);

        if(!isset($matches[0])) {
            \Log::info($title);
            return;
        }

        $page = preg_replace("/<script>[a-zA-Z0-9.()']*<\/script>/", '', $matches[0]);

        $dom->loadHTML($meta.$page);

        $lis = $dom->getElementsByTagName("li");

        foreach ($lis as $key => $li) {
            $title = $li->getElementsByTagName('h3')[0]->textContent;
            preg_match_all('/[\x{4e00}-\x{9fff}0-9a-zA-Z]+/u', $title, $matches);
            $title = join('', $matches[0]);

            $post = DB::connection('dreamgo')->select("
                SELECT ID FROM wp_posts WHERE post_title = '$title' AND post_status = 'publish' LIMIT 1
            ");

            if($post) continue;
            
            $thumbnail = $li->getElementsByTagName("img")[0]->getAttribute('src');
            $thumbnail = explode('url=', $thumbnail)[1];
            $link = $li->getElementsByTagName('a')[1]->getAttribute('href');
            $excerpt = $li->getElementsByTagName('p')[0]->textContent;
            $author = $li->getElementsByTagName('a')[2]->textContent;

            $contentPage = file_get_contents($link);
 
            $contentPage = preg_replace("/[\n\r\t]+/", '', $contentPage);
            $contentPage = preg_replace("/[\s]+/", ' ', $contentPage);
            $contentPage = preg_replace('/data-src/', 'src', $contentPage);
            $contentPage = preg_replace('/<!DOCTYPE html>.*<\/em> <\/div> /', '', $contentPage);
            $contentPage = preg_replace('/<\/div> <script nonce="[\d]+" type="text\/javascript"> var first_sceen__time.*html>/', '', $contentPage);
            $contentPage .= '</div>';
           // dd($contentPage);
            //preg_match('/<div class="rich_media_content\s?" id="js_content.*[\n\s]*.*[\n\s]*<\/div>/', $contentPage, $matches);

            // if(!isset($matches[0])) {
            //     \Log::info($matches);
            //     continue;
            // }

            $contentPage = preg_replace('/<script.*<\/script>/', '', $contentPage);
            
            $contentPage = strip_tags($contentPage, '<div><span><pre><p><br><hr><hgroup><h1><h2><h3><h4><h5><h6>
            <ul><ol><li><dl><dt><dd><strong><em><b><i><u><img><abbr><address>
            <blockquote><label><caption><table><tbody><td><tfoot><th><thead><tr>');

            $this->http->post('http://18.219.227.57/wp-admin/admin-ajax.php?action=dreamgo_wechat_post', [
                'form_params' => [
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $contentPage,
                    'category' => $query->term_id,
                    'thumbnail' => $thumbnail,
                ],
            ]);
        }

        $whatToCrawl[] = $query;
        Cache::forever('dreamgo-collegs', $whatToCrawl);
    }

    public function updateCollegesInCache()
    {
        $colleges = DB::connection('dreamgo')->select("
            SELECT p.post_title FROM wp_posts p INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id WHERE tr.term_taxonomy_id = 3
        ");

        if(count($colleges)) {
            foreach($colleges as $college) {
                $this->http->post('http://18.219.227.57/wp-admin/admin-ajax.php?action=dreamgo_update_college_news_category', [
                    'form_params' => [
                        'name' => $college->post_title.'新闻'
                    ],
                ]);
            }

            $categories = DB::connection('dreamgo')->select("
                SELECT term_id, name FROM wp_terms WHERE name LIKE '%新闻'
            ");

            if(count($categories)) Cache::forever('dreamgo-collegs', $categories);
        }
    }

    public function unlockcrawl()
    {
        $url = urldecode(request('r'));
        $r = explode('&', $url)[0].'&_sug_type_=&&_sug_=n&type=2&page=1&ie=utf8';
        $c = strtoupper(request('c'));

        $response = $this->http->post('https://weixin.sogou.com/antispider/thank.php', [
            'form_params' => [
                'c' => $c,
                'r' => $r,
                'v' => 5
            ]
        ]);
                var_dump($c, $r);
        dd((string) $response->getBody());
    }

    public function unlockcrawlForm()
    {
        return view('unlockcrawl');
    }

    public function adminPage()
    {
        $crawlBlogs = CrawlBlog::all();
        return view('admin.crawlBlog', compact('crawlBlogs'));
    }

    // public function show(Blog $blog)
    public function show(CrawlBlog $blog)
    {
        return view('blog', compact('blog'));
    }

    public function update(Blog $blog)
    {
        $blog->update(request()->all());
    }
}
