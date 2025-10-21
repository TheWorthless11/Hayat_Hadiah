<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Welcome, Admin!") }}
                </div>
            </div>

            <!-- Users Panel -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">Registered Users</h3>

                    <div id="adminUsersContainer">
                        <table class="w-full text-left" id="adminUsersTable">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Email</th>
                                    <th class="px-4 py-2">Role</th>
                                    <th class="px-4 py-2">Signed Up</th>
                                </tr>
                            </thead>
                            <tbody id="adminUsersBody">
                                <tr><td class="px-4 py-2" colspan="5">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function fetchAdminUsers() {
            try {
                const res = await fetch('{{ route("admin.users.list") }}', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                const body = document.getElementById('adminUsersBody');
                if (!data.success) {
                    body.innerHTML = '<tr><td class="px-4 py-2" colspan="5">Unable to load users</td></tr>';
                    return;
                }

                if (!data.users || data.users.length === 0) {
                    body.innerHTML = '<tr><td class="px-4 py-2" colspan="5">No users found</td></tr>';
                    return;
                }

                body.innerHTML = data.users.map(u => `
                    <tr>
                        <td class="px-4 py-2">${u.id}</td>
                        <td class="px-4 py-2">${u.name}</td>
                        <td class="px-4 py-2">${u.email}</td>
                        <td class="px-4 py-2">${u.is_admin ? 'Admin' : 'User'}</td>
                        <td class="px-4 py-2">${new Date(u.created_at).toLocaleString()}</td>
                    </tr>
                `).join('');
            } catch (err) {
                console.error('Failed to fetch admin users', err);
            }
        }

        // initial load
        document.addEventListener('DOMContentLoaded', function() {
            fetchAdminUsers();
            // poll every 10 seconds for new users
            setInterval(fetchAdminUsers, 10000);
        });
    </script>
</x-app-layout>