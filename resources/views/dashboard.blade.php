@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    @include('includes.message-block')
<section class="row new-post">
    <div class="col-md-6 col-md-offset-3">
    <header><h3>What do you have to say?</h3></header>
        <form action="{{ route('post.create') }}" method="post">
            <div class="form-group {{ $errors->has('body') ? 'has-error' : '' }}">
                <textarea class="form-control" name="body" id="new-post" rows="5" placeholder="Your Post"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
            {{ csrf_field() }}
        </form>
    </div>
</section>
    <section class="row posts">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>What other people say...</h3></header>
            @foreach($posts as $post)
            <article class="post" data-postid="{{ $post->id }}">
                <div class="media-left">
                <a href="#">
                    @if (Storage::disk('local')->has($post->user->first_name . '-' . $post->user->id . '.jpg'))
                        <img class="media-object" style="width: 64px; height: 64px;" src="{{ route('account.image', ['filename' => $post->user->first_name . '-' . $post->user->id . '.jpg']) }}" alt="" >
                    @else
                        <img class="media-object" style="width: 64px; height: 64px;" src="{{ route('account.image', ['filename' => 'empty-profile.jpg']) }}" alt="" >
                    @endif
                </a>
                </div>
                <div class="media-right">
                <strong>{{ $post->user->first_name }}</strong>
                <div class="info">
                    <a href="{{ route('single.post',['post_id' => $post->id]) }}" style="color: #aaa;">Created at {{ $post->created_at }} <span class="glyphicon glyphicon-time"></span></a>
                </div>
                <p>{{ $post->body }}</p>
                <div class="interaction">
                    <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 1 ? 'You like this post' : 'Like' : 'Like'  }}</a> |
                    <a href="#" class="like">{{ Auth::user()->likes()->where('post_id', $post->id)->first() ? Auth::user()->likes()->where('post_id', $post->id)->first()->like == 0 ? 'You don\'t like this post' : 'Dislike' : 'Dislike'  }}</a>
                    @if($post->user == Auth::user())
                    | <a href="#" class="edit">Edit</a> |
                    <a href="{{ route('post.delete',['post_id' => $post->id]) }}">Delete</a>
                    @endif
                </div>
                </div>
                @foreach($comments as $comment)
                    @if($post->id == $comment->post_id)
                        <div class="media">
                        <div class="comment" data-commentid="{{ $comment->id }}">
                            <div class="media-left">
                                <a href="#">
                                    @if (Storage::disk('local')->has($comment->user->first_name . '-' . $comment->user->id . '.jpg'))
                                        <img class="media-object" style="width: 64px; height: 64px;" src="{{ route('account.image', ['filename' => $comment->user->first_name . '-' . $comment->user->id . '.jpg']) }}" alt="" >
                                    @else
                                        <img class="media-object" style="width: 64px; height: 64px;" src="{{ route('account.image', ['filename' => 'empty-profile.jpg']) }}" alt="" >
                                    @endif
                                </a>
                            </div>
                            <div class="media-body">
                            <strong>{{ $comment->user->first_name }}</strong><p>{{ $comment->comment }}</p>
                            <p class="comment_time">Created at {{ $comment->created_at }} <span class="glyphicon glyphicon-time"></span></p>
                                <div class="interaction">
                                <a href="#" class="like_comment">{{ Auth::user()->likes_comment()->where('comment_id', $comment->id)->first() ? Auth::user()->likes_comment()->where('comment_id', $comment->id)->first()->like == 1 ? 'You like this comment' : 'Like' : 'Like'  }}</a> |
                                <a href="#" class="like_comment">{{ Auth::user()->likes_comment()->where('comment_id', $comment->id)->first() ? Auth::user()->likes_comment()->where('comment_id', $comment->id)->first()->like == 0 ? 'You don\'t like this comment' : 'Dislike' : 'Dislike'  }}</a>
                            @if($comment->user == Auth::user())
                                | <a href="#" class="edit_comment">Edit</a> |
                                <a href="{{ route('comment.delete',['comment_id' => $comment->id]) }}">Delete</a>
                            @endif
                                </div>
                            </div>
                        </div>
                        </div>
                    @endif
                @endforeach
                <form action="{{ route('comment.add') }}" method="post" class="form_comment">
                    <div class="form-group {{ $errors->has('post_comment') ? 'has-error' : '' }}">
                        <textarea class="form-control" name="post_comment" id="post_comment" rows="2" placeholder="Your Comment"></textarea>
                        <input type="hidden" name="post_comment_id" value="{{ $post->id }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                    {{ csrf_field() }}
                </form>
            </article>
           @endforeach
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Post</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="post-body">Edit The Post</label>
                            <textarea class="form-control" name="post-body" id="post-body" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-save">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="edit-comment-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Comment</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="comment-body">Edit The Commnet</label>
                            <textarea class="form-control" name="comment-body" id="comment-body" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit-modal-save">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        var token = '{{ Session::token() }}';
        var urlEdit = '{{ route('edit') }}';
        var urlCommentEdit = '{{ route('edit.comment') }}';
        var urlLike = '{{ route('like') }}';
        var urlLikeComment = '{{ route('like.comment') }}';
    </script>
@endsection