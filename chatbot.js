(function () {
    const root = document.querySelector('[data-chatbot]');
    if (!root) {
        return;
    }

    const endpoint = root.getAttribute('data-endpoint') || '';
    const toggle = root.querySelector('[data-chatbot-toggle]');
    const closeBtn = root.querySelector('[data-chatbot-close]');
    const form = root.querySelector('[data-chatbot-form]');
    const input = root.querySelector('[data-chatbot-input]');
    const messagesWrap = root.querySelector('[data-chatbot-messages]');
    const panel = root.querySelector('.chatbot-panel');

    if (!toggle || !closeBtn || !form || !input || !messagesWrap || !panel) {
        return;
    }

    const history = [];

    const setOpen = function (open) {
        root.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        panel.setAttribute('aria-hidden', open ? 'false' : 'true');
        if (open) {
            setTimeout(function () {
                input.focus();
            }, 120);
        }
    };

    const addMessage = function (role, text, track) {
        const message = document.createElement('div');
        message.className = 'chatbot-message ' + role;
        message.textContent = text;
        messagesWrap.appendChild(message);
        messagesWrap.scrollTop = messagesWrap.scrollHeight;

        if (track) {
            history.push({ role: role, content: text });
            if (history.length > 12) {
                history.shift();
            }
        }
    };

    const addTyping = function () {
        const message = document.createElement('div');
        message.className = 'chatbot-message assistant';
        const typing = document.createElement('div');
        typing.className = 'chatbot-typing';
        for (let i = 0; i < 3; i += 1) {
            const dot = document.createElement('span');
            typing.appendChild(dot);
        }
        message.appendChild(typing);
        messagesWrap.appendChild(message);
        messagesWrap.scrollTop = messagesWrap.scrollHeight;
        return message;
    };

    const setSending = function (sending) {
        input.disabled = sending;
        const button = form.querySelector('.chatbot-send');
        if (button) {
            button.disabled = sending;
        }
    };

    const sendToApi = async function () {
        const typing = addTyping();
        setSending(true);

        try {
            if (!endpoint) {
                throw new Error('Endpoint manquant.');
            }

            const payload = {
                messages: history.slice(-8)
            };

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });

            let data = null;
            try {
                data = await response.json();
            } catch (error) {
                data = null;
            }

            if (!response.ok) {
                const message = data && data.error ? data.error : 'Service indisponible.';
                throw new Error(message);
            }

            const reply = data && data.reply ? data.reply : 'Je ne peux pas repondre pour le moment.';
            addMessage('assistant', reply, true);
        } catch (error) {
            const message = error && error.message ? error.message : 'Service indisponible.';
            addMessage('assistant', message, true);
        } finally {
            typing.remove();
            setSending(false);
        }
    };

    toggle.addEventListener('click', function () {
        setOpen(!root.classList.contains('is-open'));
    });

    closeBtn.addEventListener('click', function () {
        setOpen(false);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && root.classList.contains('is-open')) {
            setOpen(false);
        }
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const text = input.value.trim();
        if (!text) {
            return;
        }

        addMessage('user', text, true);
        input.value = '';
        sendToApi();
    });

    addMessage('assistant', 'Bonjour! Je suis votre assistant Seabel. Posez une question sur nos hotels, tarifs ou reservations.', false);
})();
