<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div id="profil" class="page active">
        <div class="profile-container">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.show') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fa-solid fa-chevron-left text-2xl"></i>
                </a>
                <h1 class="text-3xl font-bold">
                    Edit Profile
                </h1>
            </div>
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                    </div>
                    <div class="profile-info">
                        <h1 class="profile-name">
                            {{ auth()->user()->full_name }}
                            <span class="status-badge status-active">
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </h1>
                        <p class="profile-email">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="profile-section">
                @csrf
                @method('PATCH')
                <h2 class="section-title">Edit Personal Information</h2>
                <div class="profile-grid">
                    <div class="profile-field">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->full_name) }}"
                            class="profile-input">
                    </div>
                    <div class="profile-field">
                        <label>Username</label>
                        <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}"
                            class="profile-input">
                    </div>
                    <div class="profile-field">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="profile-input">
                    </div>
                    <div class="profile-field">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', auth()->user()->phone_number) }}" class="profile-input">
                    </div>
                    <div class="profile-field">
                        <label>Domicile</label>
                        <input type="text" name="domicile" value="{{ old('domicile', auth()->user()->domicile) }}"
                            class="profile-input">
                    </div>
                    <div class="profile-field">
                        <label>Born Date</label>
                        <input type="date" name="born_date" value="{{ old('born_date', auth()->user()->born_date) }}"
                            class="profile-input">
                    </div>
                    <div class="profile-field">
                        <label>Gender</label>
                        <select name="gender" class="profile-input">
                            <option value="">- Pilih -</option>
                            <option value="male" {{ auth()->user()->gender === 'male' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="female" {{ auth()->user()->gender === 'female' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                    </div>
                    <div class="profile-btn">
                        <button type="submit" class="profile-btn btn-primary">
                            Save
                        </button>
                    </div>
            </form>

        </div>
    </div>
</x-app-layout>