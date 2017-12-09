$('.like').on('click', function (event) {
    event.preventDefault();
    var tweetId = event.target.parentNode.parentNode.dataset['tweetid'];
    var isLikeText = event.target.innerText;
    // console.log(isLikeText);
    $.ajax({
        method: 'POST',
        url: urlLike,
        data: { isLikeText: isLikeText, tweetId: tweetId, _token: token}
    })
        .done(function (msg) {
            if(isLikeText == 'Like'){
                event.target.innerText = 'DisLike'
            }else{
                event.target.innerText = 'Like'
            }
            /*event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'You like this post' : 'Like' : event.target.innerText == 'Dislike' ? 'You don\'t like this post' : 'Dislike';
            if(isLike){
                event.target.nextElementSibling.innerText = 'Dislike';
            }else {
                event.target.previousElementSibling.innerText = 'Like';
            }*/
        });
});