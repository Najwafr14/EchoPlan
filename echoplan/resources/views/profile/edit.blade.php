<x-app-layout>
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

            <!-- FORM EDIT PROFILE -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-section">
                @csrf
                @method('PATCH')

                <h2 class="section-title">Edit Informasi Pribadi</h2>

                <div class="profile-grid">

                    <div class="profile-field">
                        <label>Nama Lengkap</label>
                        <input type="text" name="full_name"
                               value="{{ old('full_name', auth()->user()->full_name) }}"
                               class="profile-input">
                    </div>

                    <div class="profile-field">
                        <label>Username</label>
                        <input type="text" name="username"
                               value="{{ old('username', auth()->user()->username) }}"
                               class="profile-input">
                    </div>

                    <div class="profile-field">
                        <label>Email</label>
                        <input type="email" name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               class="profile-input">
                    </div>

                    <div class="profile-field">
                        <label>Nomor Telepon</label>
                        <input type="text" name="phone_number"
                               value="{{ old('phone_number', auth()->user()->phone_number) }}"
                               class="profile-input">
                    </div>

                    <div class="profile-field">
                        <label>Domisili</label>
                        <input type="text" name="domicile"
                               value="{{ old('domicile', auth()->user()->domicile) }}"
                               class="profile-input">
                    </div>

                    <div class="profile-field">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="born_date"
                               value="{{ old('born_date', auth()->user()->born_date) }}"
                               class="profile-input">
                    </div>

                    <div class="profile-field">
                        <label>Jenis Kelamin</label>
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

                <!-- SUBMIT -->
                <div class="profile-btn">
                    <button type="submit" class="profile-btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
