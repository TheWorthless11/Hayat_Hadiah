// Floating Chatbot Widget
(function() {
    const RASA_SERVER_URL = 'http://localhost:5005';
    let isWidgetOpen = false;
    let isConnected = false;
    let conversationId = 'widget_' + Date.now();
    
    // Initialize widget
    function initChatWidget() {
        // Create widget HTML
        const widgetHTML = `
            <div id="floatingChatWidget">
                <button class="chat-bubble-btn" id="chatBubbleBtn" onclick="toggleChatWidget()">
                    🤖
                    <span class="unread-badge" id="unreadBadge">1</span>
                </button>
                
                <div class="floating-chat-window" id="floatingChatWindow">
                    <div class="floating-chat-header">
                        <div>
                            <h4>🤖 AI Assistant</h4>
                            <p>Ask me anything!</p>
                        </div>
                        <button class="chat-minimize-btn" onclick="toggleChatWidget()">✕</button>
                    </div>
                    
                    <div class="floating-chat-messages" id="floatingChatMessages">
                        <div class="floating-welcome">
                            <h5>السلام عليكم</h5>
                            <p>How can I help you today?</p>
                            <div class="floating-quick-actions">
                                <div class="floating-action-btn" onclick="sendFloatingQuickReply('Prayer times')">
                                    <div class="floating-action-icon">🕌</div>
                                    <div>Prayer Times</div>
                                </div>
                                <div class="floating-action-btn" onclick="sendFloatingQuickReply('Quran verse')">
                                    <div class="floating-action-icon">📖</div>
                                    <div>Quran</div>
                                </div>
                                <div class="floating-action-btn" onclick="sendFloatingQuickReply('Show hadith')">
                                    <div class="floating-action-icon">📚</div>
                                    <div>Hadith</div>
                                </div>
                                <div class="floating-action-btn" onclick="sendFloatingQuickReply('I want to donate')">
                                    <div class="floating-action-icon">❤️</div>
                                    <div>Donate</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="floating-chat-input-container">
                        <form class="floating-chat-form" id="floatingChatForm" onsubmit="handleFloatingSubmit(event)">
                            <input 
                                type="text" 
                                class="floating-chat-input" 
                                id="floatingChatInput" 
                                placeholder="Type a message..."
                                autocomplete="off"
                            >
                            <button type="submit" class="floating-send-btn" id="floatingSendBtn">➤</button>
                        </form>
                    </div>
                </div>
            </div>
        `;
        
        // Add widget to body
        document.body.insertAdjacentHTML('beforeend', widgetHTML);
        
        // Check connection
        checkWidgetConnection();
    }
    
    // Toggle widget
    window.toggleChatWidget = function() {
        const chatWindow = document.getElementById('floatingChatWindow');
        const chatBubble = document.getElementById('chatBubbleBtn');
        const unreadBadge = document.getElementById('unreadBadge');
        
        isWidgetOpen = !isWidgetOpen;
        
        if (isWidgetOpen) {
            chatWindow.classList.add('open');
            chatBubble.classList.add('active');
            unreadBadge.classList.remove('show');
            
            // Scroll to bottom
            const messagesContainer = document.getElementById('floatingChatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } else {
            chatWindow.classList.remove('open');
            chatBubble.classList.remove('active');
        }
    };
    
    // Check Rasa connection
    async function checkWidgetConnection() {
        try {
            const response = await fetch(`${RASA_SERVER_URL}/status`);
            isConnected = response.ok;
        } catch (error) {
            isConnected = false;
            console.warn('Chatbot offline:', error);
        }
    }
    
    // Add message to floating chat
    function addFloatingMessage(text, isUser = false) {
        const messagesContainer = document.getElementById('floatingChatMessages');
        
        // Remove welcome message if exists
        const welcomeMsg = messagesContainer.querySelector('.floating-welcome');
        if (welcomeMsg) {
            welcomeMsg.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
        
        const content = document.createElement('div');
        content.className = 'message-content';
        
        // Format text
        let formattedText = text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/_(.*?)_/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
        
        content.innerHTML = formattedText;
        messageDiv.appendChild(content);
        messagesContainer.appendChild(messageDiv);
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Show unread badge if widget is closed
        if (!isWidgetOpen && !isUser) {
            const unreadBadge = document.getElementById('unreadBadge');
            unreadBadge.classList.add('show');
        }
    }
    
    // Show typing indicator
    function showFloatingTyping() {
        const messagesContainer = document.getElementById('floatingChatMessages');
        
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot';
        typingDiv.id = 'floatingTypingIndicator';
        
        const indicator = document.createElement('div');
        indicator.className = 'floating-typing-indicator active';
        indicator.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
        
        typingDiv.appendChild(indicator);
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    // Hide typing indicator
    function hideFloatingTyping() {
        const typingIndicator = document.getElementById('floatingTypingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    // Send message
    async function sendFloatingMessage(message) {
        if (!message.trim()) return;
        
        addFloatingMessage(message, true);
        document.getElementById('floatingChatInput').value = '';
        
        if (!isConnected) {
            setTimeout(() => {
                addFloatingMessage('I\'m currently offline. Please try the full chatbot page.', false);
            }, 500);
            return;
        }
        
        showFloatingTyping();
        
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
            
            hideFloatingTyping();
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.length === 0) {
                    addFloatingMessage('I\'m not sure how to help with that. Try asking about prayer times, Quran, or donations.', false);
                } else {
                    data.forEach((msg, index) => {
                        setTimeout(() => {
                            if (msg.text) {
                                addFloatingMessage(msg.text, false);
                            }
                        }, index * 500);
                    });
                }
            } else {
                addFloatingMessage('Sorry, there was an error.', false);
            }
        } catch (error) {
            hideFloatingTyping();
            console.error('Error:', error);
            addFloatingMessage('Sorry, I couldn\'t reach the server.', false);
        }
    }
    
    // Handle form submission
    window.handleFloatingSubmit = function(event) {
        event.preventDefault();
        const input = document.getElementById('floatingChatInput');
        const message = input.value.trim();
        if (message) {
            sendFloatingMessage(message);
        }
    };
    
    // Send quick reply
    window.sendFloatingQuickReply = function(message) {
        sendFloatingMessage(message);
    };
    
    // Initialize widget when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatWidget);
    } else {
        initChatWidget();
    }
    
    // Periodically check connection
    setInterval(checkWidgetConnection, 30000);
})();
