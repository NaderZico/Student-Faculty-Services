import React, { useState } from 'react';
import Chatbot from 'react-chatbot-kit';
import 'react-chatbot-kit/build/main.css';
import config from './Chatbot/config.js';
import MessageParser from './Chatbot/MessageParser.js';
import ActionProvider from './Chatbot/ActionProvider.js';
import './Chatbot/chatbot.css';

function App() {
    const [chatbotVisible, setChatbotVisible] = useState(false);

    const toggleChatbot = () => {
        setChatbotVisible(!chatbotVisible);
    };

    return (
        <div>
            <div className="avatar" onClick={toggleChatbot}></div>
            <div className={`chatbot-window ${chatbotVisible ? 'active' : ''}`}>
                <Chatbot
                    headerText='Ask Farah'
                    config={config}
                    messageParser={MessageParser}
                    actionProvider={ActionProvider}
                />
            </div>
        </div>
    );
}

export default App;
