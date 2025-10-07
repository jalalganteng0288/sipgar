<footer class="bg-gray-800 text-white pt-12 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Kolom Tentang --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Tentang SIPGAR</h3>
                <p class="text-gray-400 text-sm">
                    Sistem Informasi Perumahan Garut (SIPGAR) adalah platform resmi dari Dinas Perumahan dan Permukiman Kabupaten Garut untuk menyediakan data dan informasi terpusat mengenai perumahan yang tersedia di wilayah Garut.
                </p>
            </div>

            {{-- Kolom Tautan Cepat --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('charts.index') }}" class="text-gray-400 hover:text-white transition-colors">Grafik Perumahan</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Login Pengembang</a></li>
                </ul>
            </div>

            {{-- Kolom Kontak & Sosial Media --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                <p class="text-gray-400 text-sm mb-4">
                    Dinas Perumahan dan Permukiman <br>
                    Kabupaten Garut
                </p>
                <div class="flex space-x-4">
                    {{-- Ganti # dengan link asli --}}
                    <a href="https://www.facebook.com/DisperkimGarutOfficial" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://www.instagram.com/disperkimgarut/" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.012-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.08 2.525c.636-.247 1.363-.416 2.427-.465C9.53 2.013 9.884 2 12.315 2zM12 7a5 5 0 100 10 5 5 0 000-10zm0 8a3 3 0 110-6 3 3 0 010 6zm5.456-9.873a1.144 1.144 0 10-2.288 0 1.144 1.144 0 002.288 0z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://www.youtube.com/c/DisperkimGarutChannel" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">YouTube</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.78 22 12 22 12s0 3.22-.42 4.814c-.23.861-.907 1.538-1.768 1.768C18.219 19 12 19 12 19s-6.219 0-7.812-.42c-.861-.23-1.538-.907-1.768-1.768C2.002 15.22 2 12 2 12s0-3.22.42-4.814c.23-.861.907-1.538 1.768-1.768C5.781 5 12 5 12 5s6.219 0 7.812.418zM9.5 15.584V8.416a.5.5 0 01.753-.432l5.416 3.584a.5.5 0 010 .864l-5.416 3.584a.5.5 0 01-.753-.432z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">WhatsApp</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zM12 20.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31c-.82-1.28-1.26-2.76-1.26-4.3 0-4.54 3.69-8.23 8.23-8.23 4.54 0 8.23 3.69 8.23 8.23S16.54 20.15 12 20.15zM16.49 14.49c-.22-.11-1.28-.63-1.48-.7-.2-.07-.34-.11-.49.11-.15.22-.56.7-.68.85-.13.15-.25.17-.47.06-.22-.11-.93-.34-1.77-1.09-.65-.58-1.1-1.29-1.23-1.51-.12-.22-.01-.34.1-.45.1-.11.22-.28.33-.42.11-.15.15-.25.22-.42.07-.17.04-.31-.02-.42-.06-.11-.49-1.18-.67-1.62-.18-.43-.36-.37-.49-.37-.13 0-.28-.01-.42-.01s-.38.06-.58.31c-.2.25-.76.73-.76 1.79s.78 2.08.89 2.23c.11.15 1.53 2.34 3.71 3.28.52.23.93.37 1.25.47.48.15.91.13 1.26.08.38-.06 1.28-.52 1.46-.98.18-.47.18-.87.13-.98-.05-.11-.19-.18-.41-.29z" /></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Garis Pemisah & Copyright --}}
        <div class="mt-8 border-t border-gray-700 pt-8 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} SIPGAR - Dinas Perumahan dan Permukiman Kabupaten Garut. All rights reserved.
        </div>
    </div>
</footer>