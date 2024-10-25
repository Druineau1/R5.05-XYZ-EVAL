<x-app :title="$week->name">
    <main class="container-wide two-cols space-y-8">

        <section>
            <h1>
                {{ $week->name }} <small>{{ trans_choice('tracks.count', $week->tracks_count) }}</small>
            </h1>

            <ol class="chart">
                @foreach($tracks as $track)
                <li>
                    <a href="{{ route('app.tracks.show', ['week' => $week->uri, 'track' => $track]) }}">
                        <span class="position">{{ $loop->iteration }}.</span>
                        <img src="{{ $track->player_thumbnail_url }}" alt="">
                        <div class="details">
                            <h1 class="truncate">{{ $track->artist }}</h1>
                            <h2 class="truncate">{{ $track->title }}</h2>

                            <div class="metadata">
                                <div class="metadata-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $track->user->username }}</span>
                                </div>

                                <div class="metadata-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" />
                                    </svg>
                                    <span>{{ trans_choice('tracks.likes', $track->likes_count) }}</span>
                                </div>

                                <div class="metadata-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="M4.5 2A2.5 2.5 0 0 0 2 4.5v3.879a2.5 2.5 0 0 0 .732 1.767l7.5 7.5a2.5 2.5 0 0 0 3.536 0l3.878-3.878a2.5 2.5 0 0 0 0-3.536l-7.5-7.5A2.5 2.5 0 0 0 8.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $track->category ? $track->category->name : 'Non catégorisé' }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <svg class="size-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </a>
                </li>
                @endforeach
            </ol>
        </section>

        <div>
            <div class="block block-content space-y-8">
                
                @if($isCurrent)
                <div class="title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                    </svg>                      
                    <div>
                        <h1>Il est encore temps</h1>
                        <h2>{{ $week->name }}</h2>
                    </div>
                </div>

                <p>
                    Les contributions pour la semaine {{ $week->weekNumber }} sont ouvertes.
                </p>

                <div class="space-y-2">
                    <dl>
                        <dt>Temps restant</dt>
                        <dd>{{ round(now()->diffInHours($week->week_ends_at)) }}h</dd>

                        <dt>Limite</dt>
                        <dd>{{ $week->week_ends_at->format("d/m/Y H:i:s") }} UTC</dd>
                    </dl>

                    @can('create', App\Models\Track::class)
                    <div>
                        <a href="{{ route('app.tracks.create') }}">
                            <button class="secondary w-full">Contribuer</button>
                        </a>
                    </div>
                    @endcan
                </div>
                @else
                <div class="title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" />
                    </svg>                   
                    <div>
                        <h1>Synthèse de la semaine</h1>
                    </div>
                </div>

                <div class="space-y-2">
                    <dl>
                        <dt>Contributions</dt>
                        <dd>{{ trans_choice('tracks.count', $week->tracks_count) }}</dd>

                        <dt>Période</dt>
                        <dd>Du {{ $week->week_starts_at->format("d/m/Y") }} au {{ $week->week_ends_at->format("d/m/Y") }}</dd>
                    </dl>
                </div>
                @endif

            </div>
        </div>
    </main>
</x-app>
