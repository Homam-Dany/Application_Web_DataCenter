<div id="ai-chatbot-widget" class="chatbot-closed" data-menu-url="{{ route('chatbot.menu') }}"
    data-ask-url="{{ route('chatbot.ask') }}">

    <!-- Bubble Button -->
    <div id="chatbot-trigger" class="chatbot-trigger">
        <i class="fas fa-robot"></i>
        <span class="notification-dot" style="display: none;"></span>
    </div>

    <!-- Chat Window -->
    <div class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-title">
                <i class="fas fa-brain"></i>
                <span>Assistant DataCenter</span>
            </div>
            <button id="chatbot-close" class="chatbot-close-btn"><i class="fas fa-times"></i></button>
        </div>

        <!-- Messages Area -->
        <div id="chatbot-messages" class="chatbot-messages">
            <div class="message bot-message">
                Bonjour ! Je suis l'IA du DataCenter. Posez-moi une question ou cliquez sur une suggestion ci-dessous :
            </div>

            <!-- Suggestions Chips -->
            <div class="chatbot-suggestions" id="chatbot-suggestions-container">
                <!-- Loaded dynamically -->
                <div class="loading-suggestions"><i class="fas fa-circle-notch fa-spin"></i> Chargement du menu...</div>
            </div>
        </div>

        <!-- Image Preview Area -->
        <div id="chatbot-image-preview-container" style="display: none; padding: 5px 15px; background: #f8fafc; border-top: 1px solid #e2e8f0; align-items: center; gap: 10px;">
            <img id="chatbot-preview-img" src="" style="height: 40px; border-radius: 4px; object-fit: cover;">
            <button id="chatbot-clear-img" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px;"><i class="fas fa-times"></i></button>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input-area">
            <label for="chatbot-file-upload" id="chatbot-file-btn" style="cursor: pointer; color: #94a3b8; font-size: 20px; display: flex; align-items: center; justify-content: center; padding: 0 5px; transition: color 0.2s;">
                <i class="fas fa-paperclip"></i>
            </label>
            <input type="file" id="chatbot-file-upload" accept="image/*" style="display: none;">
            
            <input type="text" id="chatbot-input" placeholder="Posez votre question..." autocomplete="off">
            <button id="chatbot-send"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<style>
    /* Widget Container */
    #ai-chatbot-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Trigger Button */
    .chatbot-trigger {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        transition: all 0.3s ease;
        position: relative;
    }

    .chatbot-trigger:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.6);
    }

    /* Chat Window */
    .chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 320px;
        /* Taille réduite demandée */
        height: 420px;
        /* Taille réduite demandée */
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transform-origin: bottom right;
        transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
        opacity: 0;
        transform: scale(0);
        pointer-events: none;
    }

    .chatbot-open .chatbot-window {
        opacity: 1;
        transform: scale(1);
        pointer-events: all;
    }

    .chatbot-open .chatbot-trigger {
        transform: rotate(90deg) scale(0.8);
        background: #ef4444;
    }

    .chatbot-open .chatbot-trigger i::before {
        content: "\f00d";
        /* Change icon to X */
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        padding: 12px 15px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chatbot-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 16px;
    }

    .chatbot-close-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 18px;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .chatbot-close-btn:hover {
        opacity: 1;
    }

    /* Messages */
    .chatbot-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
        white-space: pre-line;
        /* Handle newlines in answers */
    }

    .bot-message {
        background: white;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .user-message {
        background: #4f46e5;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 5px rgba(79, 70, 229, 0.3);
    }

    .loading-message {
        font-style: italic;
        color: #94a3b8;
        font-size: 12px;
        text-align: center;
        margin-top: 10px;
    }

    /* Suggestions Menu Style */
    .chatbot-suggestions {
        display: flex;
        flex-direction: column;
        /* Vertical stack like Telegram menu */
        gap: 8px;
        margin-top: 10px;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
    }

    .suggestion-chip {
        background: white;
        color: #4338ca;
        border: 1px solid #e0e7ff;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .suggestion-chip:hover {
        background: #e0e7ff;
        border-color: #c7d2fe;
        transform: translateX(2px);
    }

    .loading-suggestions {
        text-align: center;
        color: #94a3b8;
        font-size: 12px;
        padding: 10px;
    }

    /* Input */
    .chatbot-input-area {
        padding: 15px;
        background: white;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
    }

    #chatbot-file-btn:hover {
        color: #4f46e5;
    }

    #chatbot-input {
        flex: 1;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        padding: 10px 15px;
        outline: none;
        transition: border-color 0.2s;
        font-size: 14px;
        min-width: 0; /* Prevents overflow */
    }

    #chatbot-input:focus {
        border-color: #6366f1;
    }

    #chatbot-send {
        background: #4f46e5;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    #chatbot-send:hover {
        background: #4338ca;
    }

    #chatbot-send:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const widget = document.getElementById('ai-chatbot-widget');
        const trigger = document.getElementById('chatbot-trigger');
        const closeBtn = document.getElementById('chatbot-close');
        const messagesContainer = document.getElementById('chatbot-messages');
        const input = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');
        const fileInput = document.getElementById('chatbot-file-upload');
        const previewContainer = document.getElementById('chatbot-image-preview-container');
        const previewImg = document.getElementById('chatbot-preview-img');
        const clearImgBtn = document.getElementById('chatbot-clear-img');
        const suggestionsContainer = document.getElementById('chatbot-suggestions-container');

        let isOpen = false;
        let menuLoaded = false;
        let selectedImageBase64 = null;

        // Handle Image Selection
        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        selectedImageBase64 = event.target.result;
                        previewImg.src = selectedImageBase64;
                        previewContainer.style.display = 'flex';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (clearImgBtn) {
            clearImgBtn.addEventListener('click', () => {
                selectedImageBase64 = null;
                previewContainer.style.display = 'none';
                fileInput.value = ''; // clear input
            });
        }

        // Toggle Chat
        function toggleChat() {
            isOpen = !isOpen;
            if (isOpen) {
                widget.classList.add('chatbot-open');
                setTimeout(() => input.focus(), 300);
                if (!menuLoaded) loadMenu();
            } else {
                widget.classList.remove('chatbot-open');
            }
        }

        // Event Listeners direct
        if (trigger) {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleChat();
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleChat();
            });
        }

        // Load Menu from Backend
        async function loadMenu() {
            try {
                // Utiliser l'URL du data-attribute
                const menuUrl = widget.getAttribute('data-menu-url');
                const response = await fetch(menuUrl);
                const menuItems = await response.json();

                if (suggestionsContainer) {
                    suggestionsContainer.innerHTML = ''; // Clear loading

                    menuItems.forEach(item => {
                        const btn = document.createElement('button');
                        btn.classList.add('suggestion-chip');
                        btn.textContent = item.text;
                        btn.addEventListener('click', () => {
                            if (input) input.value = item.text;
                            sendMessage();
                        });
                        suggestionsContainer.appendChild(btn);
                    });
                }

                menuLoaded = true;
            } catch (error) {
                console.error("Erreur chargement menu", error);
                if (suggestionsContainer) suggestionsContainer.innerHTML = '<div class="loading-suggestions">Erreur de chargement du menu.</div>';
            }
        }

        // Send Message
        async function sendMessage() {
            const text = input.value.trim();
            if (!text && !selectedImageBase64) return; // Prevent empty send

            // 1. Add User Message
            let displayMsg = text;
            if (selectedImageBase64) {
                displayMsg += "\n[Image attachée]";
            }
            addMessage(displayMsg, 'user');
            
            // Capture image before clearing UI
            const imageToSend = selectedImageBase64;
            
            // Clear UI
            input.value = '';
            input.disabled = true;
            sendBtn.disabled = true;
            if (clearImgBtn) clearImgBtn.click(); // clear image preview

            // 2. Add Loading Indicator
            const loadingId = addLoading();

            try {
                const askUrl = widget.getAttribute('data-ask-url');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // 3. Call API
                const response = await fetch(askUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: text, image: imageToSend })
                });

                const data = await response.json();

                // 4. Remove Loading & Add Bot Response
                removeMessage(loadingId);

                if (data.success) {
                    addMessage(data.message, 'bot');
                } else {
                    addMessage("Oups, j'ai eu un petit problème technique.", 'bot');
                }

            } catch (error) {
                console.error(error);
                removeMessage(loadingId);
                addMessage("Erreur de connexion au serveur.", 'bot');
            } finally {
                input.disabled = false;
                sendBtn.disabled = false;
                input.focus();
            }
        }

        if (sendBtn) sendBtn.addEventListener('click', sendMessage);
        if (input) input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        // Helper Functions
        function addMessage(text, type) {
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('message', type === 'user' ? 'user-message' : 'bot-message');
            
            // Simple markdown parsing for the bot
            if (type === 'bot') {
                let parsedText = text
                    // Parse markdown code blocks ```lang ... ``` -> with a download button
                    .replace(/```(\w*)\n([\s\S]*?)```/g, (match, lang, code) => {
                        const escapedCode = code.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        const blob = new Blob([code], { type: 'text/plain' });
                        const url = URL.createObjectURL(blob);
                        const filename = 'code_' + Date.now() + (lang ? '.' + lang : '.txt');
                        return `<div style="background:#1e293b; color:#fff; border-radius:8px; padding:10px; margin-top:5px;">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:5px; border-bottom:1px solid #334155; padding-bottom:5px;">
                                        <span style="font-size:12px; color:#94a3b8;">${lang || 'code'}</span>
                                        <a href="${url}" download="${filename}" style="color:#60a5fa; font-size:12px; text-decoration:none;">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                    <pre style="margin:0; overflow-x:auto; font-size:13px;"><code>${escapedCode}</code></pre>
                                </div>`;
                    })
                    // Parse markdown images ![alt](url) -> <img src="url" alt="alt">
                    .replace(/!\[([^\]]*)\]\(([^)]+)\)/g, '<img src="$2" alt="$1" style="max-width:100%; border-radius:8px; margin-top:5px;">')
                    // Parse markdown links [text](url) -> <a href="url">text</a>
                    .replace(/\[([^\]]+)\]\(([^)]+)\)(\{([^}]+)\})?/g, (match, title, url, _, attributes) => {
                        let cls = '';
                        if (attributes && attributes.includes('.btn')) {
                            cls = 'class="btn btn-sm btn-primary" style="display:inline-block; margin-top:5px; padding:5px 10px; background:#4f46e5; color:white; border-radius:4px; text-decoration:none;"';
                        }
                        // Avoid double matching if it was already parsed as an image
                        if (match.startsWith('!')) return match;
                        return `<a href="${url}" ${cls}>${title}</a>`;
                    })
                    // Parse bold
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                
                msgDiv.innerHTML = parsedText;
            } else {
                msgDiv.innerText = text;
            }

            messagesContainer.appendChild(msgDiv);
            scrollToBottom();
            return msgDiv;
        }

        function addLoading() {
            const id = 'loading-' + Date.now();
            const msgDiv = document.createElement('div');
            msgDiv.id = id;
            msgDiv.classList.add('loading-message');
            msgDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> L\'IA réfléchit...';
            messagesContainer.appendChild(msgDiv);
            scrollToBottom();
            return id;
        }

        function removeMessage(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });
</script>