<nav class="bg-darker border-b border-gray w-full fixed top-0 left-0 z-50 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex-shrink-0">
                <a href="/">
                    <img src="../imgs/src/logo.webp" alt="Logo" class="h-10 w-auto" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">

            <!-- Si el perfil es temp, que no muestre el enlace de mercado -->
                    <?php if ($_SESSION['perfil'] !== 'temp'): ?>
                        <a href="/mercado" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Mercado
                        </a>
                        <a href="/editTeam" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Editar Equipo
                        </a>
                    <?php endif; ?>                    
                    <?php if ($_SESSION['perfil'] == 'admin'): ?>
                        <a href="/jugadoresList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Jugadores
                        </a>
                        <a href="/tirosList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Tiros
                        </a>
                        <a href="/regatesList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Regates
                        </a>
                        <a href="/bloqueosList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Bloqueos
                        </a>
                        <a href="/paradasList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Paradas
                        </a>
                        <a href="/talentosList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Talentos
                        </a>
                        <a href="/entrenadoresList" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Entrenadores
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="/logout" class="text-error hover:bg-error hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Cerrar Sesión
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" id="mobile-menu-button" class="bg-darker inline-flex items-center justify-center p-2 rounded-md text-light-gray hover:text-white hover:bg-gray focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition-colors">
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-darkest border-t border-gray">
            <?php if ($_SESSION['perfil'] !== 'temp'): ?>
                <a href="/mercado" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Mercado
                </a>
                <a href="/editTeam" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                    Editar Equipo
                </a>
            <?php endif; ?>
            <?php if ($_SESSION['perfil'] === 'admin'): ?>
                <a href="/jugadoresList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Jugadores
                </a>
                <a href="/tirosList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Tiros
                </a>
                <a href="/regatesList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Regates
                </a>
                <a href="/bloqueosList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Bloqueos
                </a>
                <a href="/paradasList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Paradas
                </a>
                <a href="/talentosList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Talentos
                </a>
                <a href="/entrenadoresList" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Entrenadores
                </a>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/logout" class="text-error hover:bg-error hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Cerrar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
// Toggle mobile menu
document.getElementById('mobile-menu-button').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
});
</script>