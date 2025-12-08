@extends('admin.layout')

@section('content')
<div class="main-content-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Lecture avant verdict</h3>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <h2 class="h4">{{ $contenu->titre }}</h2>
      <p class="text-muted">Par: {{ $contenu->user?->prenom ?? $contenu->user?->nom ?? 'Auteur inconnu' }} &middot; Publié: {{ $contenu->created_at }}</p>
      <hr/>
      <div class="contenu-texte mt-3">{!! nl2br(e($contenu->texte)) !!}</div>
    </div>
    <div class="card-footer d-flex gap-2">
      <button id="btn-approve" class="btn btn-success">Valider</button>
      <button id="btn-reject" class="btn btn-danger">Rejeter</button>
      <a href="{{ route('moderation.contenus') }}" class="btn btn-outline-secondary ms-auto">Retour</a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const csrf = '{{ csrf_token() }}';
  const id = {{ $contenu->id }};

  document.getElementById('btn-approve').addEventListener('click', function(){
    if (!confirm('Valider ce contenu ?')) return;
    fetch(`/moderation/contenus/${id}/approve`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      credentials: 'same-origin'
    }).then(r => r.json()).then(j => { alert('Contenu validé'); window.location = '{{ route("moderation.contenus") }}'; }).catch(err => { console.error(err); alert('Erreur'); });
  });

  document.getElementById('btn-reject').addEventListener('click', function(){
    const reason = prompt('Saisir un motif de rejet (optionnel)');
    if (reason === null) return; // cancelled
    fetch(`/moderation/contenus/${id}/reject`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({ reason })
    }).then(r => r.json()).then(j => { alert('Contenu rejeté et auteur notifié'); window.location = '{{ route("moderation.contenus") }}'; }).catch(err => { console.error(err); alert('Erreur'); });
  });
});
</script>
@endpush
