@extends('layouts.admin')

@section('title', 'Admin Profile')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Your Profile</h6>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            Profile updated successfully.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Profile Update Form -->
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <!-- Avatar Section -->
                            <div class="col-md-4 mb-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="avatar-wrapper text-center mb-3">
                                    @if ($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Avatar" 
                                             class="rounded-circle img-thumbnail shadow-sm" style="width: 180px; height: 180px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white shadow-sm mx-auto" 
                                             style="width: 180px; height: 180px; font-size: 64px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="custom-file" style="max-width: 250px;">
                                    <input type="file" name="avatar" id="avatar" class="custom-file-input @error('avatar') is-invalid @enderror">
                                    <label class="custom-file-label" for="avatar">Choose new image</label>
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted mt-2">
                                    <i class="fas fa-info-circle"></i> Max size: 2MB. Recommended: Square image.
                                </small>
                            </div>

                            <!-- Profile Details Section -->
                            <div class="col-md-8">
                                <div class="card bg-light border-0 mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title border-bottom pb-2">Account Information</h5>
                                        
                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">
                                                <i class="fas fa-user mr-1"></i> Name
                                            </label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                                class="form-control @error('name') is-invalid @enderror" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email" class="font-weight-bold">
                                                <i class="fas fa-envelope mr-1"></i> Email
                                            </label>
                                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                                class="form-control @error('email') is-invalid @enderror" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-block mt-4">
                                            <i class="fas fa-save mr-1"></i> Update Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Deletion Card -->
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5>Delete Account</h5>
                            <p class="text-muted mb-0">
                                Once deleted, all resources and data will be permanently removed. This action cannot be undone.
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteAccountModal">
                                <i class="fas fa-trash-alt mr-1"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        This action is irreversible. All your data will be permanently removed.
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Please enter your password to confirm</label>
                        <input type="password" name="password" id="password" 
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update custom file input label with selected filename
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0].name;
        const label = e.target.nextElementSibling;
        label.innerText = fileName;
    });
</script>
@endpush

@endsection