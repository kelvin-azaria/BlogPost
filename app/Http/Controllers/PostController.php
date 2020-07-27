<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Events\BlogPostPosted;
use App\Facades\CounterFacades;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\Image;
use App\Services\Counter;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct(Counter $counter)
    {
        $this->middleware('auth')
             ->only(['create','store','edit','update','destroy']);
    }

    public function index()
    {
        // DB::connection()->enableQueryLog();

        // $posts = BlogPost::with('comments')->get();

        // foreach($posts as $post){
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

        return view('posts.index', [
            'posts' => BlogPost::latestWithRelations()->get()
        ]);
    }

    public function show(Request $request, $id)
        {
            $request->session()->reflash(); //REMOVE STATUS AFTER REFRESH

            //return view('posts.show', [
            //    'post' => BlogPost::with(['comments' => function($query){
            //        return $query->latest();
            //    }])->findOrFail($id)
            //]);

            $blog_post = Cache::tags(['blog-post'])->remember("blog-post-($id)", 60, function () use($id) {
                return BlogPost::with('comments','tags','user','comments.user')->findOrFail($id);
            });

            // $counter = resolve(Counter::class);

            return view('posts.show', [
                'post' => $blog_post,
                'counter' => CounterFacades::Increment("blog-post-($id)",['blog-post'])
            ]);
        }

    public function create()
    {
        $this->authorize('posts.create');
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validate = $request->validated();
        $validate['user_id'] = $request->user()->id;

        $bp = BlogPost::create($validate);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails','public');
            $bp->Image()->save(
                Image::make([ 'path' => $path ])
            );
        }

        event(new BlogPostPosted($bp));

        $request->session()->flash('status', 'Create Success!');

        return redirect()->route('posts.show', ['post' => $bp->id]);
    }

    public function edit($id)
    {   
        $post = BlogPost::findOrFail($id);

        //if (Gate::denies('update-post', $post)){
        //    abort(403, "You can't edit this blog post");
        //}

        $this->authorize($post);

        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        //if (Gate::denies('update-post', $post)){
        //    abort(403, "You can't edit this blog post");
        //}

        $this->authorize('update', $post);

        $validate = $request->validated();
        
        $post->fill($validate);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails','public');

            dump($path);
            
            if ($post->image) {
                Storage::disk('public')->delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();

                dd($post);
            } else {
                $post->Image()->save(
                    Image::make([ 'path' => $path ])
                );
                
            }
            
        }

        $post->save();
        $request->session()->flash('status', 'Update Success!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {   
        $post = BlogPost::findOrFail($id);
        
        $this->authorize($post);

        $post->delete();

        $request->session()->flash('status', 'Delete Success!');

        return redirect()->route('posts.index');
    }
}
