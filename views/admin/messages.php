<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Messages - Admin panel</title>
    <link rel="stylesheet" href="/vetclinic/public/css/css17.04.css">
    <link rel="stylesheet" href="/vetclinic/public/css/admin.css">
</head>
<body>

    <?php require_once __DIR__ . '/../partials/header.php'; ?>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <nav class="admin-nav">
                <ul>
                    <li><a href="/vetclinic/admin" class="active">📊 Dasboard</a></li>
                    <li><a href="/vetclinic/admin/users">👥 Users</a></li>
                    <li><a href="/vetclinic/admin/pets">🐾 Pets</a></li>
                    <li><a href="/vetclinic/admin/appointments">📅 Appointments</a></li>
                    <li><a href="/vetclinic/admin/personal">👨‍⚕️ Personal</a></li>
                    <li><a href="/vetclinic/admin/services">💊 Service</a></li>
                    <li><a href="/vetclinic/admin/messages" class="active">✉️ Messages</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Messages from users</h1>
                <?php if ($newCount > 0): ?>
                    <div class="new-badge">New: <?= $newCount ?></div>
                <?php endif; ?>
            </div>
            
            <div class="messages-list">
                <?php if (empty($messages)): ?>
                    <div class="empty-state">No messages</div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="message-item <?= $msg['status'] == 'new' ? 'unread' : '' ?>" data-id="<?= $msg['id'] ?>">
                            <div class="message-header">
                                <div class="message-sender">
                                    <strong><?= htmlspecialchars($msg['firstName'] ?? $msg['user_name'] ?? 'Guest') ?> <?= htmlspecialchars($msg['secondName'] ?? '') ?></strong>
                                    <span class="message-email"><?= htmlspecialchars($msg['email'] ?? '') ?></span>
                                    <?php if (!empty($msg['phone'])): ?>
                                        <span class="message-phone"><?= htmlspecialchars($msg['phone']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="message-date">
                                    <?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?>
                                    <span class="status-badge status-<?= $msg['status'] ?>">
                                        <?php
                                            switch($msg['status']) {
                                                case 'new': echo 'New'; break;
                                                case 'read': echo 'Readed'; break;
                                                case 'replied': echo 'Answered'; break;
                                                default: echo $msg['status'];
                                            }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="message-body">
                                <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            </div>
                            
                            <?php if ($msg['reply']): ?>
                                <div class="message-reply">
                                    <strong>Answer:</strong>
                                    <p><?= nl2br(htmlspecialchars($msg['reply'])) ?></p>
                                    <div class="reply-info">
                                        Answered: <?= date('d.m.Y H:i', strtotime($msg['replied_at'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="message-actions">
                                <?php if (!$msg['reply']): ?>
                                    <button class="btn-reply" onclick="openReplyModal(<?= $msg['id'] ?>)">To answer</button>
                                <?php endif; ?>
                                <button class="btn-delete" onclick="deleteMessage(<?= $msg['id'] ?>)">Delete</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="replyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reply to message</h3>
                <span class="close-btn" onclick="closeReplyModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="original-message" id="originalMessage"></div>
                <div class="form-group">
                    <label for="replyText">Your answer:</label>
                    <textarea id="replyText" class="reply-textarea" rows="5" placeholder="Enter your answer..."></textarea>
                </div>
                <div class="form-buttons">
                    <button class="btn-save" onclick="sendReply()">Send answer</button>
                    <button class="btn-cancel" onclick="closeReplyModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentMessageId = null;
        
        function openReplyModal(messageId) {
            currentMessageId = messageId;
            
            fetch('/vetclinic/api/message/get?id=' + messageId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const senderName = data.data.firstName + ' ' + (data.data.secondName || '');
                        const senderEmail = data.data.email || '';
                        document.getElementById('originalMessage').innerHTML = `
                            <strong>From:</strong> ${senderName} (${senderEmail})<br>
                            <strong>Message:</strong><br>
                            ${data.data.message.replace(/\n/g, '<br>')}
                        `;
                        document.getElementById('replyText').value = '';
                        document.getElementById('replyModal').style.display = 'flex';
                    }
                });
        }
        
        function sendReply() {
            const replyText = document.getElementById('replyText').value.trim();
            
            if (!replyText) {
                alert('Enter your answer');
                return;
            }
            
            fetch('/vetclinic/api/message/reply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: currentMessageId,
                    reply: replyText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Answer sent');
                    closeReplyModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }
        
        function closeReplyModal() {
            document.getElementById('replyModal').style.display = 'none';
            currentMessageId = null;
        }
        
        function deleteMessage(messageId) {
            if (confirm('Delete message?')) {
                fetch('/vetclinic/api/message/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: messageId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('replyModal');
            if (event.target === modal) {
                closeReplyModal();
            }
        }
    </script>
</body>
</html>