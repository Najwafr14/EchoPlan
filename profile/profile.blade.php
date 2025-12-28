<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div id="profil" class="page active">
        <div class="profile-container">

            <!-- PROFILE CARD -->
            <div class="profile-card">
                <div class="profile-header">

                    <!-- AVATAR -->
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                    </div>

                    <!-- BASIC INFO -->
                    <div class="profile-info">
                        <h1 class="profile-name">
                            {{ auth()->user()->full_name }}

                            <span class="status-badge 
                            {{ auth()->user()->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </h1>

                        <p class="profile-email">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- INFORMASI PRIBADI -->
            <div class="profile-section">
                <h2 class="section-title">Informasi Pribadi</h2>

                <div class="profile-grid">
                    <div class="profile-field">
                        <label>Nama Lengkap</label>
                        <p>{{ auth()->user()->full_name }}</p>
                    </div>

                    <div class="profile-field">
                        <label>Username</label>
                        <p>{{ auth()->user()->username }}</p>
                    </div>

                    <div class="profile-field">
                        <label>Email</label>
                        <p>{{ auth()->user()->email }}</p>
                    </div>

                    <div class="profile-field">
                        <label>Nomor Telepon</label>
                        <p>{{ auth()->user()->phone_number ?? '-' }}</p>
                    </div>

                    <div class="profile-field">
                        <label>Domisili</label>
                        <p>{{ auth()->user()->domicile ?? '-' }}</p>
                    </div>

                    <div class="profile-field">
                        <label>Tanggal Lahir</label>
                        <p>
                            {{ auth()->user()->born_date
    ? \Carbon\Carbon::parse(auth()->user()->born_date)->translatedFormat('d F Y')
    : '-' 
                        }}
                        </p>
                    </div>

                    <div class="profile-field">
                        <label>Jenis Kelamin</label>
                        <p>{{ ucfirst(auth()->user()->gender ?? '-') }}</p>
                    </div>

                    <div class="profile-field">
                        <label>Role</label>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                <!-- ACTION BUTTON -->
                <div class="profile-actions">
                    <a href="{{ route('profile.edit') }}" class="profile-btn btn-primary">
                        Edit Profil
                    </a>
                    <a class="profile-btn btn-danger">@include('profile.partials.delete-user-form')</a>
                </div>
            </div>

        </div>
    </div>




</x-app-layout>