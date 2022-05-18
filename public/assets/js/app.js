class App {

    constructor() {
        this.displayCommentForm();
        this.handleCommentForm();
        this.likeAndDislike();
        this.displayResetForm();
        this.resetPassword();
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
        const like = document.querySelector('.partner-like');
        const dislike = document.querySelector('.partner-dislike');
        if(like === null) {
            return;
        }

        like.addEventListener('click', async (e) => setNote(e, 1));
        dislike.addEventListener('click', async (e) => setNote(e, -1));

        const setNote = async (e, note) => {
            let form = new FormData();
            form.append('note', note);
            const response = await fetch(`${e.target.getAttribute('url')}`, {
                method: 'POST',
                body: form
            });

            if(!response.ok) {
                return;
            }

            const json = await response.json();
            console.log(json);

            const like = document.querySelector('.partner-like');
            const likeNumber = document.querySelector('.partner-like span');
            const dislike = document.querySelector('.partner-dislike');
            const dislikeNumber = document.querySelector('.partner-dislike span');
            if(json.code === 'NOTE_ADDED_SUCCESSFULLY') {
                if(json.note == 1) {
                    like.classList.add('partner-liked');
                    likeNumber.textContent = json.likeNumber;
                } else if(json.note == -1) {
                    dislike.classList.add('partner-disliked');
                    dislikeNumber.textContent = json.dislikeNumber;
                }
            } else if(json.code === 'NOTE_EDITED_SUCCESSFULLY') {
                if(json.note == 1) {
                    like.classList.add('partner-liked');
                    likeNumber.textContent = json.likeNumber;
                    dislike.classList.remove('partner-disliked');
                    dislikeNumber.textContent = json.dislikeNumber;
                } else if(json.note == -1) {
                    like.classList.remove('partner-liked');
                    likeNumber.textContent = json.likeNumber;
                    dislike.classList.add('partner-disliked');
                    dislikeNumber.textContent = json.dislikeNumber;
                }
            } else if(json.code === 'NOTE_DELETED_SUCCESSFULLY') {
                if(json.note == 1) {
                    like.classList.remove('partner-liked');
                    likeNumber.textContent = json.likeNumber;
                } else if(json.note == -1) {
                    dislike.classList.remove('partner-disliked');
                    dislikeNumber.textContent = json.dislikeNumber;
                }
            }
        };
    }

    //Reset Password AJAX

    displayResetForm() {
        const displayBtn = document.querySelector('.display-reset');
        const resetForm = document.querySelector('.login__resetPassword');
        if(resetForm === null) {
            return;
        }
        displayBtn.addEventListener('click', () => resetForm.classList.toggle('reset-visible'));
    }

    resetPassword() {
        const resetForm = document.querySelector('.reset-form')
        if(resetForm === null) {
            return;
        }
        resetForm.addEventListener('submit', async(e) => {
            e.preventDefault();

            const response = await fetch('/connexion/reset', {
                method: 'POST',
                body: new FormData(e.target)
            });

            const json = await response.json();
            console.log(json);

            const message = document.querySelector('.reset__message');
            if(json.code !== 'PASSWORD CHANGED') {
                message.classList.add('error');
                message.classList.remove('success');
            } else {
                message.classList.add('success');
                message.classList.remove('error');
            }
            message.textContent = json.message;
        })
    }
}

document.addEventListener('DOMContentLoaded', () => new App())