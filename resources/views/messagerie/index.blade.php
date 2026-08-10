@extends('layouts.app')

@section('header')
<div class="header-container" style="display:flex; justify-content:space-between; align-items:center; padding:20px;">
    <h2>
        <i class="fas fa-comments" style="color: #4f46e5; margin-right: 10px;"></i> Messagerie Interne
    </h2>
</div>
@endsection

@section('content')
<div class="chat-wrapper">
    <div class="chat-container">
        
        <!-- Left Pane: Contacts List -->
        <div class="chat-sidebar">
            <div class="sidebar-header">
                <h3>Contacts</h3>
                <input type="text" placeholder="Rechercher..." id="search-contact">
            </div>
            <div class="contacts-list">
                @foreach($users as $user)
                    @php
                        $unread = $unreadCounts[$user->id] ?? 0;
                    @endphp
                    <div class="contact-card" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                        <div class="contact-avatar">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="contact-info">
                            <h4 class="contact-name">{{ $user->name }}</h4>
                            <span class="contact-role">{{ ucfirst($user->role) }}</span>
                        </div>
                        @if($unread > 0)
                            <div class="contact-unread-badge" id="badge-{{ $user->id }}">{{ $unread }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right Pane: Chat Window -->
        <div class="chat-main">
            <!-- Chat Header -->
            <div id="chat-header" class="chat-header" style="display: none;">
                <div id="chat-header-avatar" class="chat-header-avatar">A</div>
                <div class="chat-header-info">
                    <h3 id="chat-header-name">Utilisateur</h3>
                    <span class="chat-status"><span class="status-dot"></span> En ligne</span>
                </div>
            </div>
            
            <!-- Chat Messages -->
            <div id="chat-messages" class="chat-messages" style="display: none;">
                <!-- Messages will be injected here via JS -->
            </div>
            
            <!-- Empty State -->
            <div id="chat-empty-state" class="chat-empty-state">
                <i class="fas fa-paper-plane empty-icon"></i>
                <p>Sélectionnez un contact pour commencer à discuter</p>
            </div>

            <!-- Chat Input -->
            <div id="chat-input-area" class="chat-input-area" style="display: none;">
                <textarea id="chat-input-text" rows="1" placeholder="Écrivez un message..."></textarea>
                <button id="chat-send-btn" class="chat-send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>
</div>

<style>
/* Chat Layout Variables */
:root {
    --chat-border: #e2e8f0;
    --chat-bg-sidebar: #f8fafc;
    --chat-bg-main: #ffffff;
    --chat-bg-msg: #f1f5f9;
    --chat-primary: #4f46e5;
    --chat-primary-hover: #4338ca;
    --chat-text-main: #1e293b;
    --chat-text-muted: #64748b;
}

/* Wrapper and Container */
.chat-wrapper {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    height: calc(100vh - 150px);
}

.chat-container {
    display: flex;
    background: var(--chat-bg-main);
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--chat-border);
    height: 100%;
    overflow: hidden;
}

/* Sidebar (Left Pane) */
.chat-sidebar {
    width: 320px;
    background: var(--chat-bg-sidebar);
    border-right: 1px solid var(--chat-border);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid var(--chat-border);
    background: #ffffff;
}

.sidebar-header h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: var(--chat-text-main);
}

#search-contact {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid var(--chat-border);
    border-radius: 8px;
    outline: none;
    transition: border-color 0.2s;
    font-size: 14px;
    box-sizing: border-box;
}

#search-contact:focus {
    border-color: var(--chat-primary);
}

.contacts-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.contact-card {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.2s;
    margin-bottom: 5px;
}

.contact-card:hover {
    background: #eef2ff;
}

.contact-card.active {
    background: #eef2ff;
    border-left: 4px solid var(--chat-primary);
}

.contact-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #c7d2fe;
    color: var(--chat-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    flex-shrink: 0;
    margin-right: 15px;
}

