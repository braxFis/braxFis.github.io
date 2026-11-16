(() => {
  'use strict';
  const role = document.getElementById('gdes-reporter-role');
  const panel = document.getElementById('gdes-tmdb-picker');
  if (!role || !panel || !window.GDESTmdb) return;
  const query = document.getElementById('gdes-tmdb-query');
  const search = document.getElementById('gdes-tmdb-search');
  const status = document.getElementById('gdes-tmdb-status');
  const results = document.getElementById('gdes-tmdb-results');
  const selected = document.getElementById('gdes-tmdb-selected');
  const id = document.getElementById('gdes-tmdb-id');
  const language = panel.closest('form').querySelector('[name="language"]');
  let request;
  let serial = 0;
  function cancel() { serial++; if (request) request.abort(); search.disabled = false; }
  function clear() {
    cancel(); id.value = ''; selected.textContent = 'Ingen film vald.';
    results.replaceChildren(); status.textContent = '';
  }
  role.addEventListener('change', () => { panel.hidden = role.value !== 'movie'; clear(); });
  query.addEventListener('input', clear);
  language.addEventListener('change', clear);
  document.getElementById('gdes-tmdb-clear').addEventListener('click', clear);
  async function lookup() {
    cancel();
    const term = query.value.trim();
    if (term.length < 2) { status.textContent = 'Skriv minst två tecken.'; return; }
    request = new AbortController();
    const run = serial;
    search.disabled = true; results.replaceChildren(); status.textContent = 'Söker filmer…';
    try {
      const url = new URL(GDESTmdb.ajaxUrl);
      url.searchParams.set('action', 'gdes_tmdb_search');
      url.searchParams.set('nonce', GDESTmdb.nonce);
      url.searchParams.set('query', term);
      url.searchParams.set('language', language.value);
      const response = await fetch(url, { credentials: 'same-origin', signal: request.signal });
      const payload = await response.json();
      if (run !== serial) return;
      if (!response.ok || !payload.success) throw new Error(payload.data?.message || 'Filmsökningen misslyckades.');
      status.textContent = payload.data.movies.length ? 'Välj rätt film och årtal.' : 'Inga filmer hittades.';
      payload.data.movies.forEach(movie => {
        const button = document.createElement('button');
        button.type = 'button'; button.className = 'button';
        button.textContent = movie.title + ' (' + (movie.year || 'år okänt') + ') · TMDB #' + movie.id;
        button.addEventListener('click', () => {
          id.value = String(movie.id); selected.textContent = 'Vald film: ' + button.textContent;
          const title = panel.closest('form').querySelector('[name="title"]');
          if (!title.value.trim()) title.value = movie.title;
          status.textContent = movie.overview || 'Synopsis saknas. Lägg till eget faktaunderlag.';
          results.replaceChildren();
        });
        const row = document.createElement('p'); row.append(button); results.append(row);
      });
    } catch (error) {
      if (run === serial && error.name !== 'AbortError') status.textContent = error.message;
    } finally { if (run === serial) search.disabled = false; }
  }
  search.addEventListener('click', lookup);
  query.addEventListener('keydown', event => { if (event.key === 'Enter') { event.preventDefault(); lookup(); } });
})();
