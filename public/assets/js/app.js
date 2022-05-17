class App {

    constructor() {
        this.displayCommentForm();
        this.handleCommentForm();
        this.likeAndDislike();
    }

    //Commentaires
    displayCommentForm() {
        const btn = document.querySelector('.comment-btn');
        const commentForm = document.querySelector('.home__partner__commentForm');
        if(commentForm === null) {
            return;
        }
        btn.addEventListener('click', () => commentForm.classList.toggle('visible'));
    }

    handleCommentForm() {
        const commentForm = document.querySelector('form.comment-form');
        if(commentForm === null) {
            return;
        }

        commentForm.addEventListener('submit', async(e) => {
            e.preventDefault();
            
            const response = await fetch('/ajax/comment', {
                method: 'POST',
                body: new FormData(e.target)
            });

            if(!response.ok) {
                return;
            }

            const json = await response.json();
            console.log(json.code);
            console.log(json.message);

            const commentList = document.querySelector('.comment-list');
            const commentCount = document.querySelector('.comment-count');
            const commentContent = document.querySelector('#comment_comment');

            if(json.code === 'COMMENT_EDITED_SUCCESSFULLY') {
                document.querySelector(`#comment_${json.id}`).remove();
            } else if(json.code === 'COMMENT_ADDED_SUCCESSFULLY') {
                commentCount.textContent = json.commentsNumber;
            }
            if(json.code === 'COMMENT_ADDED_SUCCESSFULLY' || json.code === 'COMMENT_EDITED_SUCCESSFULLY') {                
                commentList.insertAdjacentHTML('afterbegin', json.message);
                commentContent.value = '';
            }
        });
    }

    //Like & dislike
    likeAndDislike() {
        const like = document.querySelector('.partner_like');
        const dislike = document.querySelector('.partner_dislike');
        if(like === null) {
            return;
        }

        like.addEventListener('click', async (e) => setNote(e, 1));
        dislike.addEventListener('click', async (e) => setNote(e, -1));

        const setNote = async (e, note) => {
            console.log(e.target.getAttribute('url'));
            let form = new FormData();
            form.append('note', note);
            const response = await fetch(`${e.target.getAttribute('url')}`, {
                method: 'POST',
                body: form
            });

            console.log(response);

            if(!response.ok) {
                return;
            }

            const json = await response.json();
        };
    }
}

document.addEventListener('DOMContentLoaded', () => new App())