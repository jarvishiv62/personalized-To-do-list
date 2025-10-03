<!-- Floating Chat Widget with Gemini AI -->
<div id="chatWidget" class="chat-widget">
    <!-- Chat Toggle Button -->
    <button id="chatToggleBtn" class="chat-toggle-btn" aria-label="Toggle chat">
        <i class="bi bi-chat-dots-fill"></i>
        @if(config('services.gemini.enabled'))
            <span class="ai-badge">AI</span>
        @endif
    </button>
    
    <!-- Chat Window -->
    <div id="chatWindow" class="chat-window">
        <!-- Header -->
        <div class="chat-window-header">
            <div class="d-flex align-items-center">
                <div class="avatar-small bg-primary text-white rounded-circle me-2">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <h6 class="mb-0">
                        DailyDrive Assistant
                        @if(config('services.gemini.enabled'))
                            <span class="badge bg-light text-primary ms-1" style="font-size: 0.65rem;">✨ AI</span>
                        @endif
                    </h6>
                    <small class="text-white-50">Always here to help</small>
                </div>
            </div>
            <button id="chatCloseBtn" class="btn-close btn-close-white" aria-label="Close"></button>
        </div>
        
        <!-- Messages -->
        <div id="widgetChatMessages" class="chat-window-messages">
            <div class="welcome-message text-center py-4">
                <i class="bi bi-robot" style="font-size: 2rem; color: #0d6efd;"></i>
                <p class="mt-2 mb-3">
                    Hi! I'm your DailyDrive assistant
                    @if(config('services.gemini.enabled'))
                        powered by Google Gemini AI ✨
                    @endif
                    <br>How can I help you today?
                </p>
                <div class="d-flex flex-wrap gap-1 justify-content-center">
                    <button class="btn btn-sm btn-outline-primary widget-quick-cmd" data-command="tasks today">
                        📝 Today's Tasks
                    </button>
                    <button class="btn btn-sm btn-outline-primary widget-quick-cmd" data-command="quote">
                        🌟 Quote
                    </button>
                    <button class="btn btn-sm btn-outline-primary widget-quick-cmd" data-command="goals">
                        🎯 Goals
                    </button>
                    <button class="btn btn-sm btn-outline-primary widget-quick-cmd" data-command="help">
                        ❓ Help
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Input -->
        <div class="chat-window-footer">
            <form id="widgetChatForm" class="d-flex gap-2">
                @csrf
                <input 
                    type="text" 
                    id="widgetMessageInput" 
                    class="form-control form-control-sm" 
                    placeholder="@if(config('services.gemini.enabled'))Ask me anything...@else Type a message...@endif"
                    autocomplete="off"
                >
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatToggleBtn = document.getElementById('chatToggleBtn');
        const chatWindow = document.getElementById('chatWindow');
        const widgetChatForm = document.getElementById('widgetChatForm');
        const widgetMessageInput = document.getElementById('widgetMessageInput');
        const widgetChatMessages = document.getElementById('widgetChatMessages');
        const widgetQuickCmds = document.querySelectorAll('.widget-quick-cmd');
        
        // Toggle chat window
        chatToggleBtn.addEventListener('click', function() {
            chatWindow.classList.toggle('visible');
            if (chatWindow.classList.contains('visible')) {
                widgetMessageInput.focus();
            }
        });
        
        // Close chat window
        chatCloseBtn.addEventListener('click', function() {
            chatWindow.classList.remove('visible');
        });
        
        // Add message to widget
        function addWidgetMessage(message, isUser = false, aiPowered = false) {
            const messageDiv = document.createElement('div');
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'widget-message-content';
            contentDiv.style.whiteSpace = 'pre-line';
            contentDiv.textContent = message;
            
            const timeDiv = document.createElement('div');
            timeDiv.className = 'widget-message-time';
            const timeText = new Date().toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit', 
                hour12: true 
            });
            
            if (!isUser && aiPowered) {
                timeDiv.innerHTML = timeText + ' <span class="ai-indicator">✨ AI</span>';
            } else {
                timeDiv.textContent = timeText;
            }
            
            messageDiv.appendChild(contentDiv);
            messageDiv.appendChild(timeDiv);
            
            // Remove welcome message if exists
            const welcomeMsg = widgetChatMessages.querySelector('.welcome-message');
            if (welcomeMsg && isUser) {
                welcomeMsg.remove();
            }
            
            widgetChatMessages.appendChild(messageDiv);
            widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
        }
        
        // Add typing indicator
        function addTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.id = 'widgetTypingIndicator';
            typingDiv.className = 'widget-message bot';
            typingDiv.innerHTML = `
                <div class="widget-message-content">
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;
            widgetChatMessages.appendChild(typingDiv);
            widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
        }
        
        // Remove typing indicator
        function removeTypingIndicator() {
            const indicator = document.getElementById('widgetTypingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }
        
        // Handle form submission
        widgetChatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = widgetMessageInput.value.trim();
            if (!message) return;
            
            // Add user message
            addWidgetMessage(message, true);
            widgetMessageInput.value = '';
            
            // Disable input
            widgetMessageInput.disabled = true;
            
            // Show typing indicator
            addTypingIndicator();
            
            try {
                const response = await fetch('{{ route("chat.message") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                });
                
                const data = await response.json();
                
                // Remove typing indicator
                removeTypingIndicator();
                
                if (data.success) {
                    // Add bot message with AI indicator if powered by AI
                    addWidgetMessage(data.response, false, data.ai_powered || false);
                    
                    // Add links if present
                    if (data.links && data.links.length > 0) {
                        const linksDiv = document.createElement('div');
                        linksDiv.className = 'widget-message bot';
                        linksDiv.innerHTML = '<div class="widget-message-content">' +
                            data.links.map(link => 
                                `<a href="${link.url}" class="btn btn-sm btn-outline-primary me-1 mb-1">${link.text}</a>`
                            ).join('') +
                            '</div>';
                        widgetChatMessages.appendChild(linksDiv);
                        widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
                    }
                } else {
                    addWidgetMessage('Sorry, something went wrong.', false);
                }
            } catch (error) {
                console.error('Error:', error);
                removeTypingIndicator();
                addWidgetMessage('Sorry, I encountered an error.', false);
            } finally {
                widgetMessageInput.disabled = false;
                widgetMessageInput.focus();
            }
        });
        
        // Handle quick commands
        widgetQuickCmds.forEach(button => {
            button.addEventListener('click', function() {
                const command = this.dataset.command;
                widgetMessageInput.value = command;
                widgetChatForm.dispatchEvent(new Event('submit'));
            });
        });
    });
</script>
@endpush