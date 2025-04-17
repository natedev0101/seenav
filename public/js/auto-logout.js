/**
 * Automatikus kijelentkeztetés inaktivitás esetén
 */
class AutoLogout {
    constructor(options = {}) {
        // Alapértelmezett beállítások
        this.options = {
            inactivityTime: 15 * 60 * 1000, // 15 perc (milliszekundumban)
            warningTime: 60 * 1000,     // 1 perc figyelmeztetés a kijelentkeztetés előtt
            logoutUrl: '/kijelentkezes',  // Kijelentkezés URL
            events: ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'], // Figyelt események
            ...options
        };

        // Időzítők
        this.inactivityTimer = null;
        this.warningTimer = null;
        
        // Állapot
        this.isWarningVisible = false;
        
        // Eseménykezelők
        this.resetTimerBound = this.resetTimer.bind(this);
        
        // Inicializálás
        this.init();
    }
    
    /**
     * Inicializálja az automatikus kijelentkeztetést
     */
    init() {
        // Eseménykezelők hozzáadása
        this.addEventListeners();
        
        // Időzítő indítása
        this.resetTimer();
        
        // Figyelmeztető elem létrehozása
        this.createWarningElement();
        
        console.log('Automatikus kijelentkeztetés inicializálva.');
        console.log(`Inaktivitási idő: ${this.options.inactivityTime / 1000} másodperc`);
    }
    
    /**
     * Eseménykezelők hozzáadása
     */
    addEventListeners() {
        this.options.events.forEach(event => {
            document.addEventListener(event, this.resetTimerBound);
        });
    }
    
    /**
     * Időzítő visszaállítása
     */
    resetTimer() {
        // Korábbi időzítők törlése
        clearTimeout(this.inactivityTimer);
        clearTimeout(this.warningTimer);
        
        // Figyelmeztetés elrejtése, ha látható
        if (this.isWarningVisible) {
            this.hideWarning();
        }
        
        // Új időzítők beállítása
        const warningDelay = this.options.inactivityTime - this.options.warningTime;
        
        // Figyelmeztetés időzítő
        this.warningTimer = setTimeout(() => {
            this.showWarning();
        }, warningDelay);
        
        // Kijelentkezés időzítő
        this.inactivityTimer = setTimeout(() => {
            this.logout();
        }, this.options.inactivityTime);
    }
    
    /**
     * Figyelmeztető elem létrehozása
     */
    createWarningElement() {
        // Ellenőrizzük, hogy létezik-e már
        if (document.getElementById('auto-logout-warning')) {
            return;
        }
        
        // Figyelmeztető elem létrehozása
        const warningElement = document.createElement('div');
        warningElement.id = 'auto-logout-warning';
        warningElement.className = 'fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center hidden';
        
        // Figyelmeztető tartalom
        warningElement.innerHTML = `
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md mx-auto">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg font-medium text-white">Automatikus kijelentkeztetés</h3>
                </div>
                <p class="text-gray-300 mb-4">
                    Inaktivitás miatt hamarosan kijelentkeztünk. Kattints bárhova a folytatáshoz.
                </p>
                <div class="text-center">
                    <span id="logout-countdown" class="text-xl font-bold text-yellow-400">60</span>
                    <span class="text-gray-300">másodperc múlva kijelentkezteted.</span>
                </div>
            </div>
        `;
        
        // Hozzáadjuk a dokumentumhoz
        document.body.appendChild(warningElement);
        
        // Kattintás eseménykezelő a figyelmeztetéshez
        warningElement.addEventListener('click', this.resetTimerBound);
    }
    
    /**
     * Figyelmeztetés megjelenítése
     */
    showWarning() {
        const warningElement = document.getElementById('auto-logout-warning');
        if (!warningElement) return;
        
        // Megjelenítés
        warningElement.classList.remove('hidden');
        this.isWarningVisible = true;
        
        // Visszaszámlálás indítása
        this.startCountdown();
    }
    
    /**
     * Figyelmeztetés elrejtése
     */
    hideWarning() {
        const warningElement = document.getElementById('auto-logout-warning');
        if (!warningElement) return;
        
        // Elrejtés
        warningElement.classList.add('hidden');
        this.isWarningVisible = false;
    }
    
    /**
     * Visszaszámlálás indítása
     */
    startCountdown() {
        const countdownElement = document.getElementById('logout-countdown');
        if (!countdownElement) return;
        
        // Kezdeti érték
        let secondsLeft = Math.floor(this.options.warningTime / 1000);
        countdownElement.textContent = secondsLeft;
        
        // Visszaszámlálás
        const countdownInterval = setInterval(() => {
            secondsLeft--;
            
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                return;
            }
            
            countdownElement.textContent = secondsLeft;
        }, 1000);
    }
    
    /**
     * Kijelentkeztetés
     */
    logout() {
        console.log('Automatikus kijelentkeztetés...');
        
        // Először frissítsük az online státuszt AJAX kéréssel
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // AJAX kérés az online státusz frissítéséhez
        fetch('/api/user/set-offline', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Online státusz frissítve');
            
            // Ezután küldjük el a kijelentkezési kérést
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.options.logoutUrl;
            
            // CSRF token hozzáadása
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Űrlap hozzáadása a dokumentumhoz és elküldése
            document.body.appendChild(form);
            form.submit();
        })
        .catch(error => {
            console.error('Hiba az online státusz frissítésekor:', error);
            
            // Hiba esetén is próbáljuk meg a kijelentkezést
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.options.logoutUrl;
            
            // CSRF token hozzáadása
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Űrlap hozzáadása a dokumentumhoz és elküldése
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// Automatikus inicializálás, amikor a dokumentum betöltődött
document.addEventListener('DOMContentLoaded', () => {
    // Csak akkor inicializáljuk, ha a felhasználó be van jelentkezve
    if (document.body.classList.contains('logged-in')) {
        new AutoLogout();
    }
});
