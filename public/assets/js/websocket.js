class WebSocketManager {
    constructor() {
        this.socket = '';
        this.websocketUrl = websocketUrl;
        this.disconnectBeforeUnload();
    }

    processMessage(message) {
        try {
            const data = JSON.parse(message);
            
            if(typeof data.type == 'undefined') return;
            if(data.type == 'chat') {
                addMessageToUI(data.message, data.direction);
                scrollToBottom();
            }
        } catch(e) {
            console.log(e);
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
            console.log("✅ Connected to WebSocket server");
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