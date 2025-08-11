@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-primary">
        <i class="bi bi-person me-2"></i> Profile
    </h1>

    <div class="row">
        <div class="col-lg-8">
            <!-- Update Profile Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-person-gear me-2"></i> Update Profile Information
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-semibold text-dark">
                    <i class="bi bi-shield-lock me-2"></i> Update Password
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-semibold text-danger">
                    <i class="bi bi-trash me-2"></i> Delete Account
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
