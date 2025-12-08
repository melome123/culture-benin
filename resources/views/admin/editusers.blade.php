@extends('admin.layout')

@section('content')
<div class="card bg-white border-0 rounded-3 mb-4">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.update', $u->id) }}">
            @csrf
            @method('PUT')

            {{-- Affichage global des erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                {{-- Nom --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Nom</label>
                        <input type="text" name="nom" id="nom"
                               class="form-control h-55 @error('nom') is-invalid @enderror"
                               placeholder="Entrez le nom" value="{{ old('nom', $u->nom) }}">
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Prénom --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Prénom</label>
                        <input type="text" name="prenom" id="prenom"
                               class="form-control h-55 @error('prenom') is-invalid @enderror"
                               placeholder="Entrez le prénom" value="{{ old('prenom', $u->prenom) }}">
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Adresse Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control h-55 @error('email') is-invalid @enderror"
                               placeholder="Entrez l'adresse email" value="{{ old('email', $u->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Mot de passe --}}
<div class="col-lg-6 col-sm-6">
    <div class="form-group mb-4">
        <label class="label text-secondary">Mot de passe</label>
        <input type="password" name="password" id="password"
               class="form-control h-55 @error('password') is-invalid @enderror"
               placeholder="Entrez le mot de passe">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>


                {{-- Date de naissance --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Date de naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance"
                               class="form-control h-55 @error('date_naissance') is-invalid @enderror"
                               value="{{ old('date_naissance', $u->date_naissance) }}">
                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Région --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Région</label>
                        <select name="region_id"
                                class="form-select form-control h-55 @error('region_id') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach($regions as $id => $nom)
                                <option value="{{ $id }}" {{ old('region_id', $u->region_id) == $id ? 'selected' : '' }}>
                                    {{ $nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('region_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Langue --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Langue</label>
                        <select name="langue_id"
                                class="form-select form-control h-55 @error('langue_id') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach($langues as $id => $nom)
                                <option value="{{ $id }}" {{ old('langue_id', $u->langue_id) == $id ? 'selected' : '' }}>
                                    {{ $nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('langue_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Rôle --}}
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group mb-4">
                        <label class="label text-secondary">Rôle</label>
                        <select name="role_id"
                                class="form-select form-control h-55 @error('role_id') is-invalid @enderror">
                            <option value="">-- Choisir --</option>
                            @foreach($roles as $id => $nom)
                                <option value="{{ $id }}" {{ old('role_id', $u->role_id) == $id ? 'selected' : '' }}>
                                    {{ $nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ URL('/admin/users/list') }}">
                            <button class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white" type="button">
                                Cancel
                            </button>
                        </a>
                        <button class="btn btn-primary py-2 px-4 fw-medium fs-16" type="submit">
                            <i class="ri-add-line text-white fw-medium"></i> Update User
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
