(() => {
  'use strict';
  if (!document.getElementById('coma-movie-picker') || !window.COMAMoviePicker) return;
  const query = document.getElementById('coma-movie-query');
  const results = document.getElementById('coma-movie-results');
  const status = document.getElementById('coma-movie-search-status');
  const id = document.getElementById('coma-movie-id');
  const title = document.getElementById('coma-movie-title');
  const selected = document.getElementById('coma-movie-selected');
  let timer;
  let controller;
  let serial = 0;
  function cancel() {
    clearTimeout(timer);
    if (controller) controller.abort();
    serial++;
  }
  document.getElementById('coma-movie-clear').addEventListener('click', () => {
    cancel(); id.value = ''; title.value = ''; query.value = '';
    selected.textContent = 'Ingen film vald.';
    results.replaceChildren(); status.textContent = '';
  });
  query.addEventListener('keydown', event => {
    if (event.key === 'Enter') event.preventDefault();
  });
  query.addEventListener('input', () => {
    cancel();
    const run = serial;
    const term = query.value.trim();
    results.replaceChildren();
    if (term.length < 2) { status.textContent = ''; return; }
    status.textContent = 'Söker filmer…';
    timer = setTimeout(async () => {
      controller = new AbortController();
      try {
        const url = new URL(COMAMoviePicker.ajaxUrl);
        url.searchParams.set('action', 'coma_movie_search');
        url.searchParams.set('nonce', COMAMoviePicker.nonce);
        url.searchParams.set('query', term);
        const response = await fetch(url, {signal: controller.signal, credentials: 'same-origin'});
        const payload = await response.json();
        if (run !== serial) return;
        if (!response.ok || !payload.success) throw new Error(payload.data?.message || 'Filmsökningen misslyckades.');
        const movies = payload.data.movies;
        status.textContent = movies.length ? movies.length + ' filmer hittades. Välj rätt årtal.' : 'Inga filmer hittades.';
        movies.forEach(movie => {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'coma-game-result';
          button.textContent = movie.name + ' (' + (movie.year || 'år okänt') + ') · TMDB #' + movie.id;
          button.addEventListener('click', () => {
            cancel();
            id.value = String(movie.id); title.value = movie.name; query.value = movie.name;
            selected.textContent = 'Vald film: ' + movie.name + ' (TMDB #' + movie.id + ')';
            results.replaceChildren(); status.textContent = '';
          });
          results.append(button);
        });
      } catch (error) {
        if (run === serial && error.name !== 'AbortError') status.textContent = error.message;
      }
    }, 350);
  });
})();
