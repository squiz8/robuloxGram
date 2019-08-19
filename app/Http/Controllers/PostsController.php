<?php

namespace App\Http\Controllers;
use App\Post;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        $users = auth()->user()->following()->pluck('profiles.user_id');
        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(4);
        
        $postCount = $user->posts->count();
        $followersCount = $user->profile->followers->count();
        $followingCount = $user->following->count(); 
        
        return view('posts.index', compact('posts', 'postCount', 'followersCount', 'followingCount'));
        }
    
    public function create() {
        return view('posts.create');     
    }
    
    public function store() {
        
        $data = request()->validate([
            'caption' => 'required',
            'image' => ['required', 'image'],
        ]);
        
        $imagePath = request('image')->store('uploads', 'public');
        
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        $image->save();
                
        
        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);
        return redirect('/profile/' . auth()->user()->id);
    }
    
    public function show(\App\Post $post) {
        
        //the compact works just like the array matching post to the variable $post
       return view('posts.show', compact('post'));
    }
}