.contact-info {
    flex: 1;
    overflow: hidden;
}

.contact-name {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--chat-text-main);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.contact-role {
    font-size: 12px;
    color: var(--chat-text-muted);
}

.contact-unread-badge {
    background: #ef4444;
    color: white;
    font-size: 11px;
    font-weight: bold;
    min-width: 20px;
    height: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    margin-left: 10px;
}

/* Main Chat Window (Right Pane) */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.chat-header {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--chat-border);
    background: var(--chat-bg-sidebar);
}

.chat-header-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--chat-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
    margin-right: 15px;
}

.chat-header-info h3 {
    margin: 0;
    font-size: 16px;
    color: var(--chat-text-main);
}

.chat-status {
    font-size: 12px;
    color: #10b981;
    display: flex;
    align-items: center;
    font-weight: 600;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    margin-right: 5px;
}

/* Empty State */
.chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--chat-text-muted);
}

.empty-icon {
    font-size: 60px;
    color: #e2e8f0;
    margin-bottom: 20px;
}

/* Messages Area */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: var(--chat-bg-sidebar);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.msg-wrapper {
    display: flex;
    flex-direction: column;
    max-width: 70%;
}

.msg-me {
    align-self: flex-end;
}

.msg-other {
    align-self: flex-start;
}

.msg-bubble {
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
}

.msg-me .msg-bubble {
    background: var(--chat-primary);
    color: white;
    border-bottom-right-radius: 2px;
}

.msg-other .msg-bubble {
    background: #ffffff;
    color: var(--chat-text-main);
    border: 1px solid var(--chat-border);
    border-bottom-left-radius: 2px;
}

.msg-time {
    font-size: 11px;
    color: var(--chat-text-muted);
    margin-top: 5px;
}

.msg-me .msg-time {
    text-align: right;
}

/* Input Area */
.chat-input-area {
    display: flex;
    align-items: flex-end;
    padding: 20px;
    background: #ffffff;
    border-top: 1px solid var(--chat-border);
}

#chat-input-text {
    flex: 1;
    border: 1px solid var(--chat-border);
    border-radius: 24px;
    padding: 12px 20px;
    font-size: 14px;
    resize: none;
    outline: none;
    background: var(--chat-bg-msg);
    transition: border-color 0.2s;
    font-family: inherit;
    box-sizing: border-box;
}

#chat-input-text:focus {
    border-color: var(--chat-primary);
    background: #ffffff;
}

.chat-send-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--chat-primary);
    color: white;
    border: none;
    margin-left: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: transform 0.2s, background 0.2s;
    flex-shrink: 0;
}

.chat-send-btn:hover {
    background: var(--chat-primary-hover);
    transform: scale(1.05);
}

