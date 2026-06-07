<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Hi, Keisya! 👋</h1>
                <span class="inline-block mt-2 px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold rounded-full uppercase tracking-widest">
                    SUPER HEAD ADMIN
                </span>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 text-[11px] uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4">Jabatan</th>
                            <th class="px-6 py-4">Akses</th>
                            <th class="px-6 py-4 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="text-[10px] text-gray-400">ID: #{{ $user->id }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-sm {{ $user->is_approved ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->is_approved ? 'Aktif' : 'Blokir' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST" class="inline mr-2">
                                    @csrf
                                    <button class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[10px] font-bold rounded hover:bg-yellow-100 transition">
                                        {{ $user->is_approved ? 'BLOKIR' : 'AKTIF' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1 bg-red-50 text-red-500 text-[10px] font-bold rounded hover:bg-red-100 transition">
                                        HAPUS
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
