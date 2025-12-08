@extends('admin.layout')

@section('content')
<div class="card bg-white border-0 rounded-3 mb-4">
    <div class="card-body p-4">
        <form>
            <div class="row">
                {{-- Nom --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label for="nom" class="label text-secondary">Nom</label>
                        <input id="nom" type="text" readonly class="form-control h-55" value="{{ $u->nom }}">
                    </div>
                </div>

                {{-- Prénom --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label for="prenom" class="label text-secondary">Prénom</label>
                        <input id="prenom" type="text" readonly class="form-control h-55" value="{{ $u->prenom }}">
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label for="email" class="label text-secondary">Adresse Email</label>
                        <input id="email" type="text" readonly class="form-control h-55" value="{{ $u->email }}">
                    </div>
                </div>

                {{-- Date de naissance --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label for="date_naissance" class="label text-secondary">Date de naissance</label>
                        <input id="date_naissance" type="text" readonly class="form-control h-55" value="{{ $u->date_naissance }}">
                    </div>
                </div>

                {{-- Région --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Région</label>
                        <input type="text" readonly class="form-control h-55" value="{{ $u->region->nom }}">
                    </div>
                </div>

                {{-- Langue --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Langue</label>
                        <input type="text" readonly class="form-control h-55" value="{{ $u->langue->nomlang }}">
                    </div>
                </div>

                {{-- Rôle --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Rôle</label>
                        <input type="text" readonly class="form-control h-55" value="{{ $u->role->nomrole }}">
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('/admin/users/list') }}" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
