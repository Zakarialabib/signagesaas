<div>
    <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">User Manager</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="mb-4 flex justify-between items-center">
            <div>
                <input type="text" wire:model.live="search" placeholder="Search users..."
                    class="form-input rounded-md shadow-sm mt-1 block w-full">
            </div>
            <div>
                <select wire:model.live="tenantId" class="form-select rounded-md shadow-sm mt-1 block w-full">
                    <option value="">All Tenants</option>
                    @foreach ($tenants as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="createUser"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Add New User
            </button>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('name')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Name
                            @if ($sortField === 'name')
                                <span class="ml-1">{!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('email')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Email
                            @if ($sortField === 'email')
                                <span class="ml-1">{!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('tenant_id')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Tenant
                            @if ($sortField === 'tenant_id')
                                <span class="ml-1">{!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('role')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Role
                            @if ($sortField === 'role')
                                <span class="ml-1">{!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->tenant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editUser({{ $user->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="deleteUser({{ $user->id }})"
                                    class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">No users
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

        <!-- User Create/Edit Modal -->
        @if ($showUserModal)
            <div
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">{{ $editingUser ? 'Edit User' : 'Create User' }}</h3>
                    <form wire:submit.prevent="saveUser">
                        <div class="mb-4">
                            <label for="userName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="userName" wire:model="userName"
                                class="form-input rounded-md shadow-sm mt-1 block w-full">
                            @error('userName')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="userEmail" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="userEmail" wire:model="userEmail"
                                class="form-input rounded-md shadow-sm mt-1 block w-full">
                            @error('userEmail')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="userRole" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="userRole" wire:model="userRole"
                                class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            @error('userRole')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="userPassword" class="block text-sm font-medium text-gray-700">Password (leave
                                blank to keep current)</label>
                            <input type="password" id="userPassword" wire:model="userPassword"
                                class="form-input rounded-md shadow-sm mt-1 block w-full">
                            @error('userPassword')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="button" wire:click="$set('showUserModal', false)"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
