@extends('admin.layout')

@section('content')
<div class="main-content-container overflow-hidden">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h3 class="mb-0">Commentaires</h3>
  </div>

  <div class="card bg-white border-0 rounded-3 mb-4" id="commentaires">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
        <form class="position-relative table-src-form me-0">
          <input id="commentaires-search" type="text" class="form-control" placeholder="Search here">
          <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
        </form>
        <button class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Add New Commentaire</button>
      </div>
      <div class="table-responsive">
        <table id="commentaires-table" class="table align-middle">
          <thead>
            <tr><th>ID</th><th>Texte</th><th>Publié</th><th>Actions</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- create offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header border-bottom p-4">
    <h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel">Create Commentaire</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-4">
    <form method="POST" action="{{ route('admin.commentaires.store') }}">
      @csrf
      <div class="form-group mb-4">
        <label class="label">Texte</label>
        <textarea name="texte" class="form-control text-dark" placeholder="Texte"></textarea>
      </div>
      <div class="form-group mb-4">
        <label class="label">Contenu</label>
        <select name="idcontenu" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($contenus as $id => $titre)
            <option value="{{ $id }}">{{ $titre }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mb-4">
        <label class="label">Utilisateur</label>
        <select name="user_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($users as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group d-flex gap-3">
        <button class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" type="submit">Create Commentaire</button>
      </div>
    </form>
  </div>
</div>

<!-- offcanvas for show/edit -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCommentaire">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasCommentaireLabel">Commentaire</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="commentaireForm" method="POST">
      @csrf
      <input type="hidden" name="id" id="commentaire_id">
      <div class="form-group mb-3">
        <label>Texte</label>
        <textarea name="texte" id="texte" class="form-control"></textarea>
      </div>
      <div class="form-group mb-3">
        <label>Contenu</label>
        <select name="idcontenu" id="idcontenu" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($contenus as $id => $titre)
            <option value="{{ $id }}">{{ $titre }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mb-3">
        <label>Utilisateur</label>
        <select name="user_id" id="user_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($users as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary" id="submitBtn">Enregistrer</button>
    </form>
  </div>
</div>

<!-- delete modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Voulez-vous vraiment supprimer cet élément ?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var table = $('#commentaires-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"bottom"lp><"clear">',
    ajax: '{{ route("admin.commentaires.data") }}',
    columns: [
      { data: 'id' },
      { data: 'texte' },
      { data: 'published_at' },
      { data: null, orderable: false, searchable: false, render: function (data) {
                return `
<button class="btn btn-outline-secondary"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasCommentaire"
        data-mode="read"
        data-id="${data.id}">
  <i class="bi bi-eye"></i>
</button>

<button class="btn btn-primary"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasCommentaire"
        data-mode="edit"
        data-id="${data.id}">
  <i class="bi bi-pencil"></i>
</button>

<button class="btn btn-sm btn-outline-danger btn-delete" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">
  <i class='bi bi-trash'></i>
</button>
                `;
            }}
    ]
  });

  $('#commentaires-search').on('input', function(){ table.search(this.value).draw(); });

  const offcanvasEl = document.getElementById('offcanvasCommentaire');
  const form = document.getElementById('commentaireForm');
  const inputs = form.querySelectorAll('input, textarea, select');
  const submitBtn = document.getElementById('submitBtn');
  const titleEl = document.getElementById('offcanvasCommentaireLabel');

  offcanvasEl.addEventListener('show.bs.offcanvas', function (event) {
    const trigger = event.relatedTarget;
    const mode = trigger.getAttribute('data-mode');
    const id = trigger.getAttribute('data-id');

    fetch(`/admin/commentaires/${id}`)
      .then(r => r.json())
      .then(data => {
        document.getElementById('commentaire_id').value = data.id;
        document.getElementById('texte').value = data.texte;
      });

    if (mode === 'read') {
      inputs.forEach(el => el.setAttribute('readonly', true));
      submitBtn.style.display = 'none';
      titleEl.textContent = 'Commentaire (lecture)';
    } else {
      inputs.forEach(el => el.removeAttribute('readonly'));
      submitBtn.style.display = '';
      titleEl.textContent = 'Commentaire (édition)';
      form.setAttribute('action', `/admin/commentaires/${id}`);
      ensureMethodPut(form);
    }
  });

  function ensureMethodPut(form) {
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
      methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'PUT';
      form.appendChild(methodInput);
    }
  }

  let deleteId = null;
  $(document).on('click', '.btn-delete', function(){ deleteId = $(this).data('id'); });

  $('#confirmDelete').on('click', function(){
    if (!deleteId) return;
    fetch('/admin/commentaires/' + deleteId, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    }).then(r => r.json()).then(j => { table.ajax.reload(null, false); $('#deleteModal').modal('hide'); }).catch(console.error);
  });
});
</script>
@endpush
