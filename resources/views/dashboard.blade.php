<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Tableau de Bord TaskFloWeb') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Nouvelle Tâche') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Ajoutez quelque chose à faire pour aujourd'hui.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('tasks.store') }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="title" :value="__('Titre')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Ajouter') }}</x-primary-button>

                                @if (session('status') === 'task-created')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Enregistré.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Mes Tâches en cours') }}
                    </h2>
                </header>

                <div class="space-y-4">
                    @forelse($tasks as $task)
                        <div class="flex items-center justify-between p-4 bg-gray-50 border-l-4 {{ $task->is_completed ? 'border-green-500' : 'border-orange-400' }} rounded shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($task->is_completed)
                                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <div class="h-6 w-6 rounded-full border-2 border-orange-300"></div>
                                    @endif
                                </div>
                                
                                <div>
                                    <p class="text-gray-800 {{ $task->is_completed ? 'line-through text-gray-400' : 'font-medium' }}">
                                        {{ $task->title }}
                                    </p>
                                    <span class="text-xs text-gray-500 italic">Créée le {{ $task->created_at->format('d/m à H:i') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <form action="{{ route('tasks.update', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        {{ $task->is_completed ? __('Annuler') : __('Terminer') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">{{ __("Vous n'avez pas encore de tâches. Respirez, tout va bien !") }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>