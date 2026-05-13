<div id="chatbot-widget" class="chatbot-widget d-none" aria-live="polite">
    <div class="chatbot-header">
        <div class="chatbot-title">
            <span class="chatbot-avatar"><i class="fas fa-comments"></i></span>
            <div>
                <h3>Bhasha Bot</h3>
                <p>BhashaPathshala course guide</p>
            </div>
        </div>
        <div class="chatbot-header-actions">
            <button type="button" class="chatbot-icon-btn" id="chatbot-clear" aria-label="Clear chat" title="Clear chat">
                <i class="fas fa-rotate-right"></i>
            </button>
            <button type="button" class="chatbot-icon-btn" id="chatbot-minimize" aria-label="Minimize chat" title="Minimize">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="chatbot-messages" id="chatbot-messages">
        <div class="message bot-message">
            <div class="message-content">
                <p>Hi. I can find courses, recommend a language path, explain enrollment, and help you check progress.</p>
            </div>
        </div>
    </div>

    <div class="chatbot-suggestions" id="chatbot-suggestions">
        <button class="suggestion-btn" data-message="Show courses"><i class="fas fa-compass"></i> Courses</button>
        <button class="suggestion-btn" data-message="Recommend courses"><i class="fas fa-lightbulb"></i> Recommend</button>
        <button class="suggestion-btn" data-message="How do I enroll?"><i class="fas fa-user-plus"></i> Enroll</button>
        <button class="suggestion-btn" data-message="Help"><i class="fas fa-circle-question"></i> Help</button>
    </div>

    <div class="chatbot-input-area">
        <form id="chatbot-form" class="chatbot-form">
            <input
                type="text"
                id="chatbot-input"
                placeholder="Ask for Hindi, beginner courses, progress..."
                class="chatbot-input"
                autocomplete="off"
            >
            <button type="submit" class="btn-send" aria-label="Send message">
                <i class="fas fa-arrow-up"></i>
            </button>
        </form>
    </div>
</div>

<button id="chatbot-toggle" class="chatbot-toggle" aria-label="Open Bhasha Bot">
    <i class="fas fa-comments"></i>
</button>

