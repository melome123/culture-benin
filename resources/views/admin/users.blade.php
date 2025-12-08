@extends('admin.layout')

@section('content')
    <div class="main-content-container overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <h3 class="mb-0">Users</h3>

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

                    <div class="card bg-white border-0 rounded-3 mb-4" id="users">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
                                <form class="position-relative table-src-form me-0">
                                    <input id="users-search" type="text" class="form-control" placeholder="Search here">
                                    <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
                                </form>
                                <a href="{{URL('/admin/users/create')}}">
                                    <button class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg">
                                        <span class="py-sm-1 d-block">
                                          <i class="ri-add-line d-none d-sm-inline-block"></i>
                                          <span>Add New User</span>
                                      </span>
                                    </button>
                                </a>
                            </div>

                            <div class="default-table-area style-two default-table-width">
                                <div class="table-responsive">
                                    <table id="users-table" class="table align-middle">
                                        <thead>
                                            <tr>
                                             <th scope="col">ID</th>
                                             <th scope="col">Nom</th>
                                             <th scope="col">Prénom</th>
                                             <th scope="col">Date de naissance</th>
                                             <th scope="col">Role</th>
                                             <th scope="col">Langue</th>
                                             <th scope="col">Region</th>
                                             <th scope="col">Actions</th>
                                           </tr>
                                        </thead>
                                       <tbody ></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
document.addEventListener("DOMContentLoaded", function(){
    var table = $('#users-table').DataTable({
    processing: true,
    serverSide: true,
    // remove the default DataTables search box ('f') from the layout
    dom: 'rt<"bottom"lp><"clear">',
    searching: true,
   ajax: {
    url: '{{ route("admin.users.data") }}',
    dataSrc: function(json) {
        console.log("Données reçues :", json.data);
        return json.data;
    }
},
    columns: [
        { data: 'id', title: 'ID' },
        { data: 'nom', title: 'Nom' },
        { data: 'prenom', title: 'Prénom' },
        { data: 'date_naissance', title: 'Date de naissance' },
        { data: 'role', title: 'Rôle' },
        { data: 'langue', title: 'Langue' },
        { data: 'region', title: 'Région' },
        { data: null, orderable: false, searchable: false, render: function (data) {
    return `
        <a href="/admin/users/${data.id}" class="btn btn-sm btn-outline-secondary"><i class='bi bi-eye'></i></a>
        <a href="/admin/users/${data.id}/edit" class="btn btn-sm btn-outline-primary"><i class='bi bi-pencil'></i></a>
        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${data.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class='bi bi-trash'></i>
        </button>
    `;
}}
        ]
          
    });
    // Connect custom search input (id="users-search") to the DataTable search
    var $search = $('#users-search');
    if ($search.length) {
        var debounce;
        $search.on('input', function() {
            var q = this.value;
            clearTimeout(debounce);
            debounce = setTimeout(function() { table.search(q).draw(); }, 250);
        });
    }
    let deleteId = null;

$(document).on('click', '.btn-delete', function(){
    deleteId = $(this).data('id'); // stocke l'id
});

$('#confirmDelete').on('click', function(){
    if (!deleteId) return;

    fetch('/admin/users/' + deleteId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(j => {
        $('#users-table').DataTable().ajax.reload();
        $('#deleteModal').modal('hide');
    })
    .catch(console.error);
});

});
</script>
@endpush