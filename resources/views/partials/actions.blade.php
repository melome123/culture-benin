@php
    $model = $langue ?? $region ?? $contenu ?? $user ?? $commentaire ?? $media ?? $parler ?? $role ?? $typecontenu ?? $typemedia ?? null;
    $id = $model?->id ?? '';
@endphp

@if($id)
<div class="flex gap-2 justify-center">
    <a href="#" class="btn btn-sm btn-primary edit-btn" data-id="{{ $id }}" title="Edit">
        Edit
    </a>
    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $id }}" title="Delete">
        Delete
    </button>
</div>
@endif
