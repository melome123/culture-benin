@extends('admin.layout')

@section('content')
    <div class="main-content-container overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                      <h3 class="mb-0">Regions</h3>

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
                                    <span class="fw-medium">Users</span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card bg-white border-0 rounded-3 mb-4" id="langues">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
                                <form class="position-relative table-src-form me-0">
                                  <input type="text" class="form-control" placeholder="Search here" id="regions-search">
                                    <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                                </form>
                                <button class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                    <span class="py-sm-1 d-block">
                                        <i class="ri-add-line d-none d-sm-inline-block"></i>
                                    <span>Add New Region</span>
                                    </span>
                                </button>
                            </div>

                            <div class="default-table-area style-two default-table-width">
                                <div id="regions" class="table-responsive">
                                    <table id="regions-table" class="table align-middle">
                                        <thead>
                                            <tr>
                                             <th scope="col">ID</th>
                                             <th scope="col">Nom</th>
                                             <th scope="col">Population</th>
                                             <th scope="col">Superficie</th>
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
                <!--offcanvas-->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header border-bottom p-4">
                <h5 class="offcanvas-title fs-18 mb-0" id="offcanvasRightLabel">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-4">
                <form method="POST" action="{{route('admin.regions.store')}}">
                    @csrf
                    <div class="form-group mb-4">
    <label class="label">Nom</label>
    <input type="text" name="nom" class="form-control text-dark" placeholder="Nom de la langue">
</div>
<div class="form-group mb-4">
    <label class="label">Population</label>
    <input type="text" name="population" class="form-control text-dark" placeholder="Code de la langue">
</div>
<div class="form-group mb-4">
    <label class="label">Superficie</label>
    <input type="text" name="superficie" class="form-control text-dark" placeholder="Code de la langue">
</div>
<div class="form-group mb-4">
    <label class="label">Description</label>
    <textarea name="description" class="form-control text-dark" placeholder="Description"></textarea>
</div>

                    
                    <div class="form-group d-flex gap-3">
                        <button class="btn btn-primary text-white fw-semibold py-2 px-2 px-sm-3" type="submit">
                            <span class="py-sm-1 d-block">
                                <i class="ri-add-line text-white"></i>
                                <span>Create Langue</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRegion">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRegionLabel">Region</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="regionForm" method="POST">
      @csrf
        <input type="hidden" name="id" id="region_id">

        <div class="form-group mb-3">
          <label>Nom</label>
          <input type="text" name="nom" id="nom" class="form-control">
        </div>

        <div class="form-group mb-3">
          <label>Population</label>
          <input type="text" name="population" id="population" class="form-control">
        </div>

         <div class="form-group mb-3">
          <label>Superficie</label>
          <input type="text" name="superficie" id="superficie" class="form-control">
        </div>

        <div class="form-group mb-3">
          <label>Description</label>
          <textarea name="description" id="description" class="form-control"></textarea>
        </div>

      <button type="submit" class="btn btn-primary" id="submitBtn">Enregistrer</button>
    </form>
  </div>
</div>

        
    <!-- Modal global -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Voulez-vous vraiment supprimer cet utilisateur ?
      </div>
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
document.addEventListener('DOMContentLoaded', function () {
  const offcanvasEl = document.getElementById('offcanvasRegion');
  const form = document.getElementById('regionForm');
  const inputs = form.querySelectorAll('input, textarea, select');
  const submitBtn = document.getElementById('submitBtn');
  const titleEl = document.getElementById('offcanvasRegionLabel');

  offcanvasEl.addEventListener('show.bs.offcanvas', function (event) {
    const trigger = event.relatedTarget;
    const mode = trigger.getAttribute('data-mode');
    const id = trigger.getAttribute('data-id');

    // Charger les données via AJAX
    fetch(`/admin/regions/${id}`)
      .then(r => r.json())
      .then(data => {
        document.getElementById('region_id').value = data.id;
        document.getElementById('nom').value = data.nom;
        document.getElementById('population').value = data.population;
        document.getElementById('superficie').value = data.superficie;
        document.getElementById('description').value = data.description;
      });

    if (mode === 'read') {
      inputs.forEach(el => {
        el.setAttribute('readonly', true);
        if (el.tagName === 'SELECT') el.setAttribute('disabled', true);
      });
      submitBtn.style.display = 'none';
      titleEl.textContent = 'Region (lecture)';
    } else {
      inputs.forEach(el => {
        el.removeAttribute('readonly');
        if (el.tagName === 'SELECT') el.removeAttribute('disabled');
      });
      submitBtn.style.display = '';
      titleEl.textContent = 'Region (édition)';
      form.setAttribute('action', `/admin/regions/${id}`);
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
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function(){
    $('#preloader').show();

    var table = $('#regions-table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'rt<"bottom"lp><"clear">',
        ajax: '{{ route("admin.regions.data") }}',
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'nom', title: 'Nom' },
            { data: 'superficie', title: 'Superficie' },
            { data: 'population', title: 'Population' },
            { data: null, orderable: false, searchable: false, render: function (data) {
                return `
<button class="btn btn-outline-secondary"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasRegion"
        data-mode="read"
        data-id="${data.id}">
  <i class="bi bi-eye"></i>
</button>

<button class="btn btn-primary"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasRegion"
        data-mode="edit"
        data-id="${data.id}">
  <i class="bi bi-pencil"></i>
</button>

<button class="btn btn-sm btn-outline-danger btn-delete" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">
  <i class='bi bi-trash'></i>
</button>
                `;
            }}
        ],
        initComplete: function () {
            $('#preloader').fadeOut();
        }
    });

    table.on('xhr.dt', function () {
        $('#preloader').fadeOut();
    });

    let deleteId = null;

    $(document).on('click', '.btn-delete', function(){
        deleteId = $(this).data('id');
    });

    $('#confirmDelete').on('click', function(){
        if (!deleteId) return;

        fetch('/admin/regions/' + deleteId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(j => {
            table.ajax.reload(null, false);
            $('#deleteModal').modal('hide');
        })
        .catch(console.error);
    });
});
</script>
@endpush

