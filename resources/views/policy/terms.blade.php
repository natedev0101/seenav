<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Általános Szerződési Feltételek
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Általános Szerződési Feltételek</h1>
            <p class="text-gray-400 mb-6">Hatálybalépés dátuma: 2025. február 25.</p>

            <div class="space-y-8">
                <!-- 1. Bevezető rendelkezések -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">1. Bevezető rendelkezések</h2>
                    <div class="space-y-4 text-gray-300">
                        <p>1.1. Jelen Általános Szerződési Feltételek (továbbiakban: ÁSZF) a seenav.hu weboldalon elérhető szolgáltatások igénybevételére vonatkozó feltételeket tartalmazza.</p>
                        
                        <div>
                            <p class="mb-2">1.2. Az üzemeltető adatai:</p>
                            <div class="bg-gray-700/50 rounded-lg p-4 space-y-2">
                                <p>• Üzemeltető neve: SeeMTA NAV</p>
                                <p>• Székhely: Budapest, Hungary</p>
                                <p>• Kapcsolat: natedev@mws.hu</p>
                            </div>
                        </div>

                        <p>1.3. A felhasználó az oldal szolgáltatásainak igénybevételével elfogadja jelen ÁSZF rendelkezéseit.</p>
                    </div>
                </section>

                <!-- 2. Szolgáltatás leírása -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">2. Szolgáltatás leírása</h2>
                    <p class="text-gray-300">
                        A seenav.hu egy adatbázis-kezelő platform, amely lehetővé teszi a felhasználók számára különböző adatok kezelését és rendszerezését.
                    </p>
                </section>

                <!-- 3. Felhasználói fiók, regisztráció -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">3. Felhasználói fiók, regisztráció</h2>
                    <div class="space-y-4 text-gray-300">
                        <p>3.1. A weboldalon történő regisztrációval a felhasználó elfogadja az ÁSZF-et és az Adatvédelmi Nyilatkozatot.</p>
                        <p>3.2. A felhasználó köteles a valós adatait megadni.</p>
                        <p>3.3. Az üzemeltető fenntartja a jogot, hogy indoklás nélkül megtagadja vagy törölje a regisztrációt.</p>
                    </div>
                </section>

                <!-- 4. Jelszó és biztonság -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">4. Jelszó és biztonság</h2>
                    <div class="space-y-4 text-gray-300">
                        <p>4.1. A felhasználó felelős a saját fiókjához tartozó jelszó titokban tartásáért.</p>
                        <p>4.2. Az üzemeltető nem vállal felelősséget a fiókok feltöréséből eredő károkért, kivéve, ha az a weboldal biztonsági hibájából adódott.</p>
                    </div>
                </section>

                <!-- 5. Adatvédelem -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">5. Adatvédelem</h2>
                    <p class="text-gray-300">
                        A felhasználók személyes adatainak kezeléséről az 
                        <a href="{{ route('policy.privacy') }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                            Adatvédelmi Nyilatkozat
                        </a> 
                        rendelkezik.
                    </p>
                </section>

                <!-- 6. Felelősség korlátozása -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">6. Felelősség korlátozása</h2>
                    <div class="space-y-4 text-gray-300">
                        <p>6.1. Az üzemeltető nem vállal felelősséget a weboldalon található információk pontosságáért vagy a szolgáltatások hibamentességéért.</p>
                        <p>6.2. Az oldal használata során esetlegesen bekövetkező károkért a felhasználó kizárólagos felelősséggel tartozik.</p>
                    </div>
                </section>

                <!-- 7. Szerzői jogok -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">7. Szerzői jogok</h2>
                    <div class="space-y-4 text-gray-300">
                        <p>7.1. A weboldalon található minden tartalom (szöveg, kép, videó stb.) az üzemeltető tulajdona vagy megfelelő licenccel rendelkezik.</p>
                        <p>7.2. A weboldal tartalmának másolása vagy újraközlése csak az üzemeltető engedélyével lehetséges.</p>
                    </div>
                </section>

                <!-- 8. Egyéb rendelkezések -->
                <section>
                    <h2 class="text-xl font-semibold text-white mb-4">8. Egyéb rendelkezések</h2>
                    <div class="space-y-4 text-gray-300">
                        <p>8.1. Az üzemeltető fenntartja a jogot az ÁSZF módosítására.</p>
                        <p>8.2. A felhasználó köteles rendszeresen ellenőrizni az ÁSZF esetleges változásait.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
