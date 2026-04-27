<footer class="bg-navy-900 text-slate-300">
    <div class="mu-container pt-16">
        <div class="grid grid-cols-1 gap-10 pb-12 md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr_1.2fr]">

            <div>
                <h3 class="mb-6 text-lg font-bold text-yellow-brand">MARK-UP</h3>
                <p class="mb-6 text-[15px] leading-relaxed text-slate-200">
                    Platform EdTech terdepan untuk persiapan kompetisi bisnis dan pengembangan karir profesional.
                </p>
                <div class="flex gap-3">
                    @foreach ([
                        ['icon' => 'fa-instagram', 'label' => 'Instagram'],
                        ['icon' => 'fa-linkedin-in', 'label' => 'LinkedIn'],
                        ['icon' => 'fa-twitter', 'label' => 'Twitter'],
                        ['icon' => 'fa-facebook-f', 'label' => 'Facebook'],
                    ] as $social)
                        <a href="#" aria-label="{{ $social['label'] }}"
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-800 text-white transition-colors hover:bg-navy-600">
                            <i class="fab {{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="mb-6 text-lg font-bold text-yellow-brand">Quick Links</h3>
                <ul class="space-y-4 text-[15px]">
                    <li><a href="{{ url('/') }}" class="transition-colors hover:text-white">Beranda</a></li>
                    <li><a href="#" class="transition-colors hover:text-white">Info Lomba</a></li>
                    <li><a href="{{ route('produk') }}" class="transition-colors hover:text-white">Produk</a></li>
                    <li><a href="{{ route('about') }}" class="transition-colors hover:text-white">Tentang Kami</a></li>
                    <li><a href="#" class="transition-colors hover:text-white">Testimonial</a></li>
                </ul>
            </div>

            <div>
                <h3 class="mb-6 text-lg font-bold text-yellow-brand">Program</h3>
                <ul class="space-y-4 text-[15px]">
                    <li><a href="#" class="transition-colors hover:text-white">Business Case Mastery</a></li>
                    <li><a href="#" class="transition-colors hover:text-white">Strategic Consulting</a></li>
                    <li><a href="#" class="transition-colors hover:text-white">Career Mentoring</a></li>
                    <li><a href="#" class="transition-colors hover:text-white">Competition Bootcamp</a></li>
                    <li><a href="#" class="transition-colors hover:text-white">Corporate Training</a></li>
                </ul>
            </div>

            <div>
                <h3 class="mb-6 text-lg font-bold text-yellow-brand">Hubungi Kami</h3>
                <ul class="space-y-4 text-[15px]">
                    <li class="flex items-center gap-4">
                        <i class="far fa-envelope w-5 text-center text-lg text-fuchsia-500"></i>
                        <span>hello@markup.id</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-phone-alt w-5 text-center text-lg text-fuchsia-500"></i>
                        <span>+62 812-3456-7890</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-map-marker-alt w-5 text-center text-lg text-fuchsia-500"></i>
                        <span>Surabaya, Indonesia</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="border-t border-slate-800 py-6">
        <div class="mu-container flex flex-col items-center justify-between gap-4 text-sm text-slate-400 md:flex-row">
            <p>&copy; {{ date('Y') }} MARK-UP. All rights reserved.</p>
            <div class="flex flex-wrap items-center justify-center gap-5">
                <a href="#" class="transition-colors hover:text-white">Privacy Policy</a>
                <a href="#" class="transition-colors hover:text-white">Terms of Service</a>
                <a href="#" class="transition-colors hover:text-white">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
