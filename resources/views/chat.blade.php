<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messagerie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row">
                <!-- Colonne des contacts -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Contacts</div>
                        <div class="list-group list-group-flush">
                            @foreach($users as $user)
                                <a href="{{ route('chat.show', $user->id) }}" class="list-group-item list-group-item-action">
                                    {{ $user->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Colonne de la conversation -->
                <div class="col-md-8">
                    @if(isset($selectedConversation))
                        <div class="card">
                            <div class="card-header">
                                Conversation avec {{ $selectedConversation->users->where('id', '!=', auth()->id())->first()->name }}
                            </div>
                            <div class="card-body" id="chat-box" style="height: 400px; overflow-y: scroll;">
                                <!-- Les messages seront chargés ici -->
                                @foreach($selectedConversation->messages as $message)
                                    <div class="mb-2 {{ $message->user_id == auth()->id() ? 'text-end' : '' }}">
                                        <small>{{ $message->user->name }}</small><br>
                                        <span class="badge rounded-pill {{ $message->user_id == auth()->id() ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ $message->body }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="card-footer">
                                <form id="message-form" action="{{ route('chat.store', $selectedConversation->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="body" class="form-control" placeholder="Écrivez votre message..." required>
                                        <button type="submit" class="btn btn-primary">Envoyer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center">
                                Sélectionnez un contact pour démarrer une conversation.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    @if(isset($selectedConversation))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const chatBox = document.getElementById('chat-box');

                if (!chatBox) {
                    return;
                }
                
                const scrollToBottom = () => {
                    chatBox.scrollTop = chatBox.scrollHeight;
                };

                scrollToBottom();

                // On garde l'écoute des messages ENTRANTS.
                // Ce code est pour recevoir les messages des autres.
                window.Echo.private('conversation.{{ $selectedConversation->id }}')
                    .listen('MessageSent', (e) => {
                        const avatarUrl = e.message.user.avatar.startsWith('http') ? e.message.user.avatar : `/storage/${e.message.user.avatar}`;
                        const messageHtml = `
                            <div class="message received">
                                <img src="${avatarUrl}" alt="${e.message.user.name}" class="message-avatar">
                                <div class="message-bubble">
                                    ${e.message.body}
                                </div>
                            </div>
                        `;
                        chatBox.insertAdjacentHTML('beforeend', messageHtml);
                        scrollToBottom();
                    });

                // ===============================================
                // TOUTE LA PARTIE 'messageForm.addEventListener' A ÉTÉ SUPPRIMÉE.
                // ===============================================

            });
        </script>
    @endif
@endpush
</x-app-layout>