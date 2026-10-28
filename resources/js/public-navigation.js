// Native dialog supplies focus containment, Escape and restoration to the opening button.
const menu = document.querySelector('#navigation-mobile');
const trigger = document.querySelector('[data-menu-open]');

if (menu && trigger) {
    const closeMenu = () => menu.close();
    trigger.addEventListener('click', () => {
        menu.showModal();
        trigger.setAttribute('aria-expanded', 'true');
        document.documentElement.classList.add('menu-is-open');
    });
    menu.querySelector('[data-menu-close]').addEventListener('click', closeMenu);
    menu.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
    menu.addEventListener('close', () => {
        trigger.setAttribute('aria-expanded', 'false');
        document.documentElement.classList.remove('menu-is-open');
    });
    // Keep Tab inside the menu, including browsers that otherwise move focus to their toolbar.
    menu.addEventListener('keydown', event => {
        if (event.key !== 'Tab') return;
        const items = [...menu.querySelectorAll('a[href], button:not([disabled]), summary')]
            .filter(item => item.getClientRects().length > 0);
        if (!items.length) return;
        event.preventDefault();
        const current = items.indexOf(document.activeElement);
        const next = current < 0 ? 0 : (current + (event.shiftKey ? -1 : 1) + items.length) % items.length;
        items[next].focus();
    });
    menu.addEventListener('click', event => {
        if (event.target === menu) {
            const bounds = menu.getBoundingClientRect();
            if (event.clientX < bounds.left || event.clientX > bounds.right) closeMenu();
        }
    });
    window.matchMedia('(min-width: 1100px)').addEventListener('change', event => {
        if (event.matches && menu.open) closeMenu();
    });
}

const dropdowns = [...document.querySelectorAll('.desktop-nav details')];
dropdowns.forEach(current => current.addEventListener('toggle', () => {
    if (current.open) dropdowns.filter(item => item !== current).forEach(item => { item.open = false; });
}));
document.addEventListener('click', event => {
    dropdowns.filter(item => !item.contains(event.target)).forEach(item => { item.open = false; });
});
document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    const opened = dropdowns.find(item => item.open);
    if (opened) {
        opened.open = false;
        opened.querySelector('summary').focus();
    }
});
