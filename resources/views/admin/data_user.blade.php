@extends('layouts.dashboard')

@section('title', 'Manajemen User - MARTIP')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <i class='bx bx-chevron-right text-lg'></i>
        <span class="text-gray-900 font-medium">User</span>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">User</h1>
            <p class="text-gray-500 text-sm">Kelola data pengguna yang terdaftar di sistem.</p>
        </div>
        <button onclick="openAddUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class='bx bx-plus'></i> Tambah User
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-6">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            <div class="relative w-full max-w-xs">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" placeholder="Cari nama, email, atau no. HP..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            </div>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none min-w-[150px]">
                <option>Semua Peran</option>
                <option>Super Admin</option>
                <option>Admin</option>
                <option>Petugas Lokasi</option>
                <option>User</option>
            </select>
            <select class="border border-gray-200 rounded-lg px-4 py-2 bg-white text-gray-700 text-sm outline-none min-w-[150px]">
                <option>Semua Status</option>
                <option>Aktif</option>
                <option>Nonaktif</option>
            </select>
            <button class="px-5 py-2 border border-blue-200 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-50 transition flex items-center gap-2">
                <i class='bx bx-filter-alt'></i> Filter
            </button>
        </div>
        <button class="px-5 py-2 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition flex items-center gap-2 border border-gray-200">
            <i class='bx bx-reset'></i> Reset
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">No. HP</th>
                        <th class="px-6 py-4 font-semibold">Peran</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Bergabung</th>
                        <th class="px-6 py-4 font-semibold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-gray-500">{{ $users->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-600">-</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="px-2 py-1 bg-purple-50 text-purple-600 text-xs font-semibold rounded">Admin</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded">User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4"><span class="px-2 py-1 bg-green-50 text-green-600 text-xs font-semibold rounded">Aktif</span></td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" class="w-8 h-8 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 flex items-center justify-center transition" title="Edit"><i class='bx bx-edit-alt'></i></button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 flex items-center justify-center transition" title="Hapus"><i class='bx bx-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data user terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data</span>
            <div class="flex items-center gap-1">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit User -->
<div id="userModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl transform transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900" id="modalUserTitle">Tambah User</h3>
            <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600 transition"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="formUserMethod" value="POST">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="inputUserName" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="inputUserEmail" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Password <span id="passwordHelp" class="text-xs text-gray-400 font-normal hidden">(Kosongkan jika tidak ingin mengubah password)</span></label>
                <input type="password" name="password" id="inputUserPassword" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                <select name="role" id="inputUserRole" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-blue-500 outline-none bg-white">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeUserModal()" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-50 rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium hover:bg-blue-700 rounded-xl transition shadow-lg shadow-blue-200">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddUserModal() {
        document.getElementById('modalUserTitle').innerText = 'Tambah User';
        document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
        document.getElementById('formUserMethod').value = 'POST';
        document.getElementById('inputUserName').value = '';
        document.getElementById('inputUserEmail').value = '';
        document.getElementById('inputUserPassword').value = '';
        document.getElementById('inputUserPassword').required = true;
        document.getElementById('inputUserRole').value = 'user';
        document.getElementById('passwordHelp').classList.add('hidden');
        document.getElementById('userModal').classList.remove('hidden');
    }

    function openEditUserModal(id, name, email, role) {
        document.getElementById('modalUserTitle').innerText = 'Edit User';
        document.getElementById('userForm').action = "/admin/users/" + id;
        document.getElementById('formUserMethod').value = 'PUT';
        document.getElementById('inputUserName').value = name;
        document.getElementById('inputUserEmail').value = email;
        document.getElementById('inputUserRole').value = role;
        document.getElementById('inputUserPassword').value = '';
        document.getElementById('inputUserPassword').required = false;
        document.getElementById('passwordHelp').classList.remove('hidden');
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }
</script>

@endsection
