<div class="messages-container">
    <h1>New Message</h1>
    
    <form method="POST" action="/messages">
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required>
        </div>

        <div class="form-group">
            <label for="content">Message</label>
            <textarea id="content" name="content" rows="6" required></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Send</button>
            <a href="/messages" class="btn">Cancel</a>
        </div>
    </form>
</div>