/* Dark Mode Overrides (Optional based on app theme) */
[data-theme="dark"] .chat-container {
    background: #1e293b;
    border-color: #334155;
}
[data-theme="dark"] .chat-sidebar {
    background: #0f172a;
    border-color: #334155;
}
[data-theme="dark"] .sidebar-header, [data-theme="dark"] .chat-header, [data-theme="dark"] .chat-input-area, [data-theme="dark"] .chat-main {
    background: #1e293b;
    border-color: #334155;
}
[data-theme="dark"] .sidebar-header h3, [data-theme="dark"] .contact-name, [data-theme="dark"] .chat-header-info h3 {
    color: #f1f5f9;
}
[data-theme="dark"] #search-contact {
    background: #0f172a;
    border-color: #334155;
    color: #f1f5f9;
}
[data-theme="dark"] .contact-card:hover, [data-theme="dark"] .contact-card.active {
    background: #334155;
}
[data-theme="dark"] .chat-messages {
    background: #0f172a;
}
[data-theme="dark"] .msg-other .msg-bubble {
    background: #334155;
    color: #f1f5f9;
    border-color: #475569;
}
[data-theme="dark"] #chat-input-text {
    background: #334155;
    border-color: #475569;
    color: #f1f5f9;
}
[data-theme="dark"] #chat-input-text:focus {
    background: #1e293b;
    border-color: var(--chat-primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentUserId = null;
    let authUserId = {{ Auth::id() }};
    let pollInterval = null;

    const contacts = document.querySelectorAll('.contact-card');
    const chatHeader = document.getElementById('chat-header');
    const chatMessages = document.getElementById('chat-messages');
    const chatInputArea = document.getElementById('chat-input-area');
    const chatEmptyState = document.getElementById('chat-empty-state');
    const chatHeaderName = document.getElementById('chat-header-name');
    const chatHeaderAvatar = document.getElementById('chat-header-avatar');
    const chatInputText = document.getElementById('chat-input-text');
    const chatSendBtn = document.getElementById('chat-send-btn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const searchContact = document.getElementById('search-contact');

    // Search contacts
    searchContact.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        contacts.forEach(contact => {
            const name = contact.getAttribute('data-user-name').toLowerCase();
            if(name.includes(term)) {
                contact.style.display = 'flex';
            } else {
                contact.style.display = 'none';
            }
        });
    });

    // Handle Contact Selection
    contacts.forEach(contact => {
        contact.addEventListener('click', function() {
            // UI Selection state
            contacts.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            currentUserId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            // Hide badge when clicking on contact
            const badge = document.getElementById('badge-' + currentUserId);
            if(badge) badge.style.display = 'none';

            // Update Header
            chatHeaderName.textContent = userName;
            chatHeaderAvatar.textContent = userName.charAt(0).toUpperCase();
            
            // Toggle Views
            chatEmptyState.style.display = 'none';
            chatHeader.style.display = 'flex';
            chatMessages.style.display = 'flex';
            chatInputArea.style.display = 'flex';

            fetchMessages();

            // Clear previous polling and start new one
            if(pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(fetchMessages, 3000);
        });
    });

    async function fetchMessages() {
        if(!currentUserId) return;
        try {
            const response = await fetch(`/messagerie/${currentUserId}/messages`);
            const messages = await response.json();
            
            // Ensure scrolled to bottom only if user hasn't scrolled up
            const isAtBottom = chatMessages.scrollHeight - chatMessages.scrollTop <= chatMessages.clientHeight + 50;

            chatMessages.innerHTML = ''; 
            
            if(messages.length === 0) {
                chatMessages.innerHTML = `<div style="text-align:center; color:var(--chat-text-muted); font-size:12px; margin-top:20px;">Commencez la conversation avec ${chatHeaderName.textContent}</div>`;
            }

            messages.forEach(msg => {
                const isMe = msg.sender_id == authUserId;
                const wrapperClass = isMe ? 'msg-me' : 'msg-other';
                const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

                const html = `
                    <div class="msg-wrapper ${wrapperClass}">
                        <div class="msg-bubble">
                            ${escapeHtml(msg.body).replace(/\n/g, '<br>')}
                        </div>
                        <div class="msg-time">${time}</div>
                    </div>
                `;
                chatMessages.innerHTML += html;
            });

            if(isAtBottom) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        } catch (error) {
            console.error("Erreur de récupération des messages", error);
        }
    }

    async function sendMessage() {
        const body = chatInputText.value.trim();
        if(!body || !currentUserId) return;

        chatInputText.value = '';
        chatInputText.style.height = 'auto'; // reset height if auto-expanding
        
        try {
            const response = await fetch('/messagerie/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    receiver_id: currentUserId,
                    body: body
                })
            });

            if(response.ok) {
                fetchMessages();
                setTimeout(() => {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 100);
            }
        } catch (error) {
            console.error("Erreur d'envoi", error);
        }
    }

    chatSendBtn.addEventListener('click', sendMessage);
    chatInputText.addEventListener('keypress', function(e) {
        if(e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function escapeHtml(unsafe) {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
});
</script>
@endsection
