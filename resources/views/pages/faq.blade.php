@extends('layouts.app', [
    'seoTitle' => 'คำถามที่พบบ่อย (FAQ) — PGMF Shop',
    'seoDescription' => 'คำถามที่พบบ่อยเกี่ยวกับการสั่งซื้อ การชำระเงิน การจัดส่ง และนโยบายต่างๆ ของ PGMF Shop',
])

@section('content')
    <div class="bg-white min-h-screen">

        {{-- Hero --}}
        <div class="max-w-7xl mx-auto px-4 pt-16 pb-12 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">คำถามที่พบบ่อย</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">เรารวบรวมคำตอบสำหรับคำถามที่ลูกค้าสอบถามบ่อยที่สุด เพื่อความรวดเร็วในการรับข้อมูลเบื้องต้น</p>
        </div>

        @php
            $faqs = [
                ['id'=>'ordering','category'=>'การสั่งซื้อ','color'=>'orange','icon'=>'shopping-bag','items'=>[
                    ['q'=>'สั่งซื้อสินค้าอย่างไร?','a'=>'เลือกสินค้าที่ต้องการ กดเพิ่มลงตะกร้า จากนั้นไปที่หน้าตะกร้าสินค้า กรอกข้อมูลที่อยู่จัดส่ง แล้วยืนยันคำสั่งซื้อ สามารถดูรายละเอียดเพิ่มเติมได้ที่หน้า "วิธีการสั่งซื้อ"'],
                    ['q'=>'สามารถยกเลิกคำสั่งซื้อได้หรือไม่?','a'=>'สามารถยกเลิกได้ก่อนที่ร้านค้าจะยืนยันการชำระเงิน โดยไปที่หน้า "คำสั่งซื้อของฉัน" แล้วกดปุ่มยกเลิก หากร้านค้ายืนยันการชำระเงินแล้ว กรุณาติดต่อเจ้าหน้าที่'],
                    ['q'=>'ต้องการแก้ไขที่อยู่จัดส่งหลังสั่งซื้อแล้ว ทำอย่างไร?','a'=>'กรุณาติดต่อเจ้าหน้าที่ผ่านทาง Line หรืออีเมลโดยเร็วที่สุด ก่อนที่สินค้าจะถูกจัดส่ง'],
                ]],
                ['id'=>'payment','category'=>'การชำระเงิน','color'=>'blue','icon'=>'credit-card','items'=>[
                    ['q'=>'รับชำระเงินช่องทางใดบ้าง?','a'=>'ปัจจุบันรับชำระผ่าน QR PromptPay และโอนเงินผ่านธนาคาร หลังชำระเงินแล้วกรุณาแนบหลักฐานการโอนเงิน (สลิป) ในหน้าคำสั่งซื้อ'],
                    ['q'=>'ชำระเงินแล้วแต่ยังไม่ได้รับการยืนยัน?','a'=>'กรุณาตรวจสอบว่าได้แนบสลิปการโอนเงินเรียบร้อยแล้ว ทางร้านจะตรวจสอบและยืนยันภายใน 1-2 ชั่วโมงในเวลาทำการ หากเกินกว่านี้กรุณาติดต่อเจ้าหน้าที่'],
                    ['q'=>'สามารถขอใบเสร็จรับเงินได้หรือไม่?','a'=>'ได้ครับ ระบบจะออกใบเสร็จอัตโนมัติหลังจากยืนยันการชำระเงินเรียบร้อย สามารถดูได้จากหน้ารายละเอียดคำสั่งซื้อ'],
                ]],
                ['id'=>'shipping','category'=>'การจัดส่ง','color'=>'emerald','icon'=>'truck','items'=>[
                    ['q'=>'จัดส่งสินค้าภายในกี่วัน?','a'=>'หลังจากยืนยันการชำระเงินเรียบร้อย ทางร้านจะจัดส่งสินค้าภายใน 1-2 วันทำการ และสินค้าจะถึงมือลูกค้าภายใน 1-3 วันทำการ ขึ้นอยู่กับพื้นที่จัดส่ง'],
                    ['q'=>'ใช้บริการขนส่งอะไร?','a'=>'เราใช้บริการไปรษณีย์ไทยในการจัดส่งสินค้าทั่วประเทศ'],
                    ['q'=>'ติดตามสถานะการจัดส่งได้อย่างไร?','a'=>'หลังจากจัดส่งสินค้า ทางร้านจะแจ้งหมายเลขพัสดุผ่านอีเมลและในหน้ารายละเอียดคำสั่งซื้อ สามารถนำหมายเลขไปตรวจสอบสถานะได้ที่เว็บไซต์ไปรษณีย์ไทย'],
                ]],
                ['id'=>'returns','category'=>'การคืนสินค้า','color'=>'purple','icon'=>'arrow-uturn-left','items'=>[
                    ['q'=>'สามารถคืนสินค้าได้หรือไม่?','a'=>'สามารถคืนสินค้าได้ภายใน 7 วันหลังได้รับสินค้า ในกรณีสินค้าชำรุด เสียหาย หรือไม่ตรงกับที่สั่งซื้อ กรุณาถ่ายรูปสินค้าและติดต่อเจ้าหน้าที่'],
                    ['q'=>'ขั้นตอนการคืนสินค้าเป็นอย่างไร?','a'=>'ติดต่อเจ้าหน้าที่แจ้งปัญหาพร้อมแนบรูปภาพ → รอการตรวจสอบและอนุมัติ → จัดส่งสินค้าคืน → รับเงินคืนภายใน 3-5 วันทำการ'],
                ]],
                ['id'=>'account','category'=>'บัญชีผู้ใช้','color'=>'slate','icon'=>'user','items'=>[
                    ['q'=>'สมัครสมาชิกอย่างไร?','a'=>'กดปุ่ม "สมัครสมาชิก" ที่มุมขวาบน กรอกข้อมูลให้ครบถ้วน หรือสมัครผ่าน Google / Facebook ได้ทันที'],
                    ['q'=>'ลืมรหัสผ่าน ทำอย่างไร?','a'=>'กดปุ่ม "ลืมรหัสผ่าน" ที่หน้าเข้าสู่ระบบ กรอกอีเมลที่ใช้สมัครสมาชิก ระบบจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ไปยังอีเมลของคุณ'],
                ]],
                ['id'=>'products','category'=>'สินค้า','color'=>'amber','icon'=>'tag','items'=>[
                    ['q'=>'ผ้าแบบนี้มีรามั้ยคะ หรือผ้าเสียแล้ว?','a'=>'ไม่ใช่รานะคะ และผ้าไม่ได้เสียค่ะ ผ้าดิบเป็นผ้าที่ทำจากเส้นใยฝ้ายธรรมชาติ ไม่ผ่านการฟอกและไม่ย้อมสี จึงยังคงสีและผิวสัมผัสตามธรรมชาติของผ้าไว้ เป็นผ้าแนวออแกนิค เป็นมิตรกับสิ่งแวดล้อม ใส่สบาย ระบายอากาศดี ได้ฟีลธรรมชาติ'],
                ]],
            ];
            $iconColors = [
                'orange'  => ['bg'=>'bg-orange-100',  'text'=>'text-orange-500'],
                'blue'    => ['bg'=>'bg-blue-100',    'text'=>'text-blue-600'],
                'emerald' => ['bg'=>'bg-emerald-100', 'text'=>'text-emerald-600'],
                'purple'  => ['bg'=>'bg-purple-100',  'text'=>'text-purple-600'],
                'slate'   => ['bg'=>'bg-slate-100',   'text'=>'text-slate-600'],
                'amber'   => ['bg'=>'bg-amber-100',   'text'=>'text-amber-600'],
            ];
        @endphp

        <div class="max-w-7xl mx-auto px-4 pb-24">
            <div class="flex flex-col lg:flex-row gap-12" x-data="{ open: null }">

                {{-- Sticky Sidebar --}}
                <aside class="lg:w-1/4">
                    <div class="sticky top-24">
                        <nav class="flex flex-col space-y-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-2" id="faq-nav">
                            @foreach($faqs as $i => $section)
                                <a href="#{{ $section['id'] }}"
                                   class="faq-nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ $i === 0 ? 'bg-white text-orange-500 shadow-sm border-l-4 border-orange-500 font-semibold' : 'text-slate-500 hover:bg-slate-100' }}">
                                    <span class="shrink-0">
                                        @if($section['icon']==='shopping-bag')<x-heroicon-o-shopping-bag class="h-5 w-5"/>
                                        @elseif($section['icon']==='credit-card')<x-heroicon-o-credit-card class="h-5 w-5"/>
                                        @elseif($section['icon']==='truck')<x-heroicon-o-truck class="h-5 w-5"/>
                                        @elseif($section['icon']==='arrow-uturn-left')<x-heroicon-o-arrow-uturn-left class="h-5 w-5"/>
                                        @elseif($section['icon']==='user')<x-heroicon-o-user class="h-5 w-5"/>
                                        @elseif($section['icon']==='tag')<x-heroicon-o-tag class="h-5 w-5"/>
                                        @endif
                                    </span>
                                    <span>{{ $section['category'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                {{-- FAQ Sections --}}
                <div class="lg:w-3/4 space-y-16">
                    @foreach($faqs as $sectionIndex => $section)
                        @php $c = $iconColors[$section['color']]; @endphp
                        <section class="scroll-mt-28" id="{{ $section['id'] }}">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center shrink-0">
                                    @if($section['icon']==='shopping-bag')<x-heroicon-o-shopping-bag class="h-5 w-5"/>
                                    @elseif($section['icon']==='credit-card')<x-heroicon-o-credit-card class="h-5 w-5"/>
                                    @elseif($section['icon']==='truck')<x-heroicon-o-truck class="h-5 w-5"/>
                                    @elseif($section['icon']==='arrow-uturn-left')<x-heroicon-o-arrow-uturn-left class="h-5 w-5"/>
                                    @elseif($section['icon']==='user')<x-heroicon-o-user class="h-5 w-5"/>
                                    @elseif($section['icon']==='tag')<x-heroicon-o-tag class="h-5 w-5"/>
                                    @endif
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900 uppercase tracking-wider">{{ $section['category'] }}</h2>
                            </div>
                            <div class="space-y-4">
                                @foreach($section['items'] as $itemIndex => $item)
                                    @php $key = $sectionIndex.'-'.$itemIndex; @endphp
                                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                                        <button @click="open = open === '{{ $key }}' ? null : '{{ $key }}'"
                                                class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 transition-colors">
                                            <span class="text-base font-medium text-slate-700 pr-4">{{ $item['q'] }}</span>
                                            <x-heroicon-o-chevron-down class="h-5 w-5 text-slate-400 shrink-0 transition-transform duration-300"
                                                x-bind:class="{ 'rotate-180': open === '{{ $key }}' }" />
                                        </button>
                                        <div x-show="open === '{{ $key }}'" x-collapse>
                                            <div class="px-6 pb-6 text-slate-500 leading-relaxed">{{ $item['a'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach

                    {{-- CTA --}}
                    <section>
                        <div class="relative overflow-hidden bg-linear-to-br from-slate-900 to-slate-800 rounded-3xl p-12 text-center text-white shadow-2xl">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500 opacity-10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>
                            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500 opacity-10 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl pointer-events-none"></div>
                            <div class="relative z-10">
                                <h3 class="text-3xl font-bold mb-4">ยังมีคำถามเพิ่มเติม?</h3>
                                <p class="text-slate-400 max-w-lg mx-auto mb-10 text-lg">หากคุณไม่พบคำตอบที่ต้องการ สามารถติดต่อเจ้าหน้าที่ฝ่ายบริการลูกค้าของเราได้โดยตรง</p>
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                    <a href="mailto:office@progressivemovement.in.th"
                                       class="group inline-flex items-center gap-3 bg-white text-slate-900 px-8 py-4 rounded-2xl font-bold hover:bg-orange-500 hover:text-white transition-all duration-300">
                                        <x-heroicon-o-envelope class="h-5 w-5 text-orange-500 group-hover:text-white transition-colors" />
                                        ส่งอีเมลหาเรา
                                    </a>
                                    <a href="{{ route('how-to-order') }}"
                                       class="inline-flex items-center gap-3 bg-slate-700/50 border border-slate-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-slate-700 transition-all duration-300">
                                        <x-heroicon-o-document-text class="h-5 w-5" />
                                        ดูวิธีการสั่งซื้อ
                                    </a>
                                </div>
                                <div class="mt-8 flex items-center justify-center gap-2 text-slate-400 text-sm">
                                    <x-heroicon-o-clock class="h-4 w-4" />
                                    <span>จันทร์ - ศุกร์ | 09.00 - 19.00 น.</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const navLinks = document.querySelectorAll('.faq-nav-link');
                const sections = document.querySelectorAll('section[id]');

                navLinks.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) target.scrollIntoView({ behavior: 'smooth' });
                        setActive(this);
                    });
                });

                function setActive(el) {
                    navLinks.forEach(a => {
                        a.classList.remove('bg-white','text-orange-500','shadow-sm','border-l-4','border-orange-500','font-semibold');
                        a.classList.add('text-slate-500');
                    });
                    el.classList.remove('text-slate-500');
                    el.classList.add('bg-white','text-orange-500','shadow-sm','border-l-4','border-orange-500','font-semibold');
                }

                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const id = entry.target.getAttribute('id');
                            const link = document.querySelector('.faq-nav-link[href="#' + id + '"]');
                            if (link) setActive(link);
                        }
                    });
                }, { rootMargin: '-30% 0px -60% 0px' });

                sections.forEach(s => observer.observe(s));
            });
        </script>
        @endpush
    </div>
@endsection
