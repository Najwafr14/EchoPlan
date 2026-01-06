<x-app-layout>
    <div class="add-user-container">
        <h2 class="add-user-title">Add New User</h2>
        
        <form method="POST" action="{{ route('admin.users.store') }}" class="add-user-form">
            @csrf
            <h3 class="section-title">General Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input name="username" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input name="full_name" class="form-input" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                        <option value="Owner">Owner</option>
                    </select>
                </div>
            </div>

            <div class="form-row section-spacing">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <h3 class="section-title">Personal Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input name="phone_number" type="tel" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Domicile</label>
                    <input name="domicile" class="form-input">
                </div>
                <div class="form-group">
                </div>
            </div>

            <div class="form-row section-spacing">
                <div class="form-group">
                    <label class="form-label">Born Date</label>
                    <input name="born_date" type="date" class="form-input">
                </div>
                <div class="form-group">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-submit">Add User</button>
            </div>
        </form>
    </div>
</x-app-layout>