<?php

namespace app\widgets;

use app\models\Emoji;

class EmojiWidget{
    public static function renderEmoji(){
        $html = <<<HTMl
        <div id="chat-container">
        <div id="chat-box"></div>
        <textarea id="message-box" placeholder="Type a message..."></textarea>
        <button id="emoji-button">😊</button>
        <button id="send-button">Send</button>
        </div>
        <script src="/js/functions/emoji.js"></script>
    HTML;

    return $html;
    }
}