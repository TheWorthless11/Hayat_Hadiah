<x-app-layout>
    {{-- Page-specific title --}}
    @section('title', 'AI Islamic Assistant - Hayat Hadiah')

    {{-- Page-specific styles pushed to the layout --}}
    @push('styles')
    <style>
        /* These styles will only apply to the chatbot page */
        body {
            font-size: 0.9rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .chat-container {
            max-width: 900px;
            margin: 2rem auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            height: calc(100vh - 120px); /* Adjusted height for better spacing */
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #0f9b8e 0%, #0c7a6e 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .chat-header h1 {
            font-size: 1.5rem;
            margin: 0 0 0.5rem 0;
            font-weight: 600;
        }
        
        .chat-header p {
            font-size: 0.85rem;
            margin: 0;
            opacity: 0.9;
        }
        
        .chat-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }
        
        .status-dot {
            width: 10px;
            height: 10px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .status-dot.offline {
            background: #f87171;
            animation: none;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8fafc;
            scroll-behavior: smooth;
        }
        
        .message {
            display: flex;
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .message.user {
            justify-content: flex-end;
        }
        
        .message.bot {
            justify-content: flex-start;
        }
        
        .message-content {
            max-width: 70%;
            padding: 0.9rem 1.2rem;
            border-radius: 18px;
            font-size: 0.85rem;
            line-height: 1.5;
            position: relative;
        }
        
        .message.user .message-content {
            background: linear-gradient(135deg, #0f9b8e 0%, #0c7a6e 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.bot .message-content {
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        
        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin: 0 0.7rem;
            flex-shrink: 0;
            color: white;
        }
        
        .message.user .message-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            order: 2;
        }
        
        .message.bot .message-avatar {
            background: linear-gradient(135deg, #0f9b8e 0%, #0c7a6e 100%);
            order: 1;
        }
        
        .typing-indicator {
            display: none;
            align-items: center;
            gap: 0.5rem;
            padding: 0.9rem 1.2rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            max-width: 70px;
        }
        
        .typing-indicator.active {
            display: flex;
        }
        
        .typing-dot {
            width: 8px;
            height: 8px;
            background: #64748b;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        
        .chat-input-container {
            padding: 1rem 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }
        
        .quick-replies {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.8rem;
        }
        
        .quick-reply-btn {
            padding: 0.4rem 0.9rem;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .quick-reply-btn:hover {
            background: #0f9b8e;
            color: white;
            border-color: #0f9b8e;
        }
        
        .chat-input-form {
            display: flex;
            gap: 0.7rem;
            align-items: center;
        }
        
        .chat-input {
            flex: 1;
            padding: 0.9rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 25px;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .chat-input:focus {
            border-color: #0f9b8e;
        }
        
        .send-btn {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #0f9b8e 0%, #0c7a6e 100%);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
            font-size: 1.1rem;
        }
        
        .send-btn:hover {
            transform: scale(1.05);
        }
        
        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .welcome-message {
            text-align: center;
            padding: 2rem;
            color: #64748b;
        }
        
        .welcome-message h3 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            color: #0f9b8e;
        }
        
        .welcome-message p {
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.8rem;
            margin-top: 1.5rem;
        }
        
        .feature-card {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 155, 142, 0.2);
            border-color: #0f9b8e;
        }
        
        .feature-icon {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .feature-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #1e293b;
        }
    </style>
    @endpush

    {{-- Main Page Content --}}
    <div class="chat-container">
        <div class="chat-header">
            <div class="chat-status">
                <span class="status-dot" id="statusDot"></span>
                <span id="statusText">Connecting...</span>
            </div>
            <h1>🤖 AI Islamic Assistant</h1>
            <p>Ask me about Prayer Times, Quran, Hadith, Duas, Donations & More</p>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="welcome-message">
                <h3>السلام عليكم ورحمة الله وبركاته</h3>
                <p>Welcome! I'm your AI Islamic assistant. How can I help you today?</p>
                
                <div class="feature-grid">
                    <div class="feature-card" onclick="sendQuickReply('What are the prayer times today?')">
                        <div class="feature-icon">🕌</div>
                        <div class="feature-title">Prayer Times</div>
                    </div>
                    <div class="feature-card" onclick="sendQuickReply('Show me Qibla direction')">
                        <div class="feature-icon">🧭</div>
                        <div class="feature-title">Qibla Direction</div>
                    </div>
                    <div class="feature-card" onclick="sendQuickReply('Show me a Quran verse')">
                        <div class="feature-icon">📖</div>
                        <div class="feature-title">Quran</div>
                    </div>
                    <div class="feature-card" onclick="sendQuickReply('Tell me a Hadith')">
                        <div class="feature-icon">📚</div>
                        <div class="feature-title">Hadith</div>
                    </div>
                    <div class="feature-card" onclick="sendQuickReply('Show me daily duas')">
                        <div class="feature-icon">🤲</div>
                        <div class="feature-title">Duas</div>
                    </div>
                    <div class="feature-card" onclick="sendQuickReply('I want to donate')">
                        <div class="feature-icon">❤️</div>
                        <div class="feature-title">Donate</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="chat-input-container">
            <div class="quick-replies" id="quickReplies">
                <!-- Quick reply buttons will be added dynamically -->
            </div>
            <form class="chat-input-form" id="chatForm">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="chatInput" 
                    placeholder="Type your message..." 
                    autocomplete="off"
                >
                <button type="submit" class="send-btn" id="sendBtn">
                    ➤
                </button>
            </form>
        </div>
    </div>

    {{-- Page-specific scripts pushed to the layout --}}
    @push('scripts')
    <script>
        const RASA_SERVER_URL = 'http://localhost:5005';
        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const statusDot = document.getElementById('statusDot');
        const statusText = document.getElementById('statusText');
        const quickReplies = document.getElementById('quickReplies');
        
        let isConnected = false;
        let conversationId = 'user_' + Date.now();
        
        // Check Rasa server connection
        async function checkConnection() {
            try {
                const response = await fetch(`${RASA_SERVER_URL}/status`);
                if (response.ok) {
                    isConnected = true;
                    statusDot.classList.remove('offline');
                    statusText.textContent = 'Online';
                }
            } catch (error) {
                isConnected = false;
                statusDot.classList.add('offline');
                statusText.textContent = 'Offline';
                console.error('Rasa server not reachable:', error);
            }
        }
        
        // Add message to chat
        function addMessage(text, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.textContent = isUser ? '👤' : '🤖';
            
            const content = document.createElement('div');
            content.className = 'message-content';
            
            // Convert markdown-like formatting to HTML
            let formattedText = text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/_(.*?)_/g, '<em>$1</em>')
                .replace(/\n/g, '<br>');
            
            content.innerHTML = formattedText;
            
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(content);
            
            // Remove welcome message if exists
            const welcomeMsg = chatMessages.querySelector('.welcome-message');
            if (welcomeMsg) {
                welcomeMsg.remove();
            }
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Show typing indicator
        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message bot';
            typingDiv.id = 'typingIndicator';
            
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.textContent = '🤖';
            
            const indicator = document.createElement('div');
            indicator.className = 'typing-indicator active';
            indicator.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
            
            typingDiv.appendChild(avatar);
            typingDiv.appendChild(indicator);
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Hide typing indicator
        function hideTyping() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
        
        // Send message to Rasa
        async function sendMessage(message) {
            if (!message.trim()) return;
            
            // Add user message
            addMessage(message, true);
            chatInput.value = '';
            
            if (!isConnected) {
                setTimeout(() => {
                    addMessage('Sorry, I\'m currently offline. Please check if the Rasa server is running.', false);
                }, 500);
                return;
            }
            
            showTyping();
            
            try {
                const response = await fetch(`${RASA_SERVER_URL}/webhooks/rest/webhook`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        sender: conversationId,
                        message: message
                    })
                });
                
                hideTyping();
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.length === 0) {
                        addMessage('I\'m not sure how to help with that. You can ask me about prayer times, Quran verses, Hadith, duas, donations, and more.', false);
                    } else {
                        data.forEach((msg, index) => {
                            setTimeout(() => {
                                if (msg.text) {
                                    addMessage(msg.text, false);
                                }
                                
                                // Handle quick replies/buttons
                                if (msg.buttons && msg.buttons.length > 0) {
                                    showQuickReplies(msg.buttons);
                                }
                            }, index * 500);
                        });
                    }
                } else {
                    addMessage('Sorry, there was an error processing your request.', false);
                }
            } catch (error) {
                hideTyping();
                console.error('Error sending message:', error);
                addMessage('Sorry, I couldn\'t reach the server. Please try again later.', false);
            }
        }
        
        // Show quick reply buttons
        function showQuickReplies(buttons) {
            quickReplies.innerHTML = '';
            buttons.forEach(button => {
                const btn = document.createElement('button');
                btn.className = 'quick-reply-btn';
                btn.textContent = button.title;
                btn.onclick = () => {
                    sendMessage(button.payload || button.title);
                    quickReplies.innerHTML = '';
                };
                quickReplies.appendChild(btn);
            });
        }
        
        // Send quick reply
        function sendQuickReply(message) {
            sendMessage(message);
        }
        
        // Form submission
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (message) {
                sendMessage(message);
            }
        });
        
        // Check connection on load
        checkConnection();
        
        // Periodically check connection
        setInterval(checkConnection, 30000);
    </script>
    @endpush
</x-app-layout>
