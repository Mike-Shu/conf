<?php

namespace App\Http\Livewire;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $chat;
    public $userId;
    public $messageText;

    public function render()
    {
        $chatConversation =  ChatConversation::find($this->chat);
        $messages = $chatConversation->latestMessages->sortBy('id');

        return view('livewire.chat', compact('messages'));
    }

    public function sendMessage()
    {
        if ($this->messageText === null) return 0;

        ChatMessage::create([
            'chat_conversation_id' => $this->chat,
            'user_id' => Auth::user()->getAuthIdentifier(),
            'text' => $this->messageText,
        ]);

        $this->reset('messageText');
    }
}
