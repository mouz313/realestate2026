@extends('layouts.admin')

@section('title', 'Profile <span class="urdu">(پروفائل)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Profile <span class="urdu">(پروفائل)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3><i class="ti ti-user me-1"></i> Profile Settings <span class="urdu">(پروفائل کی ترتیبات)</span></h3>
        <div class="page-header-sub">Manage your account information and avatar <span class="urdu">(اپنے اکاؤنٹ کی معلومات اور اوتار کا نظم کریں)</span></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-upload mb-3">
                    <div class="avatar-preview mx-auto" id="avatarPreview">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar">
                        @else
                            <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                    </div>
                    <form action="{{ route('profile.index') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <label for="avatarInput" class="btn btn-outline-secondary btn-sm mt-2">
                            <i class="ti ti-camera"></i> Change Photo <span class="urdu">(تصویر تبدیل کریں)</span>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none" onchange="document.getElementById('avatarForm').submit();">
                    </form>
                    @if($user->avatar)
<form action="{{ route('profile.index') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="ti ti-trash me-1"></i> Remove Avatar <span class="urdu">(اوتار ہٹائیں)</span></button>
                        </form>
                    @endif
                </div>
                <h5 class="mb-0">{{ $user->name }}</h5>
                <p class="text-secondary small">{{ $user->email }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-user me-1"></i> Profile Information <span class="urdu">(پروفائل معلومات)</span></h5>
            </div>
            <form action="{{ route('profile.index') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="urdu">(نام)</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="urdu">(ای میل)</span></label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Profile <span class="urdu">(پروفائل اپ ڈیٹ کریں)</span></button>
                </div>
            </form>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-lock me-1"></i> Change Password <span class="urdu">(پاس ورڈ تبدیل کریں)</span></h5>
            </div>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Password <span class="urdu">(موجودہ پاس ورڈ)</span></label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="urdu">(نیا پاس ورڈ)</span></label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password <span class="urdu">(نئے پاس ورڈ کی تصدیق)</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-dark"><i class="ti ti-key"></i> Change Password <span class="urdu">(پاس ورڈ تبدیل کریں)</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection