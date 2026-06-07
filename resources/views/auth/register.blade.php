<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - LogisTix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .page-fade-in { animation: fadeInScale 0.3s ease-out forwards; }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-white overflow-hidden">

    <div x-data="{
        isLeaving: false,
        showModal: {{ session('menunggu_acc') ? 'true' : 'false' }},
        isApproved: false,
        userEmail: '{{ session('email_user') }}',
        firstName: '', lastName: '', email: '', phone: '', password: '',
        showPassword: false,
        get isFormValid() {
            return this.firstName.trim() !== '' && this.lastName.trim() !== '' && this.email.trim() !== '' && this.phone.trim() !== '' && this.passwordStrengthScore >= 2;
        },
        get passwordStrengthScore() {
            if (this.password.length === 0) return 0;
            let score = 1;
            if (this.password.length >= 8 && /[0-9]/.test(this.password) && /[a-zA-Z]/.test(this.password)) score = 2;
            if (this.password.length >= 8 && /[0-9]/.test(this.password) && /[A-Z]/.test(this.password) && /[^A-Za-z0-9]/.test(this.password)) score = 3;
            return score;
        },
        init() {
            if (this.showModal && this.userEmail) {
                // Radar mengecek status ACC setiap 3 detik
                let radar = setInterval(() => {
                    fetch('/check-approval/' + this.userEmail)
                        .then(res => res.json())
                        .then(data => {
                            if (data.is_approved == 1 || data.is_approved == true) {
                                clearInterval(radar);
                                this.isApproved = true;

                                setTimeout(() => {
                                    window.location.href = '{{ route('login') }}?status=approved';
                                }, 2500);
                            }
                        });
                }, 3000);
            }
        }
    }" class="flex h-screen w-full relative">

        <div class="hidden lg:block lg:w-[40%] h-full relative bg-slate-200">
            <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=1200&auto=format&fit=crop"
                 alt="Pelabuhan"
                 class="w-full h-full object-cover opacity-80 mix-blend-multiply">
            <div class="absolute inset-0 bg-black/10"></div>

            <div class="absolute top-10 left-10 flex items-center gap-2 z-10">
                <div class="relative w-8 h-8 flex flex-col justify-between">
                    <div class="flex justify-between gap-0.5">
                        <div class="w-3.5 h-3.5 bg-[#1a1c3a] rounded-sm"></div>
                        <div class="w-4 h-3.5 bg-[#f36b2a] rounded-sm"></div>
                    </div>
                    <div class="w-full h-4 bg-[#e05613] rounded-sm"></div>
                </div>
                <span class="text-3xl font-black text-[#1a1c3a] tracking-tight">LogisTix</span>
            </div>
        </div>

        <div class="w-full lg:w-[60%] h-full flex items-center justify-center bg-white px-8" x-show="!showModal">
            <div :class="isLeaving ? 'opacity-0 transform translate-y-2' : 'opacity-100'"
                 class="w-full max-w-[520px] flex flex-col transition-all duration-300 ease-in-out page-fade-in overflow-y-auto max-h-[90vh] pr-2 pb-8">

                <div class="flex w-full border-b border-gray-200 mb-6">
                    <a href="{{ route('login') }}"
                       @click.prevent="isLeaving = true; setTimeout(() => window.location.href = '{{ route('login') }}', 250)"
                       class="w-1/2 text-center pb-3 text-gray-400 hover:text-gray-600 font-bold text-[14px] transition cursor-pointer">Masuk</a>
                    <a href="{{ route('register') }}" class="w-1/2 text-center pb-3 border-b-2 border-red-500 text-red-500 font-bold text-[14px]">Daftar</a>
                </div>

                <div class="flex gap-1.5 items-center mb-8">
                    <div class="w-5 h-2 bg-red-600 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                </div>

                <h2 class="text-3xl font-black text-[#1a1c3a] tracking-tight mb-2">Buat akun staff</h2>
                <p class="text-gray-400 text-sm mb-8">Lengkapi identitas untuk pengajuan akses gudang</p>

                @if ($errors->any())
                    <div class="mb-5 p-3 bg-red-50 text-red-600 rounded-lg text-sm font-semibold border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label class="block text-[14px] font-bold text-gray-600 mb-2">Nama Depan</label>
                            <input x-model="firstName" type="text" name="first_name" required class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition text-sm outline-none">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-[14px] font-bold text-gray-600 mb-2">Nama Belakang</label>
                            <input x-model="lastName" type="text" name="last_name" required class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition text-sm outline-none">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[14px] font-bold text-gray-600 mb-2">Email</label>
                        <input x-model="email" type="email" name="email" required class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition text-sm outline-none">
                    </div>

                    <div class="mb-4">
                        <label class="block text-[14px] font-bold text-gray-600 mb-2">No. Telepon</label>
                        <input x-model="phone" type="text" name="phone" required class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition text-sm outline-none">
                    </div>

                    <div class="mb-8 relative">
                        <label class="block text-[14px] font-bold text-gray-600 mb-2">Password</label>
                        <div class="relative">
                            <input x-model="password" :type="showPassword ? 'text' : 'password'" name="password" required class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition pr-10 text-sm font-mono outline-none">

                            <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>

                        <div class="mt-2.5" x-show="password.length > 0" x-transition>
                            <div class="flex h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-500" :class="passwordStrengthScore == 1 ? 'w-1/3 bg-red-500' : (passwordStrengthScore == 2 ? 'w-2/3 bg-yellow-500' : 'w-full bg-green-500')"></div>
                            </div>
                            <span class="text-[11px] font-bold mt-1.5 block"
                                :class="passwordStrengthScore == 1 ? 'text-red-500' : (passwordStrengthScore == 2 ? 'text-yellow-600' : 'text-green-600')"
                                x-text="passwordStrengthScore == 1 ? 'Kekuatan : lemah' : (passwordStrengthScore == 2 ? 'Kekuatan : sedang' : 'Kekuatan : kuat')">
                            </span>
                        </div>
                    </div>

                    <button type="submit"
                            :disabled="!isFormValid"
                            :class="isFormValid ? 'bg-red-600 text-white hover:bg-red-700 shadow-sm cursor-pointer' : 'text-gray-400 bg-gray-100 cursor-not-allowed'"
                            class="w-full flex justify-center py-3.5 px-4 rounded-lg text-[14px] font-bold transition">
                        Ajukan Sekarang
                    </button>
                </form>
            </div>
        </div>

        <div x-show="showModal" x-cloak class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl p-8 max-w-[420px] w-full text-center shadow-2xl transition-all duration-500 transform"
                 :class="isApproved ? 'border-t-8 border-green-500 scale-105' : 'border-t-8 border-red-500 scale-100'">

                <div x-show="!isApproved" x-transition.opacity>
                    <div class="inline-block relative w-12 h-12 mb-5">
                        <div class="absolute inset-0 w-12 h-12 rounded-full border-4 border-gray-100 border-t-red-500 animate-spin"></div>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 mb-2">Tunggu Sebentar...</h2>
                    <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                        Halo <span class="text-[#1a1c3a] font-bold">{{ session('nama_user') }}</span>! <br>
                        Permintaan akses gudangmu sedang ditinjau.
                    </p>
                    <div class="inline-block bg-yellow-50 border border-yellow-200 text-yellow-700 px-5 py-2.5 rounded-lg text-xs font-bold mb-4 animate-pulse">
                        Status: Menunggu ACC Admin
                    </div>
                    <p class="text-gray-400 text-[11px] mt-2">⚠️ Mohon jangan tutup halaman ini.</p>
                </div>

                <div x-show="isApproved" x-cloak x-transition.opacity>
                    <div class="text-green-500 text-6xl mb-4 transform scale-110">✅</div>
                    <h2 class="text-2xl font-black text-gray-900 mb-2">Akun Di-ACC!</h2>
                    <p class="text-gray-500 text-sm mb-6">
                        Selamat! Super Head Admin telah menyetujui pengajuan akunmu.
                    </p>
                    <div class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 animate-pulse">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Mengalihkan ke halaman login...
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
