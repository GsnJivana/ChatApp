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
    <script>
        // Le code Javascript pour le temps réel viendra ici
        @if(isset($selectedConversation))
document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chat-box');
    const messageForm = document.getElementById('message-form');
    const messageInput = messageForm.querySelector('input[name="body"]');

    // Faire défiler la boîte de chat vers le bas
    chatBox.scrollTop = chatBox.scrollHeight;

    // Écouter les événements sur le canal de la conversation
    window.Echo.private('conversation.{{ $selectedConversation->id }}')
        .listen('MessageSent', (e) => {
            console.log('Nouveau message reçu:', e);
            
            // Créer le HTML du nouveau message
            const messageHtml = `
                <div class="mb-2">
                    <small>${e.message.user.name}</small><br>
                    <span class="badge rounded-pill bg-secondary">
                        ${e.message.body}
                    </span>
                </div>
            `;

            // Ajouter le message à la boîte de chat et faire défiler
            chatBox.innerHTML += messageHtml;
            chatBox.scrollTop = chatBox.scrollHeight;
        });

    // Intercepter l'envoi du formulaire via AJAX
    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        fetch(messageForm.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ body: messageInput.value })
        })
        .then(response => response.json())
        .then(data => {
            // Ajouter le message de l'expéditeur directement à l'interface
            const messageHtml = `
                <div class="mb-2 text-end">
                    <small>Vous</small><br>
                    <span class="badge rounded-pill bg-primary">
                        ${messageInput.value}
                    </span>
                </div>
            `;
            chatBox.innerHTML += messageHtml;
            chatBox.scrollTop = chatBox.scrollHeight;
            messageInput.value = ''; // Vider le champ de saisie
        });
    });
});
@endif
    </script>
    @endpush
</x-app-layout>