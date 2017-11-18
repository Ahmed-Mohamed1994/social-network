var postId = 0;
var postBodyElement = null;
var commentBodyElement = null;

// updated Post
$('.post').find('.interaction').find('.edit').on('click', function(event){
    event.preventDefault();
    postBodyElement = event.target.parentNode.parentNode.childNodes[5];
    var postBody = postBodyElement.textContent;
    postId = event.target.parentNode.parentNode.parentNode.dataset['postid'];
    $('#post-body').val(postBody);
    $('#edit-modal').modal();
});

$('#modal-save').on('click', function(){
    $.ajax({
            method: 'POST',
            url: urlEdit,
            data: {body: $('#post-body').val(), postId: postId, _token: token}
        })
        .done(function(msg){
            $(postBodyElement).text(msg['new_body']);
            $('#edit-modal').modal('hide');
        });
});
// End updated Post

// updated Comment
$('.edit_comment').on('click', function(event){
    event.preventDefault();
    commentBodyElement = event.target.parentNode.parentNode.childNodes[2];
    var commentBody = commentBodyElement.textContent;
    //console.log(commentBody);
    commentId = event.target.parentNode.parentNode.parentNode.dataset['commentid'];
    //console.log(commentId);
    $('#comment-body').val(commentBody);
    $('#edit-comment-modal').modal();
});

$('#edit-modal-save').on('click', function(){
    $.ajax({
            method: 'POST',
            url: urlCommentEdit,
            data: {comment_body: $('#comment-body').val(), commentId: commentId, _token: token}
        })
        .done(function(msg){
            $(commentBodyElement).text(msg['new_comment']);
            $('#edit-comment-modal').modal('hide');
        });
});
// End updated Comment

// Like Post
$('.like').on('click',function (event) {
    event.preventDefault();
    postId = event.target.parentNode.parentNode.parentNode.dataset['postid'];
    var isLike = event.target.previousElementSibling == null;
    $.ajax({
        method: 'POST',
        url: urlLike,
        data: {isLike: isLike, postId: postId, _token: token}
    })
        .done(function () {
            event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'You like this post' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this post' : 'Dislike';
            if (isLike) {
                event.target.nextElementSibling.innerText = 'Dislike';
            } else {
                event.target.previousElementSibling.innerText = 'Like';
            }
        });
});
// End Like Post

// Like Comment
$('.like_comment').on('click',function (event) {
    event.preventDefault();
    commentId = event.target.parentNode.parentNode.parentNode.dataset['commentid'];
    //console.log(commentId);
    var isLike = event.target.previousElementSibling == null;
    //console.log(isLike);
    $.ajax({
            method: 'POST',
            url: urlLikeComment,
            data: {isLike: isLike, commentId: commentId, _token: token}
        })
        .done(function () {
            event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'You like this comment' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this comment' : 'Dislike';
            if (isLike) {
                event.target.nextElementSibling.innerText = 'Dislike';
            } else {
                event.target.previousElementSibling.innerText = 'Like';
            }
        });
});
// End Like Comment