@extends('admin.layout')

@section('content')
<div class="main-content-container overflow-hidden">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h3 class="mb-0">Medias</h3>
  </div>

  <div class="card bg-white border-0 rounded-3 mb-4" id="medias">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
        <form class="position-relative table-src-form me-0">
          <input id="medias-search" type="text" class="form-control" placeholder="Search here">
          <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
        </form>
        <button class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                    <span class="py-sm-1 d-block">
                                        <i class="ri-add-line d-none d-sm-inline-block"></i>
                                        <span>Add New Media</span>
                                    </span>
                                </button>
      </div>
      <div class="table-responsive">
        <table id="medias-table" class="table align-middle">
          <thead>
            <tr><th>ID</th><th>Chemin</th><th>Description</th><th>TypeMedia</th><th>Actions</th></tr>
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
          <h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel">Create Media</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
          <form method="POST" action="{{ route('admin.medias.store') }}">
            @csrf
            <div class="form-group mb-3">
              <label>Chemin</label>
              <input type="text" name="chemin" class="form-control" placeholder="Chemin">
            </div>
            <div class="form-group mb-3">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group mb-3">
              <label>Type Media</label>
              <select name="typemedia_id" class="form-select form-control">
                <option value="">-- Choisir --</option>
                @foreach($typemedias as $id => $nom)
                  <option value="{{ $id }}">{{ $nom }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-3">
              <label>Contenu</label>
              <select name="contenu_id" class="form-select form-control">
                <option value="">-- Choisir --</option>
                @foreach($contenus as $id => $titre)
                  <option value="{{ $id }}">{{ $titre }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group d-flex gap-3">
              <button class="btn btn-primary" type="submit">Create Media</button>
            </div>
          </form>
        </div>
      </div>

      <!-- offcanvas for show/edit -->
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMedia">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasMediaLabel">Media</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <form id="mediaForm" method="POST">
            @csrf
            <input type="hidden" name="id" id="media_id">
            <div class="form-group mb-3">
              <label>Chemin</label>
              <input type="text" name="chemin" id="chemin" class="form-control">
            </div>
            <div class="form-group mb-3">
              <label>Description</label>
              <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group mb-3">
              <label>Type Media</label>
              <select name="typemedia_id" id="typemedia_id" class="form-select form-control">
                <option value="">-- Choisir --</option>
                @foreach($typemedias as $id => $nom)
                  <option value="{{ $id }}">{{ $nom }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-3">
              <label>Contenu</label>
              <select name="contenu_id" id="contenu_id" class="form-select form-control">
                <option value="">-- Choisir --</option>
                @foreach($contenus as $id => $titre)
                  <option value="{{ $id }}">{{ $titre }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-primary" id="submitMedia">Enregistrer</button>
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var table = $('#medias-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"bottom"lp><"clear">',
    ajax: '{{ route("admin.medias.data") }}',
    columns: [
      { data: 'id' },
      { data: 'chemin' },
      { data: 'description' },
      { data: 'typemedia_id' },
      { data: null, orderable: false, searchable: false, render: function (data) {
                return `
<button class="btn btn-outline-secondary"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasMedia"
        data-mode="read"
        data-id="${data.id}">
  <i class="bi bi-eye"></i>
</button>

<button class="btn btn-primary"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasMedia"
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

  $('#medias-search').on('input', function(){ table.search(this.value).draw(); });
  
  const offcanvasMediaEl = document.getElementById('offcanvasMedia');
  const mediaForm = document.getElementById('mediaForm');
  const mediaInputs = mediaForm.querySelectorAll('input, textarea, select');
  const submitMedia = document.getElementById('submitMedia');

  offcanvasMediaEl.addEventListener('show.bs.offcanvas', function (event) {
    const trigger = event.relatedTarget;
    if (!trigger) return;
    const mode = trigger.getAttribute('data-mode');
    const id = trigger.getAttribute('data-id');

    fetch(`/admin/medias/${id}`)
      .then(r => r.json())
      .then(data => {
        document.getElementById('media_id').value = data.id;
        document.getElementById('chemin').value = data.chemin || '';
        document.getElementById('description').value = data.description || '';
      }).catch(console.error);

    if (mode === 'read') {
      mediaInputs.forEach(el => el.setAttribute('readonly', true));
      submitMedia.style.display = 'none';
    } else {
      mediaInputs.forEach(el => el.removeAttribute('readonly'));
      submitMedia.style.display = '';
      mediaForm.setAttribute('action', `/admin/medias/${id}`);
      ensureMethodPut(mediaForm);
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

  let deleteMediaId = null;
  $(document).on('click', '.btn-delete', function(){ deleteMediaId = $(this).data('id'); });

  $('#confirmDelete').on('click', function(){
    if (!deleteMediaId) return;
    fetch('/admin/medias/' + deleteMediaId, {
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
