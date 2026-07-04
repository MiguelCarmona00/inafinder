<?php echo \App\Core\Csrf::scriptTag(); ?>
<script src="/js/csrf.js"></script>
<nav class="bg-darker border-b border-gray w-full fixed top-0 left-0 z-50 shadow-[0_2px_8px_rgba(0,0,0,0.3)]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex-shrink-0">
                <a href="/">
                    <img src="../../imgs/src/logo.webp" alt="Logo" class="h-10 w-auto" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex space-x-4 items-center">
                    <!-- Enlace de Mercado (mantenerlo como está) -->
                    <?php if ($_SESSION['perfil'] !== 'temp'): ?>
                        <a href="/mercado" class="text-light-gray hover:bg-gray hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Mercado
                        </a>
                    <?php endif; ?>

                    <!-- Menú desplegable Admin (solo para administradores) -->
                    <?php if ($_SESSION['perfil'] == 'admin'): ?>
                        <div class="relative">
                            <button type="button" id="admin-menu-button" class="text-light-gray hover:bg-gray hov-er:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors focus:outline-none cursor-pointer">
                                Admin
                                <svg class="ml-1 h-4 w-4 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="admin-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-darkest border border-gray rounded-md shadow-lg z-50">
                                <div class="py-1">
                                    <a href="/jugadoresList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Jugadores
                                    </a>
                                    <a href="/tirosList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Tiros
                                    </a>
                                    <a href="/regatesList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Regates
                                    </a>
                                    <a href="/bloqueosList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Bloqueos
                                    </a>
                                    <a href="/paradasList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Paradas
                                    </a>
                                    <a href="/tecnicasList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Técnicas
                                    </a>
                                    <a href="/talentosList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Talentos
                                    </a>
                                    <a href="/entrenadoresList" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Entrenadores
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Menú desplegable del Usuario (escudo del equipo) -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="relative">
                            <button type="button" id="user-menu-button" class="flex items-center p-1 rounded-full hover:bg-gray transition-colors focus:outline-none cursor-pointer">
                                <img src="<?php echo '../../imgs/uploads/escudos/'.  htmlspecialchars($_SESSION['user']['equipo'][0]['escudo']); ?>" alt="Escudo del equipo" class="w-10 h-10 rounded-full border border-gray">
                            </button>
                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-darkest border border-gray rounded-md shadow-lg z-50">
                                <div class="py-1">
                                    <a href="/" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                        Inicio
                                    </a>
                                    <?php if ($_SESSION['perfil'] !== 'temp'): ?>
                                        <a href="/editTeam" class="block px-4 py-2 text-sm text-light-gray hover:bg-gray hover:text-white transition-colors">
                                            Editar Equipo
                                        </a>
                                    <?php endif; ?>
                                    <a href="/logout" class="block px-4 py-2 text-sm text-error hover:bg-error hover:text-white transition-colors">
                                        Cerrar Sesión
                                    </a>
                                </div>
                            </div>
                        </div>
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
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Inicio
                </a>
                <?php if ($_SESSION['perfil'] !== 'temp'): ?>
                    <a href="/editTeam" class="text-light-gray hover:bg-gray hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                        Editar Equipo
                    </a>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ($_SESSION['perfil'] === 'admin'): ?>
                <div class="border-t border-gray mt-2 pt-2">
                    <div class="text-xs text-gray uppercase tracking-wider px-3 py-2">Administración</div>
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
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user'])): ?>
                <div class="border-t border-gray mt-2 pt-2">
                    <a href="/logout" class="text-error hover:bg-error hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                        Cerrar Sesión
                    </a>
                </div>
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

// Toggle admin dropdown
const adminMenuButton = document.getElementById('admin-menu-button');
const adminDropdown = document.getElementById('admin-dropdown');

if (adminMenuButton && adminDropdown) {
    adminMenuButton.addEventListener('click', function(e) {
        e.preventDefault();
        adminDropdown.classList.toggle('hidden');
        // Cerrar el menú de usuario si está abierto
        const userDropdown = document.getElementById('user-dropdown');
        if (userDropdown && !userDropdown.classList.contains('hidden')) {
            userDropdown.classList.add('hidden');
        }
    });
}

// Toggle user dropdown
const userMenuButton = document.getElementById('user-menu-button');
const userDropdown = document.getElementById('user-dropdown');

if (userMenuButton && userDropdown) {
    userMenuButton.addEventListener('click', function(e) {
        e.preventDefault();
        userDropdown.classList.toggle('hidden');
        // Cerrar el menú de admin si está abierto
        const adminDropdown = document.getElementById('admin-dropdown');
        if (adminDropdown && !adminDropdown.classList.contains('hidden')) {
            adminDropdown.classList.add('hidden');
        }
    });
}

// Cerrar dropdowns al hacer click fuera
document.addEventListener('click', function(e) {
    const adminMenuButton = document.getElementById('admin-menu-button');
    const adminDropdown = document.getElementById('admin-dropdown');
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    
    // Cerrar menú admin si el click es fuera del menú
    if (adminMenuButton && adminDropdown && 
        !adminMenuButton.contains(e.target) && 
        !adminDropdown.contains(e.target)) {
        adminDropdown.classList.add('hidden');
    }
    
    // Cerrar menú usuario si el click es fuera del menú
    if (userMenuButton && userDropdown && 
        !userMenuButton.contains(e.target) && 
        !userDropdown.contains(e.target)) {
        userDropdown.classList.add('hidden');
    }
});
</script>