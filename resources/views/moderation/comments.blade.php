@extends('admin.layout')

@section('content')
<div class="main-content-container overflow-hidden">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h3 class="mb-0">Modération des commentaires</h3>
  </div>

  <div class="card bg-white border-0 rounded-3 mb-4">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
        <form class="position-relative table-src-form me-0">
          <input id="moderation-search" type="text" class="form-control" placeholder="Rechercher">
          <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
        </form>
      </div>
      <div class="table-responsive">
        <table id="moderation-comments-table" class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Texte</th>
              <th>Auteur</th>
              <th>Contenu</th>
              <th>Publié</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var table = $('#moderation-comments-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"bottom"lp><"clear">',
    ajax: '{{ route("moderation.commentaires.data") }}',
    columns: [
      { data: 'id' },
      { data: 'texte' },
      { data: 'email', orderable: false, searchable: true },
      { data: 'contenu_titre', orderable: false, searchable: true },
      { data: 'published_at' },
      { data: 'statut' },
      { data: null, orderable: false, searchable: false, render: function (data) {
            var validateBtn = '';
            if (data.statut !== 'active') {
              validateBtn = `<button class="btn btn-sm btn-success btn-validate" data-id="${data.id}">Valider</button>`;
            }
            var deleteBtn = `<button class="btn btn-sm btn-outline-danger btn-delete" data-id="${data.id}">Supprimer</button>`;
            return validateBtn + ' ' + deleteBtn;
        }}
    ]
  });

  $('#moderation-search').on('input', function(){ table.search(this.value).draw(); });

  // CSRF token
  const csrf = '{{ csrf_token() }}';

  // Validate handler
  $(document).on('click', '.btn-validate', function(){
    const id = $(this).data('id');
    if (!confirm('Valider ce commentaire ?')) return;
    fetch(`/moderation/commentaires/${id}/validate`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      credentials: 'same-origin'
    }).then(r => r.json()).then(j => { table.ajax.reload(null, false); }).catch(err => { console.error(err); alert('Erreur'); });
  });

  // Delete handler
  $(document).on('click', '.btn-delete', function(){
    const id = $(this).data('id');
    if (!confirm('Supprimer ce commentaire définitivement ?')) return;
    fetch(`/moderation/commentaires/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    }).then(r => r.json()).then(j => { table.ajax.reload(null, false); }).catch(err => { console.error(err); alert('Erreur'); });
  });
});
</script>
@endpush
