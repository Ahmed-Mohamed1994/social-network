<?php
namespace App\Http\Controllers;


use App\Comment;
use App\Like;
use App\LikeComment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller{
    public function getDashboard(){
        $posts = Post::orderBy('created_at','desc')->get();
        $comments = Comment::all();
        return view('dashboard',['posts' => $posts,'comments' => $comments]);
    }

    public function getSinglePost($post_id){
        $post = Post::find($post_id);
        $comments = Comment::where('post_id', $post_id)->get();
        if(!$post){
            return view('errors.503');
        }else{
            return view('single_post',['post' => $post,'comments' => $comments]);
        }
    }

    public function postCreatePost(Request $request){
        $this->validate($request,[
            'body' => 'required|max:1000'
        ]);
        $post = new Post();
        $post->body = $request['body'];
        $message = 'There was an error';
        if($request->user()->posts()->save($post)){
            $message = 'Post Succsesfully created!';
        }
        return redirect()->route('dashboard')->with(['message' => $message]);
    }

    public function getDeletePost($post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if(Auth::user() != $post->user){
            return redirect()->back();
        }
        $post->delete();
        return redirect()->route('dashboard')->with(['message' => 'Successfully deleted!']);
    }

    public function postEditPost(Request $request){
        $this->validate($request, [
           'body' => 'required'
        ]);
        $post = Post::find($request['postId']);
        if(Auth::user() != $post->user){
            return redirect()->back();
        }
        $post->body = $request['body'];
        $post->update();
        return response()->json(['new_body' => $post->body], 200);
    }

    public function postEditComment(Request $request){
        $this->validate($request, [
            'comment_body' => 'required'
        ]);
        $comment = Comment::find($request['commentId']);
        if(Auth::user() != $comment->user){
            return redirect()->back();
        }
        $comment->comment = $request['comment_body'];
        $comment->update();
        return response()->json(['new_comment' => $comment->comment], 200);
    }

    public function postAddComment(Request $request){
        $this->validate($request,[
            'post_comment' => 'required|max:1000'
        ]);
        $post_id = $request['post_comment_id'];
        $comment_body = $request['post_comment'];
        $user = Auth::user();
        $comment = new Comment();
        $comment->post_id = $post_id;
        $comment->user_id = $user->id;
        $comment->comment = $comment_body;
        $comment->save();
        return redirect()->route('dashboard')->with(['message' => "Comment Succsesfully Added!"]);
    }

    public function getDeleteComment($comment_id){
        $comment = Comment::where('id', $comment_id)->first();
        if(Auth::user() != $comment->user){
            return redirect()->back();
        }
        $comment->delete();
        return redirect()->route('dashboard')->with(['message' => 'Successfully deleted!']);
    }

    public function postLikePost(Request $request){
        $post_id = $request['postId'];
        $is_like = $request['isLike'] === 'true';
        $update = false;
        $post = Post::find($post_id);
        if(!$post){
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('post_id' , $post_id)->first();
        if($like){
            $already_like = $like->like;
            $update = true;
            if($already_like == $is_like){
                $like->delete();
                return null;
            }
        }else{
            $like = new Like();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        if($update){
            $like->update();
        }else{
            $like->save();
        }
        return null;
    }

    public function postLikeComment(Request $request){
        $comment_id = $request['commentId'];
        $is_like = $request['isLike'] === 'true';
        $update = false;
        $comment = Comment::find($comment_id);
        if(!$comment){
            return null;
        }
        $user = Auth::user();
        $like = $user->likes_comment()->where('comment_id' , $comment_id)->first();
        if($like){
            $already_like = $like->like;
            $update = true;
            if($already_like == $is_like){
                $like->delete();
                return null;
            }
        }else{
            $like = new LikeComment();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->comment_id = $comment->id;
        if($update){
            $like->update();
        }else{
            $like->save();
        }
        return null;
    }

}