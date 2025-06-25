class WebSocketManager {
    constructor() {
        this.retryCount = 0;
        this.maxRetries = 5;
        this.socket = '';
        this.isConnected = false;
        this.websocketUrl = websocketUrl;
        this.disconnectBeforeUnload();
    }

    processMessage(message) {
        try {
            const data = JSON.parse(message);
            
            if(typeof data.type == 'undefined') return;
            if((data.type == 'chat' && data.sender == parseInt(selectedUserId)) && (data.msgtype == 'individual')) {
                addMessageToUI(data.message, data.direction, '', data.uuid, data.media, data.files);
            }

            if((data.roomId == parseInt(selectedChatId)) && (data.msgtype == 'group')) {
                addMessageToUI(data.message, data.direction, '', data.uuid, data.media, data.files);
            }

            if(data.type == 'chat') {
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