(function () {
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.wprt-discovery-form').forEach(function (form) {
            var section = form.closest('section'), grid = section.querySelector('#wprt-active-grid');
            var list = grid.querySelector('.wprt-active-list'), status = section.querySelector('.wprt-discovery-status');
            var nav = section.querySelector('.wprt-discovery-pagination'), page = form.elements.wprt_page;
            var retry = section.querySelector('.wprt-discovery-retry'), chips = form.querySelector('.wprt-active-filters');
            var messages = JSON.parse(form.dataset.i18n), controller, sequence = 0;
            var defaults = {category:'',min_price:'',max_price:'',status:'active',closing:'',availability:'',sort:'newest',page:'1'};
            function chipState() {
                chips.replaceChildren(); var count = 0;
                ['category','min_price','max_price','closing','availability'].forEach(function (key) {
                    var field = form.elements['wprt_' + key]; if (!field.value) { return; } count++;
                    var button = document.createElement('button'); button.type = 'button';
                    var label = field.closest('label').childNodes[0].textContent.trim();
                    var value = field.tagName === 'SELECT' ? field.selectedOptions[0].textContent : field.value;
                    button.textContent = label + ': ' + value + ' ×'; button.setAttribute('aria-label',messages.remove + ' ' + label + ': ' + value);
                    button.addEventListener('click',function () { field.value = ''; page.value = '1'; load(true); }); chips.appendChild(button);
                });
                form.querySelector('.wprt-filter-count').textContent = String(count);
            }
            function readURL() {
                var url = new URL(location.href);
                Object.keys(defaults).forEach(function (key) { var field = form.elements['wprt_' + key]; field.value = url.searchParams.get('wprt_' + key) || defaults[key]; if (field.tagName === 'SELECT' && !field.value) { field.value = defaults[key]; } });
                chipState();
            }
            function writeURL() {
                var url = new URL(location.href);
                Object.keys(defaults).forEach(function (key) { var value = form.elements['wprt_' + key].value; if (!value || value === defaults[key]) { url.searchParams.delete('wprt_' + key); } else { url.searchParams.set('wprt_' + key,value); } });
                if (url.href !== location.href) { history.pushState({},'',url.href); }
            }
            function clear() { Object.keys(defaults).forEach(function (key) { form.elements['wprt_' + key].value = defaults[key]; }); load(true); }
            function load(updateHistory) {
                if (controller) { controller.abort(); } controller = new AbortController(); var current = ++sequence;
                var body = new FormData(); new FormData(form).forEach(function (value,key) { if (key.indexOf('wprt_') === 0) { body.set(key.slice(5),value); } });
                body.set('action','wprt_filter_raffles'); body.set('nonce',form.dataset.nonce); body.set('per_page','9');
                chipState(); retry.hidden = true; grid.setAttribute('aria-busy','true'); status.textContent = messages.loading;
                fetch(form.dataset.endpoint,{method:'POST',credentials:'same-origin',body:body,signal:controller.signal})
                    .then(function (response) { if (!response.ok) { throw new Error('Request failed'); } return response.json(); })
                    .then(function (response) {
                        if (current !== sequence) { return; } if (!response.success) { throw new Error('Request failed'); }
                        list.innerHTML = response.data.html;
                        section.querySelectorAll('.wprt-discovery-empty').forEach(function (el) { el.remove(); });
                        if (!response.data.total) {
                            var empty = document.createElement('div'); empty.className = 'wprt-discovery-empty'; var text = document.createElement('p'); text.textContent = messages.empty;
                            var button = document.createElement('button'); button.type = 'button'; button.className = 'button'; button.textContent = messages.clear; button.addEventListener('click',clear); empty.append(text,button); list.appendChild(empty);
                        }
                        status.textContent = messages.count.replace('%d',response.data.total);
                        page.value = String(response.data.page); nav.querySelector('span').textContent = messages.page.replace('%d',response.data.page).replace('%d',Math.max(1,response.data.pages));
                        nav.querySelector('[data-page-step="-1"]').disabled = response.data.page <= 1; nav.querySelector('[data-page-step="1"]').disabled = response.data.page >= response.data.pages;
                        if (updateHistory) { writeURL(); } document.dispatchEvent(new CustomEvent('wprt:competitions-updated'));
                    }).catch(function (error) { if (error.name !== 'AbortError' && current === sequence) { status.textContent = messages.error; retry.hidden = false; } })
                    .finally(function () { if (current === sequence) { grid.setAttribute('aria-busy','false'); } });
            }
            form.addEventListener('submit',function (event) { event.preventDefault(); page.value = '1'; load(true); });
            form.addEventListener('reset',function (event) { event.preventDefault(); clear(); });
            nav.querySelectorAll('button').forEach(function (button) { button.addEventListener('click',function () { page.value = String(Math.max(1,Number(page.value)+Number(button.dataset.pageStep))); load(true); }); });
            retry.addEventListener('click',function () { load(true); }); window.addEventListener('popstate',function () { readURL(); load(false); }); chipState();
        });
    });
})();
