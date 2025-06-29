class WebSocketManager {
    constructor() {
        this.retryCount = 0;
        this.maxRetries = 5;
        this.socket = '';
        this.isConnected = false;
        this.websocketUrl = websocketUrl;
        this.disconnectBeforeUnload();
    }

    /**
     * Converts /chat/join/{string} to clickable links in a text.
     *
     * @param {string} text - The input text.
     * @returns {string} The text with /chat/join links converted to anchor tags.
     */
    linkifyChatJoin(text) {
        return text.replace(/\/chat\/join\/[^\s]+/g, (url) => {
            return `<a class="text-black hover:cursor-pointer dark:hover:text-white dark:text-white hover:text-black hover:underline" href="${url}">${url}</a>`;
        });
    }

    processMessage(message) {
        try {

            const data = JSON.parse(message);
            
            if(typeof data.type == 'undefined') return;

            if(data.type == 'chat') {
                data.message = this.linkifyChatJoin(data.message);
                if((data.sender == parseInt(selectedUserId)) && (data.msgtype == 'individual')) {
                    addMessageToUI(data.message, data.direction, '', data.uuid, data.media, data.files, data.sender);
                }
                if((data.roomId == parseInt(selectedChatId)) && (data.msgtype == 'group')) {
                    addMessageToUI(data.message, data.direction, '', data.uuid, data.media, data.files, data.sender);
                }
                if(data.roomId !== parseInt(selectedChatId)) {
                    $(`div[data-room-count-id="${data.roomId}"]`).removeClass('hidden');
                    let roomCount = parseInt($(`div[data-room-count-id="${data.roomId}"] span[class="room-count-${data.roomId}"]`).text());
                    roomCount = isNaN(roomCount) ? 0 : roomCount;
                    $(`div[data-room-count-id="${data.roomId}"] span[class="room-count-${data.roomId}"]`).text(roomCount + 1);
                }
                setTimeout(() => {
                    new MediaDisplay();
                    scrollToBottom();
                }, 500);
            }
        } catch(e) {
        }
    }

    connect(force = false) {
        if(this.socket && !force) return;
        AppState.loadUser();
        this.websocketUrl = `${this.websocketUrl}?token=${AppState.getToken()}&userId=${AppState.getUserId()}`;

        this.socket = new WebSocket(this.websocketUrl);
        this.socket.onopen = () => {
            console.log("✅ Connected to WebSocket server");
        };

        this.socket.onmessage = (event) => {
            this.processMessage(event.data);
        };

        this.socket.onclose = () => {
            this.isConnected = false;
            setTimeout(() => {
                this.retryConnection();
            }, 5000);
        };
    }

    disconnectBeforeUnload() {
        try {
            document.addEventListener('beforeunload', () => {
                this.socket.close();
            });
        } catch(e) { }
    }

    retryConnection() {
        this.socket = new WebSocket(this.websocketUrl);
        this.socket.onopen = () => {
            this.isConnected = true;
            this.retryCount = 0;
            console.log("✅ Connected to WebSocket server");
        };
        
        this.socket.onerror = (event) => {
            this.isConnected = false;
            if(this.retryCount < this.maxRetries) {
                this.retryCount++;
                setTimeout(() => {
                    this.retryConnection();
                }, 5000);
            }
        };

    }

    send(message) {
        if(!this.socket) {
            this.connect(true);
        }

        if(this.socket.readyState === WebSocket.OPEN) {
            this.socket.send(JSON.stringify(message));
        }
    }
}

if($(`div[id="messagesArea"]`).length > 0) {
    AppState.socketConnect = new WebSocketManager();
    AppState.socketConnect.connect();
}