<style>
    :root {
        --chatbot-primary: #2454d6;
        --chatbot-primary-dark: #173a96;
        --chatbot-accent: #0f9f8f;
        --chatbot-line: #d9e2ef;
        --chatbot-soft: #f3f7fb;
        --chatbot-ink: #182230;
        --chatbot-muted: #667085;
    }

    .chatbot-widget {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9999;
        display: flex;
        width: min(420px, calc(100vw - 32px));
        height: min(680px, calc(100vh - 40px));
        overflow: hidden;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--chatbot-line);
        border-radius: 8px;
        box-shadow: 0 24px 70px rgba(24, 34, 48, .22);
        color: var(--chatbot-ink);
    }

    .chatbot-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(36, 84, 214, .96), rgba(15, 159, 143, .94)),
            repeating-linear-gradient(45deg, rgba(255,255,255,.14) 0 1px, transparent 1px 12px);
    }

    .chatbot-title {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-width: 0;
    }

    .chatbot-avatar {
        display: grid;
        width: 2.35rem;
        height: 2.35rem;
        flex: 0 0 auto;
        place-items: center;
        color: var(--chatbot-primary);
        background: #fff;
        border-radius: 8px;
    }

    .chatbot-title h3,
    .chatbot-title p {
        margin: 0;
    }

    .chatbot-title h3 {
        font-size: 1rem;
        font-weight: 800;
    }

    .chatbot-title p {
        color: rgba(255, 255, 255, .82);
        font-size: .78rem;
    }

    .chatbot-header-actions {
        display: flex;
        gap: .4rem;
    }

    .chatbot-icon-btn {
        display: grid;
        width: 2rem;
        height: 2rem;
        place-items: center;
        color: #fff;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 8px;
        cursor: pointer;
    }

    .chatbot-icon-btn:hover {
        background: rgba(255, 255, 255, .24);
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background: linear-gradient(180deg, #f8fbff 0%, #fff 58%);
    }

    .message {
        display: flex;
        margin-bottom: .8rem;
        animation: chatbotSlide .18s ease-out;
    }

    @keyframes chatbotSlide {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bot-message {
        justify-content: flex-start;
    }

    .user-message {
        justify-content: flex-end;
    }

    .message-content {
        max-width: 84%;
        padding: .75rem .85rem;
        border-radius: 8px;
        font-size: .9rem;
        line-height: 1.45;
    }

    .bot-message .message-content {
        background: #fff;
        border: 1px solid var(--chatbot-line);
        box-shadow: 0 8px 22px rgba(24, 34, 48, .06);
    }

    .user-message .message-content {
        color: #fff;
        background: var(--chatbot-primary);
    }

    .message-content p {
        margin: 0;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .chatbot-courses,
    .chatbot-actions,
    .chatbot-topics {
        display: grid;
        gap: .65rem;
        margin-top: .75rem;
    }

    .chatbot-course-card {
        display: grid;
        grid-template-columns: 74px minmax(0, 1fr);
        gap: .75rem;
        width: 100%;
        padding: .55rem;
        text-align: left;
        background: #fff;
        border: 1px solid var(--chatbot-line);
        border-radius: 8px;
        cursor: pointer;
    }

    .chatbot-course-card:hover {
        border-color: var(--chatbot-primary);
        box-shadow: 0 10px 24px rgba(36, 84, 214, .12);
    }

    .chatbot-course-image {
        width: 74px;
        height: 74px;
        object-fit: cover;
        background: linear-gradient(135deg, var(--chatbot-primary), var(--chatbot-accent));
        border-radius: 8px;
    }

    .chatbot-course-card strong {
        display: block;
        margin-bottom: .2rem;
        color: var(--chatbot-ink);
        font-size: .86rem;
        line-height: 1.25;
    }

    .chatbot-course-card p {
        margin: 0;
        color: var(--chatbot-muted);
        font-size: .74rem;
        line-height: 1.35;
    }

    .chatbot-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
        margin-top: .45rem;
    }

    .chatbot-badge {
        padding: .2rem .45rem;
        color: #175cd3;
        background: #eaf2ff;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 800;
    }

    .chatbot-action-btn {
        width: 100%;
        padding: .55rem .7rem;
        color: var(--chatbot-primary);
        background: #fff;
        border: 1px solid #b8c8df;
        border-radius: 8px;
        font-weight: 800;
        text-align: center;
    }

    .chatbot-action-btn:hover {
        color: #fff;
        background: var(--chatbot-primary);
        border-color: var(--chatbot-primary);
    }

    .chatbot-topic {
        padding: .65rem;
        background: var(--chatbot-soft);
        border: 1px solid var(--chatbot-line);
        border-radius: 8px;
        font-size: .8rem;
    }

    .chatbot-progress-row {
        display: grid;
        gap: .35rem;
        padding: .65rem;
        background: #fff;
        border: 1px solid var(--chatbot-line);
        border-radius: 8px;
    }

    .chatbot-progress-track {
        height: .45rem;
        overflow: hidden;
        background: #edf2f7;
        border-radius: 999px;
    }

    .chatbot-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--chatbot-primary), var(--chatbot-accent));
    }

    .chatbot-suggestions {
        display: flex;
        gap: .45rem;
        padding: .75rem;
        overflow-x: auto;
        background: #fff;
        border-top: 1px solid var(--chatbot-line);
    }

    .suggestion-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        flex: 0 0 auto;
        padding: .45rem .65rem;
        color: var(--chatbot-ink);
        background: var(--chatbot-soft);
        border: 1px solid var(--chatbot-line);
        border-radius: 999px;
        font-size: .78rem;
        font-weight: 800;
        cursor: pointer;
    }

    .suggestion-btn:hover {
        color: #fff;
        background: var(--chatbot-primary);
        border-color: var(--chatbot-primary);
    }

    .chatbot-input-area {
        padding: .75rem;
        background: #fff;
        border-top: 1px solid var(--chatbot-line);
    }

    .chatbot-form {
        display: flex;
        gap: .5rem;
    }

    .chatbot-input {
        flex: 1;
        min-width: 0;
        padding: .65rem .8rem;
        border: 1px solid var(--chatbot-line);
        border-radius: 8px;
        outline: none;
    }

    .chatbot-input:focus {
        border-color: var(--chatbot-primary);
        box-shadow: 0 0 0 .2rem rgba(36, 84, 214, .12);
    }

    .btn-send,
    .chatbot-toggle {
        display: grid;
        place-items: center;
        color: #fff;
        background: var(--chatbot-primary);
        border: 0;
        cursor: pointer;
        box-shadow: 0 14px 28px rgba(36, 84, 214, .26);
    }

    .btn-send {
        width: 2.55rem;
        height: 2.55rem;
        border-radius: 8px;
    }

    .chatbot-toggle {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9998;
        width: 58px;
        height: 58px;
        border-radius: 50%;
    }

    .chatbot-toggle:hover,
    .btn-send:hover {
        background: var(--chatbot-primary-dark);
    }

    .typing-indicator {
        display: flex;
        gap: .3rem;
        padding: .65rem .75rem;
    }

    .typing-dot {
        width: .45rem;
        height: .45rem;
        background: var(--chatbot-muted);
        border-radius: 50%;
        animation: chatbotTyping 1.2s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: .16s; }
    .typing-dot:nth-child(3) { animation-delay: .32s; }

    @keyframes chatbotTyping {
        0%, 70%, 100% { opacity: .45; transform: translateY(0); }
        35% { opacity: 1; transform: translateY(-5px); }
    }

    @media (max-width: 640px) {
        .chatbot-widget {
            inset: 0;
            width: 100vw;
            height: 100vh;
            max-height: none;
            border-radius: 0;
        }

        .chatbot-toggle {
            right: 16px;
            bottom: 16px;
        }
    }
</style>
