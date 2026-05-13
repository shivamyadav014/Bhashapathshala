class ChatbotWidget {
    constructor() {
        this.isWaitingForResponse = false;
        this.messagesDiv = document.getElementById('chatbot-messages');
        this.suggestionsDiv = document.getElementById('chatbot-suggestions');
        this.setupEventListeners();
        this.loadSuggestions();
    }

    setupEventListeners() {
        const form = document.getElementById('chatbot-form');
        const input = document.getElementById('chatbot-input');
        const minimizeBtn = document.getElementById('chatbot-minimize');
        const toggleBtn = document.getElementById('chatbot-toggle');
        const clearBtn = document.getElementById('chatbot-clear');

        form?.addEventListener('submit', (event) => this.handleSubmit(event));

        input?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                form?.requestSubmit();
            }
        });

        minimizeBtn?.addEventListener('click', () => this.closeWidget());
        toggleBtn?.addEventListener('click', () => this.openWidget());
        clearBtn?.addEventListener('click', () => this.clearChat());

        this.bindSuggestionButtons();
    }

    bindSuggestionButtons() {
        document.querySelectorAll('.suggestion-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const message = button.dataset.message;
                if (message) {
                    this.sendMessage(message);
                }
            });
        });
    }

    handleSubmit(event) {
        event.preventDefault();

        const input = document.getElementById('chatbot-input');
        const message = input?.value.trim();

        if (!message || this.isWaitingForResponse) {
            return;
        }

        input.value = '';
        this.sendMessage(message);
    }

    async sendMessage(message) {
        if (this.isWaitingForResponse) {
            return;
        }

        this.addMessage(message, 'user');
        this.isWaitingForResponse = true;
        this.showTypingIndicator();

        try {
            const response = await fetch('/api/chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            this.removeTypingIndicator();

            if (!response.ok || !data.success) {
                this.addMessage('I could not answer that right now. Please try again.', 'bot');
                return;
            }

            this.displayBotResponse(data.response);

            if (data.response?.suggestions) {
                this.updateSuggestions(data.response.suggestions);
            }
        } catch (error) {
            console.error('Chatbot error:', error);
            this.removeTypingIndicator();
            this.addMessage('I am having trouble connecting. Please try again in a moment.', 'bot');
        } finally {
            this.isWaitingForResponse = false;
        }
    }

    displayBotResponse(response) {
        const wrapper = document.createElement('div');
        wrapper.className = 'message bot-message';

        const content = document.createElement('div');
        content.className = 'message-content';

        if (response.text) {
            const text = document.createElement('p');
            text.textContent = response.text;
            content.appendChild(text);
        }

        if (response.courses?.length) {
            content.appendChild(this.renderCourses(response.courses));
        }

        if (response.enrollments?.length) {
            content.appendChild(this.renderProgress(response.enrollments));
        }

        if (response.badges?.length) {
            content.appendChild(this.renderBadges(response.badges));
        }

        if (response.topics?.length) {
            content.appendChild(this.renderTopics(response.topics));
        }

        if (response.actions?.length) {
            content.appendChild(this.renderActions(response.actions));
        }

        wrapper.appendChild(content);
        this.messagesDiv.appendChild(wrapper);
        this.scrollToBottom();
    }

    renderCourses(courses) {
        const container = document.createElement('div');
        container.className = 'chatbot-courses';

        courses.forEach((course) => {
            const card = document.createElement('button');
            card.type = 'button';
            card.className = 'chatbot-course-card';
            card.addEventListener('click', () => {
                window.location.href = course.url || `/courses/${course.id}`;
            });

            const image = document.createElement('img');
            image.className = 'chatbot-course-image';
            image.alt = course.title || 'Course photo';
            image.src = course.thumbnail || '';
            image.onerror = () => {
                image.removeAttribute('src');
                image.alt = '';
            };

            const body = document.createElement('div');
            const title = document.createElement('strong');
            title.textContent = course.title || 'Course';

            const description = document.createElement('p');
            description.textContent = course.description || 'Open this course to see lessons and quizzes.';

            const meta = document.createElement('div');
            meta.className = 'chatbot-meta';
            [course.language, course.level].filter(Boolean).forEach((item) => {
                const badge = document.createElement('span');
                badge.className = 'chatbot-badge';
                badge.textContent = item;
                meta.appendChild(badge);
            });

            body.appendChild(title);
            body.appendChild(description);
            body.appendChild(meta);
            card.appendChild(image);
            card.appendChild(body);
            container.appendChild(card);
        });

        return container;
    }

    renderActions(actions) {
        const container = document.createElement('div');
        container.className = 'chatbot-actions';

        actions.forEach((action) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'chatbot-action-btn';
            button.textContent = action.label;
            button.addEventListener('click', () => {
                if (action.url) {
                    window.location.href = action.url;
                } else if (action.message) {
                    this.sendMessage(action.message);
                }
            });
            container.appendChild(button);
        });

        return container;
    }

    renderProgress(enrollments) {
        const container = document.createElement('div');
        container.className = 'chatbot-actions';

        enrollments.forEach((enrollment) => {
            const row = document.createElement('button');
            row.type = 'button';
            row.className = 'chatbot-progress-row';
            row.addEventListener('click', () => {
                if (enrollment.url) {
                    window.location.href = enrollment.url;
                }
            });

            const title = document.createElement('strong');
            title.textContent = enrollment.course;

            const status = document.createElement('span');
            status.textContent = `${Math.round(enrollment.progress)}% complete - ${enrollment.status || 'Enrolled'}`;

            const track = document.createElement('div');
            track.className = 'chatbot-progress-track';
            const fill = document.createElement('div');
            fill.className = 'chatbot-progress-fill';
            fill.style.width = `${Math.min(100, Math.max(0, enrollment.progress))}%`;
            track.appendChild(fill);

            row.appendChild(title);
            row.appendChild(track);
            row.appendChild(status);
            container.appendChild(row);
        });

        return container;
    }

    renderBadges(badges) {
        const container = document.createElement('div');
        container.className = 'chatbot-topics';

        badges.forEach((badge) => {
            const item = document.createElement('div');
            item.className = 'chatbot-topic';
            item.textContent = badge.name;
            container.appendChild(item);
        });

        return container;
    }

    renderTopics(topics) {
        const container = document.createElement('div');
        container.className = 'chatbot-topics';

        topics.forEach((topic) => {
            const item = document.createElement('div');
            item.className = 'chatbot-topic';
            const title = document.createElement('strong');
            title.textContent = topic.title;
            const list = document.createElement('p');
            list.textContent = topic.topics.join(', ');
            item.appendChild(title);
            item.appendChild(list);
            container.appendChild(item);
        });

        return container;
    }

    addMessage(text, sender = 'bot') {
        const message = document.createElement('div');
        message.className = `message ${sender}-message`;

        const content = document.createElement('div');
        content.className = 'message-content';

        const paragraph = document.createElement('p');
        paragraph.textContent = text;

        content.appendChild(paragraph);
        message.appendChild(content);
        this.messagesDiv.appendChild(message);
        this.scrollToBottom();
    }

    showTypingIndicator() {
        const typing = document.createElement('div');
        typing.id = 'typing-indicator';
        typing.className = 'message bot-message';
        typing.innerHTML = '<div class="typing-indicator"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>';
        this.messagesDiv.appendChild(typing);
        this.scrollToBottom();
    }

    removeTypingIndicator() {
        document.getElementById('typing-indicator')?.remove();
    }

    updateSuggestions(suggestions) {
        if (!this.suggestionsDiv) {
            return;
        }

        this.suggestionsDiv.innerHTML = '';

        suggestions.forEach((suggestion) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'suggestion-btn';
            button.dataset.message = suggestion;
            button.textContent = suggestion;
            this.suggestionsDiv.appendChild(button);
        });

        this.bindSuggestionButtons();
    }

    async loadSuggestions() {
        try {
            const response = await fetch('/api/chatbot/suggestions', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                },
            });
            const data = await response.json();

            if (data.success && data.suggestions) {
                this.updateSuggestions(data.suggestions);
            }
        } catch (error) {
            console.warn('Could not load chatbot suggestions:', error);
        }
    }

    clearChat() {
        this.messagesDiv.innerHTML = '';
        this.addMessage('Chat cleared. Ask Bhasha Bot for courses, recommendations, enrollment help, or progress.', 'bot');
        this.updateSuggestions(['Show courses', 'Recommend courses', 'How do I enroll?', 'Help']);
    }

    openWidget() {
        document.getElementById('chatbot-widget')?.classList.remove('d-none');
        document.getElementById('chatbot-toggle')?.classList.add('d-none');
        document.getElementById('chatbot-input')?.focus();
    }

    closeWidget() {
        document.getElementById('chatbot-widget')?.classList.add('d-none');
        document.getElementById('chatbot-toggle')?.classList.remove('d-none');
    }

    scrollToBottom() {
        window.setTimeout(() => {
            this.messagesDiv.scrollTop = this.messagesDiv.scrollHeight;
        }, 0);
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('chatbot-widget')) {
        window.bhashaBot = new ChatbotWidget();
    }
});
