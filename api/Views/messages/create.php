<div class="messages-page">
    <div class="messages-header">
        <h1 class="messages-title">New Message</h1>
    </div>
    
    <form method="POST" action="/messages" class="new-message-form">
        <?= \Api\Core\View::csrfField() ?>
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="What's this about?" required>
        </div>

        <div class="form-group">
            <label for="content">Message</label>
            <textarea id="content" name="content" placeholder="Write your message..." required></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Send Message</button>
            <a href="/messages" class="btn">Cancel</a>
        </div>
    </form>
</div>