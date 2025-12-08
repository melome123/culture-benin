@extends('admin.layout')

@section('content')
    <div class="main-content-container overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <h3 class="mb-0">Contenus</h3>

                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb align-items-center mb-0 lh-1">
                                <li class="breadcrumb-item">
                                    <a href="#" class="d-flex align-items-center text-decoration-none">
                                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                                        <span class="text-secondary fw-medium hover">Dashboard</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span class="fw-medium">Apps</span>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span class="fw-medium">Contenus</span>
                                </li>
                            </ol>
                        </nav>
                    </div>

  <div class="card bg-white border-0 rounded-3 mb-4" id="contenus">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
        <form class="position-relative table-src-form me-0">
          <input id="contenus-search" type="text" class="form-control" placeholder="Search here">
          <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
        </form>
        <button class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
          <span class="py-sm-1 d-block">
            <i class="ri-add-line d-none d-sm-inline-block"></i>
            <span>Add New Contenu</span>
          </span>
        </button>
      </div>

      <div class="default-table-area style-two default-table-width">
        <div class="table-responsive">
          <table id="contenus-table" class="table align-middle">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Titre</th>
                <th scope="col">Statut</th>
                <th scope="col">Date validation</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- create offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header border-bottom p-4">
    <h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel">Create Contenu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-4">
    <form method="POST" action="{{ route('admin.contenus.store') }}">
      @csrf
      <div class="form-group mb-4">
        <label class="label">Titre</label>
        <input type="text" name="titre" class="form-control text-dark" placeholder="Titre">
      </div>
      <div class="form-group mb-4">
        <label class="label">Texte</label>
        <textarea name="texte" class="form-control text-dark" placeholder="Texte"></textarea>
      </div>
      <div class="form-group mb-4">
        <label class="label">Langue</label>
        <select name="langue_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($langues as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mb-4">
        <label class="label">Région</label>
        <select name="region_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($regions as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mb-4">
        <label class="label">Type Contenu</label>
        <select name="typecontenu_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($typecontenus as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
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
        <button class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" type="submit">
          <span class="py-sm-1 d-block">
            <i class="ri-add-line text-white"></i>
            <span>Create Contenu</span>
          </span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- offcanvas for show/edit -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasContenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasContenuLabel">Contenu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="contenuForm" method="POST">
      @csrf
      <input type="hidden" name="id" id="contenu_id">
      <div class="form-group mb-3">
        <label>Titre</label>
        <input type="text" name="titre" id="titre" class="form-control">
      </div>
      <div class="form-group mb-3">
        <label>Texte</label>
        <textarea name="texte" id="texte" class="form-control"></textarea>
      </div>
      <div class="form-group mb-3">
        <label>Langue</label>
        <select name="langue_id" id="langue_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($langues as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mb-3">
        <label>Région</label>
        <select name="region_id" id="region_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($regions as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group mb-3">
        <label>Type Contenu</label>
        <select name="typecontenu_id" id="typecontenu_id" class="form-select form-control">
          <option value="">-- Choisir --</option>
          @foreach($typecontenus as $id => $nom)
            <option value="{{ $id }}">{{ $nom }}</option>
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
  var table = $('#contenus-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"bottom"lp><"clear">',
    ajax: '{{ route("admin.contenus.data") }}',
    columns: [
      { data: 'id' },
      { data: 'titre' },
      { data: 'statut' },
      { data: 'date_valid' },
      { data: null, orderable: false, searchable: false, render: function (data) {
                return `
<button class="btn btn-outline-secondary"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasContenu"
        data-mode="read"
        data-id="${data.id}">
  <i class="bi bi-eye"></i>
</button>

<button class="btn btn-primary"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasContenu"
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

  $('#contenus-search').on('input', function(){ table.search(this.value).draw(); });

  const offcanvasEl = document.getElementById('offcanvasContenu');
  const form = document.getElementById('contenuForm');
  const inputs = form.querySelectorAll('input, textarea, select');
  const submitBtn = document.getElementById('submitBtn');
  const titleEl = document.getElementById('offcanvasContenuLabel');

  offcanvasEl.addEventListener('show.bs.offcanvas', function (event) {
    const trigger = event.relatedTarget;
    const mode = trigger.getAttribute('data-mode');
    const id = trigger.getAttribute('data-id');

    fetch(`/admin/contenus/${id}`)
      .then(r => r.json())
      .then(data => {
        document.getElementById('contenu_id').value = data.id;
        document.getElementById('titre').value = data.titre;
        document.getElementById('texte').value = data.texte;
        document.getElementById('langue_id').value = data.langue_id || '';
        document.getElementById('region_id').value = data.region_id || '';
        document.getElementById('typecontenu_id').value = data.typecontenu_id || '';
        document.getElementById('user_id').value = data.user_id || '';
      });

    if (mode === 'read') {
      inputs.forEach(el => {
        el.setAttribute('readonly', true);
        if (el.tagName === 'SELECT') el.setAttribute('disabled', true);
      });
      submitBtn.style.display = 'none';
      titleEl.textContent = 'Contenu (lecture)';
    } else {
      inputs.forEach(el => {
        el.removeAttribute('readonly');
        if (el.tagName === 'SELECT') el.removeAttribute('disabled');
      });
      submitBtn.style.display = '';
      titleEl.textContent = 'Contenu (édition)';
      form.setAttribute('action', `/admin/contenus/${id}`);
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
    fetch('/admin/contenus/' + deleteId, {
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

