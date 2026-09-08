@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Pengguna: ' . $user->name)
@section('page_subtitle', 'Perbarui informasi dan hak akses akun pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.users.index') }}">Master User</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 col-md-10 col-12 mx-auto">
        
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-user-edit mr-2 text-warning"></i> Form Perubahan Data Pengguna
                </h3>
            </div>

            <form action="{{ route('master.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show text-sm mb-4" role="alert">
                            <i class="icon fas fa-exclamation-triangle mr-1"></i> <strong>Perhatian!</strong> Terdapat kesalahan pada input Anda. Silakan periksa kembali form di bawah.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="employee_number">Nomor Pegawai / NIK <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" name="employee_number" id="employee_number" class="form-control @error('employee_number') is-invalid @enderror" value="{{ old('employee_number', $user->employee_number) }}" placeholder="Contoh: EMP-001" required>
                            </div>
                            @error('employee_number')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Contoh: Budi Santoso" required>
                            </div>
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                </div>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" placeholder="Contoh: budisantoso" required>
                            </div>
                            @error('username')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="budi@company.com" required>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="role_id">Hak Akses (Role) <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="location_id">Area Kerja (Working Area)</label>
                            <select name="location_id" id="location_id" class="form-control @error('location_id') is-invalid @enderror">
                                <option value="">-- Semua Area / Global (Opsional) --</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id', $user->location_id) == $location->id ? 'selected' : '' }}>
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="phone">Nomor Telepon / WhatsApp</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="081234567890">
                            </div>
                            @error('phone')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="d-block">Status Akun</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-normal" for="is_active">Akun Aktif</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="alert alert-info text-sm">
                        <i class="icon fas fa-info-circle"></i> Kosongkan bagian password di bawah jika Anda tidak ingin mengubah sandi pengguna ini.
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="password">Password Baru (Opsional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="photo">Ganti Foto Profil (Opsional)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="photo" id="photo" class="custom-file-input @error('photo') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                                    <label class="custom-file-label" for="photo">Pilih berkas foto baru...</label>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG. Maksimal ukuran 2MB.</small>
                            @error('photo')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror

                            <div class="mt-3 d-flex align-items-center">
                                <div class="mr-3">
                                    <span class="d-block text-muted text-xs mb-1">Foto Saat Ini:</span>
                                    @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Lama" class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}" alt="Default" class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                </div>
                                <div id="wrapperPreview" style="display: none;">
                                    <span class="d-block text-primary text-xs mb-1">Pratinjau Foto Baru:</span>
                                    <img id="imagePreview" src="#" alt="Pratinjau Foto Baru" class="img-thumbnail rounded border-primary" style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('master.users.index') }}" class="btn btn-default px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning px-4 font-weight-bold text-dark">
                        <i class="fas fa-sync-alt mr-1"></i> Perbarui Data
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        const imagePreview = document.getElementById('imagePreview');
        const wrapperPreview = document.getElementById('wrapperPreview');
        
        reader.onload = function(){
            imagePreview.src = reader.result;
            wrapperPreview.style.display = 'block';
        }
        
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
            // Update label file input dengan nama file yang dipilih
            const fileName = event.target.files[0].name;
            const nextSibling = event.target.nextElementSibling;
            if (nextSibling) {
                nextSibling.innerText = fileName;
            }
        }
    }
</script>
@endpush