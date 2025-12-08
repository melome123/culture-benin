@extends('admin.layout')

@section('content')
<div class="main-content-container overflow-hidden">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h3 class="mb-0">Modération des contenus</h3>
  </div>

  <div class="card bg-white border-0 rounded-3 mb-4">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-4">
        <form class="position-relative table-src-form me-0">
          <input id="moderation-contenus-search" type="text" class="form-control" placeholder="Rechercher">
          <i class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y">search</i>
        </form>
      </div>
      <div class="table-responsive">
        <table id="moderation-contenus-table" class="table align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Titre</th>
              <th>Auteur</th>
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
  var table = $('#moderation-contenus-table').DataTable({
    processing: true,
    serverSide: true,
    dom: 'rt<"bottom"lp><"clear">',
    ajax: '{{ route("moderation.contenus.data") }}',
    columns: [
      { data: 'id' },
      { data: 'titre' },
      { data: 'email', orderable: false, searchable: true },
      { data: 'created_at' },
      { data: 'statut' },
      { data: 'actions', orderable: false, searchable: false }
    ]
  });

  $('#moderation-contenus-search').on('input', function(){ table.search(this.value).draw(); });

  // Actions are handled on the verdict page; DataTable uses server-provided 'actions' HTML
});
</script>
@endpush
