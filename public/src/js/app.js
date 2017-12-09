$('.like').on('click', function (event) {
    event.preventDefault();
    var tweetId = event.target.parentNode.parentNode.parentNode.dataset['tweetid'];
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
        });
});