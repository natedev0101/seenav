@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-white mb-6">Adatvédelmi Szabályzat</h1>
        <p class="text-gray-400 mb-6">Hatálybalépés dátuma: 2025. február 25.</p>

        <div class="space-y-8">
            <!-- 1. Bevezetés -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">1. Bevezetés</h2>
                <div class="text-gray-300">
                    <p>A seenav.hu weboldal (továbbiakban: Weboldal) üzemeltetője kiemelten fontosnak tartja a felhasználók személyes adatainak védelmét. Jelen Adatvédelmi Nyilatkozat tájékoztatást nyújt az adatkezelés módjáról és céljáról.</p>
                    
                    <div class="mt-4 bg-gray-700/50 rounded-lg p-4 space-y-2">
                        <p>• Üzemeltető neve: SeeMTA NAV</p>
                        <p>• Székhely: Budapest, Hungary</p>
                        <p>• Kapcsolat: natedev@mws.hu</p>
                    </div>
                </div>
            </section>

            <!-- 2. Milyen adatokat gyűjtünk? -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">2. Milyen adatokat gyűjtünk?</h2>
                <p class="text-gray-300 mb-3">A Weboldalon történő regisztráció vagy kapcsolatfelvétel során az alábbi adatokat gyűjthetjük:</p>
                <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                    <li>Név</li>
                    <li>E-mail cím</li>
                    <li>IP-cím</li>
                    <li>Regisztrációs és bejelentkezési adatok</li>
                    <li>Böngészési előzmények és cookie-k</li>
                </ul>
            </section>

            <!-- 3. Az adatok felhasználása -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">3. Az adatok felhasználása</h2>
                <p class="text-gray-300 mb-3">A gyűjtött adatokat az alábbi célokra használjuk:</p>
                <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                    <li>Fiókkezelés és bejelentkezés biztosítása</li>
                    <li>A Weboldal működésének optimalizálása</li>
                    <li>Kapcsolattartás a felhasználókkal</li>
                    <li>Biztonsági intézkedések és visszaélések megelőzése</li>
                </ul>
            </section>

            <!-- 4. Jelszavak tárolása -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">4. Jelszavak tárolása</h2>
                <p class="text-gray-300">
                    A felhasználók jelszavait titkosított formában (bcrypt hashing algoritmussal) tároljuk, 
                    így azok harmadik fél számára nem visszafejthetőek.
                </p>
            </section>

            <!-- 5. Ki férhet hozzá az adatokhoz? -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">5. Ki férhet hozzá az adatokhoz?</h2>
                <p class="text-gray-300">
                    A személyes adatokhoz csak az üzemeltető és meghatározott adatfeldolgozók férhetnek hozzá. 
                    Az adatok harmadik fél számára nem kerülnek kiadásra, kivéve jogszabályi kötelezettség esetén.
                </p>
            </section>

            <!-- 6. Cookie-k és követési technológiák -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">6. Cookie-k és követési technológiák</h2>
                <p class="text-gray-300">
                    A Weboldal cookie-kat használ a felhasználói élmény javítása érdekében. 
                    A felhasználók a böngészőjük beállításaiban módosíthatják a cookie-k kezelését. 
                    Részletes információért tekintse meg 
                    <a href="{{ route('policy.cookie') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                        Cookie Szabályzatunkat
                    </a>.
                </p>
            </section>

            <!-- 7. Felhasználói jogok -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">7. Felhasználói jogok</h2>
                <p class="text-gray-300 mb-3">A felhasználók jogosultak:</p>
                <ul class="list-disc list-inside text-gray-300 space-y-2 ml-4">
                    <li>Hozzáférést kérni az általunk kezelt személyes adataikhoz</li>
                    <li>Kérni az adatok módosítását vagy törlését</li>
                    <li>Tiltakozni az adatok marketing célú felhasználása ellen</li>
                    <li>Adatokat hordozni és más szolgáltatóhoz továbbítani</li>
                </ul>
                <p class="text-gray-300 mt-4">
                    Az adatok törlésére vonatkozó kérelmeket az alábbi e-mail címen lehet benyújtani: 
                    <a href="mailto:natedev@mws.hu" class="text-blue-400 hover:text-blue-300 transition-colors">
                        natedev@mws.hu
                    </a>
                </p>
            </section>

            <!-- 8. Az adatok tárolási ideje -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">8. Az adatok tárolási ideje</h2>
                <p class="text-gray-300">
                    A személyes adatokat a fiók meglétéig vagy a törlési kérelem beérkezéséig tároljuk.
                </p>
            </section>

            <!-- 9. Jogorvoslati lehetőségek -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">9. Jogorvoslati lehetőségek</h2>
                <p class="text-gray-300">
                    Ha a felhasználók úgy érzik, hogy adataikat jogellenesen kezeljük, panaszt nyújthatnak be a 
                    Nemzeti Adatvédelmi és Információszabadság Hatósághoz (NAIH).
                </p>
            </section>

            <!-- 10. Kapcsolat -->
            <section>
                <h2 class="text-xl font-semibold text-white mb-4">10. Kapcsolat</h2>
                <p class="text-gray-300">
                    Ha bármilyen kérdésed van az adatkezeléssel kapcsolatban, írj nekünk az alábbi e-mail címre: 
                    <a href="mailto:natedev@mws.hu" class="text-blue-400 hover:text-blue-300 transition-colors">
                        natedev@mws.hu
                    </a>
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
