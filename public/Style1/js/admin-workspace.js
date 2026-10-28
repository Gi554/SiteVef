/* Shared workspace interactions. Routes, CSRF and server permissions remain authoritative. */
(() => {
    const body = document.body;
    const sidebar = document.querySelector('#admin-sidebar');
    const menuButton = document.querySelector('[data-admin-menu]');
    const backdrop = document.querySelector('[data-admin-backdrop]');
    const mobile = window.matchMedia('(max-width: 900px)');
    let activeModal = null;
    let previousFocus = null;
    const focusable = element => [...element.querySelectorAll('a[href],button:not([disabled]),input:not([type=hidden]):not([disabled]),textarea:not([disabled]),select:not([disabled]),summary,[tabindex="0"]')].filter(item => item.getClientRects().length);
    const closeNavigation = () => {
        body.classList.remove('admin-navigation-open');
        menuButton?.setAttribute('aria-expanded', 'false');
        if (backdrop) backdrop.hidden = true;
        if (sidebar) sidebar.inert = mobile.matches;
    };
    menuButton?.addEventListener('click', () => {
        body.classList.add('admin-navigation-open');
        sidebar.inert = false;
        menuButton.setAttribute('aria-expanded', 'true');
        backdrop.hidden = false;
        focusable(sidebar)[0]?.focus();
    });
    document.querySelector('[data-admin-menu-close]')?.addEventListener('click', () => { closeNavigation(); menuButton.focus(); });
    backdrop?.addEventListener('click', () => { closeNavigation(); menuButton.focus(); });
    mobile.addEventListener('change', closeNavigation);
    closeNavigation();

    const panels = [...document.querySelectorAll('.admin-panel')];
    const title = document.querySelector('[data-admin-current]');
    const titles = { dashboard:'Vue d’ensemble', projets:'Projets', actualites:'Actualités', dons:'Dons et collectes', messages:'Messages', candidatures:'Candidatures', membres:'Notre équipe', temoignages:'Témoignages' };
    function activatePanel() {
        if (!panels.length) return;
        const requested = location.hash.slice(1);
        const id = panels.some(panel => panel.id === requested) ? requested : 'dashboard';
        panels.forEach(panel => { const current = panel.id === id; panel.classList.toggle('active', current); panel.hidden = !current; });
        document.querySelectorAll('.sidebar-nav [data-panel]').forEach(link => {
            const current = link.dataset.panel === id;
            link.classList.toggle('active', current);
            if (current) link.setAttribute('aria-current', 'page'); else link.removeAttribute('aria-current');
        });
        if (title) title.textContent = titles[id];
        document.title = titles[id] + ' — Administration VEF';
    }
    if (panels.length) {
        activatePanel();
        window.addEventListener('hashchange', activatePanel);
        document.querySelectorAll('[data-panel]').forEach(link => link.addEventListener('click', event => {
            event.preventDefault();
            const id = link.dataset.panel;
            if (!panels.some(panel => panel.id === id)) return;
            location.hash = id;
            activatePanel();
            closeNavigation();
            document.querySelector('#' + id + ' h1')?.focus({preventScroll:true});
            window.scrollTo({top:0, behavior:'instant'});
        }));
        panels.forEach(panel => panel.querySelector('h1')?.setAttribute('tabindex', '-1'));
    }

    // Keep wide data tables inside their own scroll region, accessible with the keyboard.
    document.querySelectorAll('.table-scroll,.table-container').forEach(region => {
        region.tabIndex = 0;
        region.setAttribute('role','region');
        region.setAttribute('aria-label','Tableau : défilement horizontal disponible');
    });
    [['messages-search','Rechercher dans les messages'],['candidatures-search','Rechercher dans les candidatures'],['candidatures-filter','Filtrer le type de candidature']].forEach(([id,label]) => document.getElementById(id)?.setAttribute('aria-label',label));
    document.querySelectorAll('.admin-panel h1').forEach(heading => { heading.tabIndex = -1; });

    const announce = (message, error = false) => {
        const region = document.querySelector('#admin-feedback');
        region.textContent = message;
        region.hidden = false;
        region.classList.toggle('admin-feedback--error', error);
    };
    function filterRows(tableId, query, type = '') {
        const rows = [...document.querySelectorAll('#' + tableId + ' tbody tr[data-search]')];
        const normalized = query.trim().toLocaleLowerCase('fr');
        rows.forEach(row => { row.hidden = !row.dataset.search.toLocaleLowerCase('fr').includes(normalized) || (type && row.dataset.type !== type); });
        let empty = document.getElementById(tableId + '-no-results');
        if (!empty && rows.length) {
            empty = document.createElement('tr'); empty.id = tableId + '-no-results';
            const cell = empty.insertCell(); cell.colSpan = document.querySelectorAll('#'+tableId+' thead th').length; cell.className = 'admin-empty-cell'; cell.textContent = 'Aucun résultat. Essayez un autre mot ou modifiez le filtre.';
            rows[0].parentElement.appendChild(empty);
        }
        if(empty) empty.hidden = rows.some(row => !row.hidden);
    }
    document.getElementById('messages-search')?.addEventListener('input', event => filterRows('messages-table',event.target.value));
    const filterApplications = () => filterRows('candidatures-table', document.getElementById('candidatures-search').value, document.getElementById('candidatures-filter').value);
    document.getElementById('candidatures-search')?.addEventListener('input',filterApplications);
    document.getElementById('candidatures-filter')?.addEventListener('change',filterApplications);

    window.toggleRead = async (id, button) => {
        button.disabled = true;
        const row = document.querySelector('#messages-table tr[data-id="'+id+'"]');
        const wasUnread = row.classList.contains('unread');
        try {
            const endpoint = new URL(body.dataset.dashboardUrl);
            endpoint.pathname += '/messages/' + encodeURIComponent(id) + '/toggle-read';
            const response = await fetch(endpoint, {method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}});
            if (!response.ok) throw new Error('HTTP '+response.status);
            const data = await response.json();
            if (typeof data.lu !== 'boolean') throw new Error('Invalid response');
            row.classList.toggle('read',data.lu); row.classList.toggle('unread',!data.lu);
            row.cells[0].textContent = data.lu ? 'Lu' : 'Non lu';
            button.textContent = data.lu ? 'Marquer non lu' : 'Marquer lu';
            button.classList.toggle('read',data.lu);button.classList.toggle('unread',!data.lu);
            const difference = (data.lu ? 0 : 1) - (wasUnread ? 1 : 0);
            document.querySelectorAll('[data-unread-badge],[data-unread-total]').forEach(element => { const count = Math.max(0, Number(element.textContent) + difference); element.textContent = count; if(element.hasAttribute('data-unread-badge')) element.hidden = count === 0; });
            document.querySelectorAll('[data-unread-label]').forEach(element => { element.hidden = Number(element.querySelector('[data-unread-total]').textContent) === 0; });
            document.querySelectorAll('[data-message-status="'+id+'"]').forEach(element => { element.textContent = data.lu ? 'Lu' : 'Non lu'; });
            announce(data.lu ? 'Le message est marqué comme lu.' : 'Le message est marqué comme non lu.');
        } catch {
            announce('Le statut n’a pas pu être modifié. Vérifiez votre connexion et réessayez.', true);
        } finally { button.disabled = false; }
    };

    const closeModal = () => {
        if (!activeModal) return;
        activeModal.style.display = 'none'; activeModal.setAttribute('aria-hidden','true');
        document.querySelectorAll('.admin-container').forEach(el => {el.inert=false;});
        body.style.overflow=''; activeModal=null; previousFocus?.focus();
    };
    const showModal = (element, trigger) => {
        previousFocus=trigger || document.activeElement;
        activeModal=element;
        element.style.display='flex';element.setAttribute('aria-hidden','false');
        body.style.overflow='hidden';
        document.querySelector('.admin-container').inert=true;
        focusable(element)[0]?.focus();
    };
    document.querySelectorAll('#modal-edit-membre,#candidature-modal').forEach(modal => {
        modal.setAttribute('role','dialog');modal.setAttribute('aria-modal','true');modal.setAttribute('aria-hidden','true');
        modal.addEventListener('click',event=>{if(event.target===modal)closeModal();});
        const close = modal.querySelector('button');close.setAttribute('aria-label','Fermer la fenêtre');
        close.onclick=closeModal;
    });
    window.showCandidatureDetail = button => {
        document.getElementById('modal-titre').textContent = button.dataset.nom;
        const content=document.getElementById('modal-body');content.replaceChildren();
        const table=document.createElement('table');table.style.width='100%';
        [['Email','email'],['Téléphone','tel'],['Date de naissance','naissance'],['Adresse','adresse'],['Participation','type'],['Précision','autreType'],['Préférence d’action','preference'],['Disponibilités','dispos'],['Motivation','motivation']].forEach(([label,key])=>{
            const row=table.insertRow();row.insertCell().textContent=label;
            const cell=row.insertCell();cell.textContent=button.dataset[key] || '—';cell.style.whiteSpace='pre-wrap';
        });content.appendChild(table);
        showModal(document.getElementById('candidature-modal'),button);
    };
    window.openEditMembre = button => {
        for(const field of ['prenom','nom','poste','bio','ordre']) document.getElementById('edit-'+field).value=button.dataset[field] || (field==='ordre'?'0':'');
        document.getElementById('edit-photo').value='';
        const endpoint=new URL(body.dataset.dashboardUrl);endpoint.pathname+='/membres/'+encodeURIComponent(button.dataset.id);
        document.getElementById('form-edit-membre').action=endpoint.toString();
        showModal(document.getElementById('modal-edit-membre'),button);
    };
    window.closeEditMembre=closeModal;
    document.addEventListener('keydown',event=>{
        if(event.key==='Escape') { if(activeModal)closeModal(); else if(body.classList.contains('admin-navigation-open')){closeNavigation();menuButton.focus();} return; }
        if(event.key!=='Tab')return;
        const container=activeModal || (body.classList.contains('admin-navigation-open')?sidebar:null);
        if(!container)return;
        const items=focusable(container);if(!items.length)return;
        event.preventDefault();const index=items.indexOf(document.activeElement);const next=index<0?0:(index+(event.shiftKey?-1:1)+items.length)%items.length;items[next].focus();
    });
})();
