const API_BASE = './api';

// Toggle comments section
async function toggleComments(reportId) {
    const commentsSection = document.getElementById(`comments-${reportId}`);
    const commentsList = commentsSection.querySelector('.comments-list');
    
    if (commentsSection.style.display === 'none') {
        commentsSection.style.display = 'block';

        try {
            const response = await fetch(`${API_BASE}/comments.php?report_id=${reportId}`);
            const result = await response.json();
            
            if (result.success && result.data.length > 0) {
                commentsList.innerHTML = result.data.map(comment => `
                    <div class="comment-item">
                        <div class="comment-header">
                            <strong>${escapeHtml(comment.user_name)}</strong>
                            <span class="comment-date">${formatDate(comment.created_at)}</span>
                        </div>
                        <div class="comment-body">${escapeHtml(comment.comment)}</div>
                    </div>
                `).join('');
            } else {
                commentsList.innerHTML = '<p class="no-comments">هنوز نظری ثبت نشده است</p>';
            }
        } catch (error) {
            console.error('Error loading comments:', error);
            commentsList.innerHTML = '<p class="error">خطا در بارگذاری نظرات</p>';
        }
    } else {
        commentsSection.style.display = 'none';
    }
}

// Submit comment
async function submitComment(event, reportId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    const data = {
        report_id: reportId,
        user_name: formData.get('user_name').trim(),
        user_email: formData.get('user_email').trim(),
        comment: formData.get('comment').trim()
    };
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'در حال ارسال...';
    
    try {
        const response = await fetch(`${API_BASE}/comments.php`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            form.reset();

            toggleComments(reportId);
            setTimeout(() => toggleComments(reportId), 100);

            const commentsBtn = document.querySelector(`[data-report-id="${reportId}"] .comments-btn`);
            const countMatch = commentsBtn.textContent.match(/\d+/);
            if (countMatch) {
                const newCount = parseInt(countMatch[0]) + 1;
                commentsBtn.textContent = `💬 نظرات (${newCount})`;
            }
        } else {
            alert('خطا: ' + result.message);
        }
    } catch (error) {
        console.error('Error submitting comment:', error);
        alert('خطا در ارسال نظر. لطفا دوباره تلاش کنید.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'ارسال نظر';
    }
}

// React to report (like/dislike)
async function reactToReport(reportId, reactionType) {
    const email = prompt('لطفا ایمیل خود را وارد کنید:');
    
    if (!email) {
        return; // User cancelled
    }
    
    if (!validateEmail(email)) {
        alert('لطفا یک ایمیل معتبر وارد کنید');
        return;
    }
    
    const reportCard = document.querySelector(`[data-report-id="${reportId}"]`);
    const likeBtn = reportCard.querySelector('.like-btn');
    const dislikeBtn = reportCard.querySelector('.dislike-btn');

    likeBtn.disabled = true;
    dislikeBtn.disabled = true;
    
    try {
        const response = await fetch(`${API_BASE}/reactions.php`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                report_id: reportId,
                user_email: email,
                reaction_type: reactionType
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert('خطا: ' + result.message);
        }
    } catch (error) {
        console.error('Error reacting:', error);
        alert('خطا در ثبت واکنش. لطفا دوباره تلاش کنید.');
    } finally {
        likeBtn.disabled = false;
        dislikeBtn.disabled = false;
    }
}

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fa-IR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}