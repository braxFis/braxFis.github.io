(() => {
  'use strict';
  const root = document.getElementById('coma-game-picker');
  if (!root || !window.COMAGamePicker) return;
  const query = document.getElementById('coma-game-query');
  const results = document.getElementById('coma-game-results');
  const status = document.getElementById('coma-game-search-status');
  const id = document.getElementById('coma-game-id');
  const source = document.getElementById('coma-game-source');
  const title = document.getElementById('coma-game-title');
  const selected = document.getElementById('coma-game-selected');
  const clear = document.getElementById('coma-game-clear');
  let timer = null;
  let controller = null;
  let serial = 0;

  function select(game) {
    id.value = String(game.id);
    source.value = game.source;
    title.value = game.name;
    selected.textContent = `Valt: ${game.name} (${game.source.toUpperCase()} #${game.id})`;
    query.value = game.name;
    results.replaceChildren();
    status.textContent = '';
  }

  clear.addEventListener('click', () => {
    id.value = ''; source.value = ''; title.value = '';
    selected.textContent = 'Inget spel valt.';
    query.value = '';
    results.replaceChildren(); status.textContent = '';
  });

  query.addEventListener('input', () => {
    clearTimeout(timer);
    if (controller) controller.abort();
    const run = ++serial;
    const term = query.value.trim();
    results.replaceChildren();
    if (term.length < 2) { status.textContent = ''; return; }
    status.textContent = 'Söker...';
    timer = setTimeout(async () => {
      controller = new AbortController();
      try {
        const url = new URL(COMAGamePicker.ajaxUrl);
        url.searchParams.set('action', 'coma_game_search');
        url.searchParams.set('nonce', COMAGamePicker.nonce);
        url.searchParams.set('query', term);
        const response = await fetch(url.toString(), {signal: controller.signal, credentials: 'same-origin'});
        const payload = await response.json();
        if (run !== serial) return;
        if (!response.ok || !payload.success) throw new Error(payload.data?.message || 'Sökningen misslyckades');
        const {games, source: usedSource, fallback} = payload.data;
        status.textContent = `${games.length} träffar via ${usedSource.toUpperCase()}${fallback ? ' (reservkälla)' : ''}.`;
        if (!games.length) { status.textContent += ' Inga spel hittades.'; return; }
        games.forEach(game => {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'coma-game-result';
          if (game.image) {
            const img = document.createElement('img');
            img.src = game.image;
            img.alt = '';
            img.loading = 'lazy';
            button.append(img);
          }
          const label = document.createElement('span');
          label.textContent = `${game.name} (${game.year || 'år okänt'}) · ${game.source.toUpperCase()}`;
          button.append(label);
          button.addEventListener('click', () => select(game));
          results.append(button);
        });
      } catch (error) {
        if (error.name !== 'AbortError' && run === serial) status.textContent = error.message;
      }
    }, 350);
  });
})();
