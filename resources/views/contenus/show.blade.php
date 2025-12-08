@extends('layouts.app')

@section('title', $contenu->titre)

@section('content')
<article>
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('accueil') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('contenus.index') }}">Articles</a></li>
            <li class="breadcrumb-item active">{{ $contenu->titre }}</li>
        </ol>
    </nav>

    <!-- Article -->
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-3">{{ $contenu->titre }}</h1>
            
            <!-- Métadonnées -->
            <div class="d-flex justify-content-between align-items-center mb-4 text-muted">
                <div>
                    <span>Par {{ $contenu->user->prenom }} {{ $contenu->user->nom }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $contenu->created_at->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="badge bg-primary">{{ $contenu->typecontenu->nomtypec }}</span>
                    <span class="badge bg-secondary">{{ $contenu->region->nom }}</span>
                </div>
            </div>

            <!-- Médias (galerie) -->
            @if($contenu->medias->count() > 0)
            <div class="mb-4">
                <div id="articleCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($contenu->medias as $index => $media)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            @if($media->typemedia->nomtypem === 'image')
                            <img src="{{ asset('storage/' . $media->chemin) }}" 
                                 class="d-block w-100 rounded" 
                                 alt="{{ $media->description }}"
                                 style="max-height: 500px; object-fit: cover;">
                            @elseif($media->typemedia->nomtypem === 'video')
                            <video class="d-block w-100 rounded" controls style="max-height: 500px;">
                                <source src="{{ asset('storage/' . $media->chemin) }}" type="video/mp4">
                            </video>
                            @endif
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                                <p>{{ $media->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($contenu->medias->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
            </div>
            @endif

            <!-- Contenu texte -->
            <div class="article-content mb-5">
                {!! $contenu->texte !!}
            </div>

            <!-- Commentaires -->
            <div class="mt-5">
                <h3>Commentaires ({{ $contenu->commentaires->count() }})</h3>
                
                @auth
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('commentaires.store', $contenu) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="texte" class="form-label">Votre commentaire</label>
                                <textarea class="form-control" id="texte" name="texte" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">Note (1-5)</label>
                                <select class="form-select" id="note" name="note" required>
                                    <option value="5">★★★★★ Excellent</option>
                                    <option value="4">★★★★☆ Très bon</option>
                                    <option value="3">★★★☆☆ Bon</option>
                                    <option value="2">★★☆☆☆ Moyen</option>
                                    <option value="1">★☆☆☆☆ Faible</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Publier</button>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    <a href="{{ route('login') }}">Connectez-vous</a> pour laisser un commentaire.
                </div>
                @endauth

                <!-- Liste des commentaires -->
                @foreach($contenu->commentaires->sortByDesc('published_at') as $commentaire)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="card-title">{{ $commentaire->user->prenom }}</h6>
                            <small class="text-muted">{{ $commentaire->published_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $commentaire->note)
                                    <span class="text-warning">★</span>
                                @else
                                    <span class="text-secondary">☆</span>
                                @endif
                            @endfor
                        </div>
                        <p class="card-text">{{ $commentaire->texte }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <!-- Articles similaires -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Articles similaires</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach($articlesSimilaires as $articleSimilaire)
                            <li class="mb-3">
                                <a href="{{ route('contenus.show', $articleSimilaire->slug) }}" class="text-decoration-none">
                                    <h6 class="mb-1">{{ $articleSimilaire->titre }}</h6>
                                    <small class="text-muted">
                                        {{ $articleSimilaire->created_at->format('d/m/Y') }}
                                    </small>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">À propos de l'article</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Région:</strong> {{ $contenu->region->nom }}
                            </li>
                            <li class="mb-2">
                                <strong>Type:</strong> {{ $contenu->typecontenu->nomtypec }}
                            </li>
                            <li class="mb-2">
                                <strong>Langue:</strong> {{ $contenu->langue->nomlang }}
                            </li>
                            <li class="mb-2">
                                <strong>Commentaires:</strong> {{ $contenu->commentaires->count() }}
                            </li>
                            <li>
                                <strong>Médias:</strong> {{ $contenu->medias->count() }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@endsection

@push('styles')
<style>
.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
}
.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}
</style>
@endpush