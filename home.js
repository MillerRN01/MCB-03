document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    
    // Adiciona índices para animação dos itens do menu
    document.querySelectorAll('.sidebar-list li').forEach((item, index) => {
        item.style.setProperty('--item-index', index);
    });

    // Toggle sidebar
    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('active');
    });

    // Fecha o sidebar quando clicar fora
    document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });

    // Gerencia o estado do sidebar em diferentes tamanhos de tela
    function handleResize() {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('active');
        }
    }

    // Listener para redimensionamento da janela
    window.addEventListener('resize', handleResize);

    // Adiciona efeito de hover nos links
    const sidebarLinks = document.querySelectorAll('.sidebar-list a');
    sidebarLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Adiciona indicador de página ativa
    const currentPage = window.location.pathname.split('/').pop();
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
            link.style.backgroundColor = 'var(--hover-bg)';
            link.style.color = 'var(--accent-color)';
        }
    });
});