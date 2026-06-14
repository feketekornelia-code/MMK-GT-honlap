<script>
  (() => {
    const calendar = document.querySelector('.calendar-mosaic');
    const spotlight = document.querySelector('.event-spotlight');
    const track = calendar?.querySelector('.calendar-track');
    const upArrow = calendar?.querySelector('.calendar-scroll-arrow--up');
    const downArrow = calendar?.querySelector('.calendar-scroll-arrow--down');

    if (!calendar || !spotlight || !track || !upArrow || !downArrow) {
      return;
    }

    const backdrop = spotlight.querySelector('.event-spotlight-backdrop');
    const card = spotlight.querySelector('.event-spotlight-card');
    const media = spotlight.querySelector('.event-spotlight-media');
    const titleEl = spotlight.querySelector('.event-spotlight-title');
    const kickerEl = spotlight.querySelector('.event-spotlight-kicker');
    const descriptionEl = spotlight.querySelector('.event-spotlight-description');
    const tiles = Array.from(calendar.querySelectorAll('.event-tile'));
    let hoverTimer = null;
    let activeTile = null;

    const themeLabel = {
      remembrance: 'Megemlékezés',
      training: 'Továbbképzés',
      anniversary: 'Évforduló',
      conference: 'Konferencia'
    };

    let scrollIndex = 0;

    const getStep = () => {
      const firstTile = track.querySelector('.event-tile');
      if (!firstTile) {
        return 0;
      }

      const tileHeight = firstTile.getBoundingClientRect().height;
      const gap = parseFloat(getComputedStyle(track).gap || '0') || 0;
      return tileHeight + gap;
    };

    const maxScrollIndex = () => Math.max(0, track.querySelectorAll('.event-tile').length - 4);

    const renderScrollPosition = () => {
      const step = getStep();
      track.style.transform = `translateY(${-(scrollIndex * step)}px)`;
    };

    const stepScroll = (direction) => {
      const limit = maxScrollIndex();
      const next = scrollIndex + direction;
      scrollIndex = Math.min(limit, Math.max(0, next));
      renderScrollPosition();
    };

    const clearSpotlight = () => {
      if (hoverTimer) {
        window.clearTimeout(hoverTimer);
        hoverTimer = null;
      }

      if (activeTile) {
        activeTile.classList.remove('is-hot');
        activeTile = null;
      }

      spotlight.classList.remove('is-visible');
      spotlight.classList.remove('theme-remembrance', 'theme-training', 'theme-anniversary', 'theme-conference');
      spotlight.setAttribute('aria-hidden', 'true');
      spotlight.hidden = true;
      media.innerHTML = '';
      document.body.classList.remove('event-spotlight-open');
    };

    const showSpotlight = (tile) => {
      const theme = tile.dataset.theme || 'conference';
      const title = tile.dataset.title || tile.querySelector('.event-title')?.textContent || '';
      const category = tile.dataset.category || themeLabel[theme] || '';
      const description = tile.dataset.description || '';
      const previewImage = tile.querySelector('.event-image');
      const imageSrc = previewImage?.getAttribute('src') || '';
      const imageAlt = previewImage?.getAttribute('alt') || title;

      if (activeTile && activeTile !== tile) {
        activeTile.classList.remove('is-hot');
      }

      activeTile = tile;
      tile.classList.add('is-hot');
      media.innerHTML = imageSrc
        ? `<img class="event-image" src="${imageSrc}" alt="${imageAlt}">`
        : '';
      kickerEl.textContent = category;
      titleEl.textContent = title;
      descriptionEl.textContent = description;
      spotlight.classList.remove('theme-remembrance', 'theme-training', 'theme-anniversary', 'theme-conference');
      spotlight.classList.add(`theme-${theme}`);
      spotlight.setAttribute('aria-hidden', 'false');
      spotlight.hidden = false;
      document.body.classList.add('event-spotlight-open');
      requestAnimationFrame(() => spotlight.classList.add('is-visible'));
    };

    tiles.forEach((tile) => {
      tile.addEventListener('pointerenter', () => {
        if (hoverTimer) {
          window.clearTimeout(hoverTimer);
        }

        hoverTimer = window.setTimeout(() => showSpotlight(tile), 180);
      });

      tile.addEventListener('pointerleave', clearSpotlight);
      tile.addEventListener('focusin', () => showSpotlight(tile));
      tile.addEventListener('focusout', clearSpotlight);
      tile.addEventListener('click', () => {
        if (activeTile === tile && spotlight.classList.contains('is-visible')) {
          clearSpotlight();
          return;
        }

        clearSpotlight();
        showSpotlight(tile);
      });

      tile.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter' && event.key !== ' ') {
          return;
        }

        event.preventDefault();
        if (activeTile === tile && spotlight.classList.contains('is-visible')) {
          clearSpotlight();
          return;
        }

        clearSpotlight();
        showSpotlight(tile);
      });
    });

    backdrop.addEventListener('click', clearSpotlight);
    card.addEventListener('click', (event) => event.stopPropagation());
    upArrow.addEventListener('click', () => stepScroll(-1));
    downArrow.addEventListener('click', () => stepScroll(1));
    window.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        clearSpotlight();
      }
    });

    window.addEventListener('resize', renderScrollPosition);
  })();
</script>