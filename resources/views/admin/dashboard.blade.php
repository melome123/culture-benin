@extends('admin.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-6">
            <div class="card bg-primary border-0 rounded-3 welcome-box style-two mb-4 position-relative">
                <div class="card-body py-38 px-4">
                        <div class="mb-5">
                            <h3 class="text-white fw-semibold">Welcome Back, <span class="text-danger-div">{{ Auth::user()->nom }}</span></h3>
                                <p class="text-light">Your progress this week is Awesome.</p>
                        </div>

                        <div class="d-flex align-items-center flex-wrap gap-4 gap-xxl-5">
                            <div class="d-flex align-items-center welcome-status-item style-two">
                                <div class="flex-shrink-0">
                                    <i class="material-symbols-outlined">airplay</i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="text-white fw-semibold mb-0 fs-16">75h</h5>
                                    <p class="text-light">Hours Spent</p>
                                </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <span>Contenus</span>
                                            <h3 class="mb-0 fs-20">{{$c}}</h3>
                                            <div class="py-3">
                                                <div class="wh-77 lh-97 text-center m-auto bg-primary bg-opacity-25 rounded-circle">
                                                    <i class="material-symbols-outlined fs-32 text-primary">auto_stories</i>
                                                </div> 
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12">This Month</span>
                                                <i class="material-symbols-outlined text-success">timeline</i>5
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <span>Media</span>
                                            <h3 class="mb-0 fs-20"> {{$m}} </h3>
                                            <div class="py-3">
                                                <div class="wh-77 lh-97 text-center m-auto bg-primary-div bg-opacity-25 rounded-circle">
                                                    <i class="material-symbols-outlined fs-32 text-primary-div">video_library</i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12">This Month</span>
                                                <i class="material-symbols-outlined text-danger">trending_down</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="card bg-white border-0 rounded-3 mb-4">
                                        <div class="card-body p-4">
                                            <span>Users</span>
                                            <h3 class="mb-0 fs-20"> {{$u}}</h3>
                                            <div class="py-3">
                                                <div class="wh-77 lh-97 text-center m-auto bg-danger bg-opacity-25 rounded-circle">
                                                    <i class="material-symbols-outlined fs-32 text-danger">group</i>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-12">This Month</span>
                                                <i class="material-symbols-outlined text-success">trending_up</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<div class="main-content-container overflow-hidden">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h3 class="mb-0">Demandes d'inscription</h3>
  </div>

  <div class="card bg-white border-0 rounded-3 mb-4" id="demandes">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
        <form class="position-relative table-src-form me-0">
          <input id="demandes-search" type="text" class="form-control" placeholder="Search here">
          <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
        </form>
      </div>

      <div class="default-table-area style-two default-table-width">
        <div class="table-responsive">
          <table id="demandes-table" class="table align-middle">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Email</th>
                <th scope="col">Date demande</th>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmTitle">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="confirmBody">
        Êtes-vous sûr ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="confirmAction">Confirmer</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var table = $('#demandes-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"bottom"lp><"clear">',
    ajax: '{{ route("admin.demandes.data") }}',
    columns: [
      { data: 'id' },
      { data: 'nom' },
      { data: 'prenom' },
      { data: 'email' },
      { data: 'date_demande' },
      { data: 'actions', orderable: false, searchable: false }
    ]
  });

  $('#demandes-search').on('input', function(){ 
    table.search(this.value).draw(); 
  });

  let currentAction = null;
  let currentId = null;

  $(document).on('click', '.btn-approve', function(){
    currentAction = 'approve';
    currentId = $(this).data('id');
    $('#confirmTitle').text('Approuver la demande');
    $('#confirmBody').text('Confirmer l\'approbation de cette demande ? L\'utilisateur aura le rôle d\'utilisateur standard.');
    $('#confirmModal').modal('show');
  });

  $(document).on('click', '.btn-reject', function(){
    currentAction = 'reject';
    currentId = $(this).data('id');
    $('#confirmTitle').text('Rejeter la demande');
    $('#confirmBody').text('Êtes-vous sûr de vouloir rejeter cette demande ?');
    $('#confirmModal').modal('show');
  });

  $('#confirmAction').on('click', function(){
    if (!currentAction || !currentId) return;

    const endpoint = currentAction === 'approve' 
      ? `/admin/demandes/${currentId}/approve`
      : `/admin/demandes/${currentId}/reject`;

    fetch(endpoint, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(j => {
      table.ajax.reload(null, false);
      $('#confirmModal').modal('hide');
      currentAction = null;
      currentId = null;
    })
    .catch(console.error);
  });
});
</script>
@endpush
@section('me')
    <h3>{{ Auth::user()->nom }}</h3>
@endsection