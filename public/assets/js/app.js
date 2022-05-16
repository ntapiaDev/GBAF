class App {

    constructor() {
        this.displayCommentForm();
        this.handleCommentForm();
    }

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

            if(json.code === 'COMMENT_ADDED_SUCCESSFULLY') {
                const commentList = document.querySelector('.comment-list');
                const commentCount = document.querySelector('.comment-count');
                const commentContent = document.querySelector('#comment_comment');
                commentList.insertAdjacentHTML('afterbegin', json.message);
                commentCount.textContent = json.commentsNumber;
                commentContent.value = '';
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => new App())