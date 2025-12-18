(() => {
  const root = document.getElementById("latest-books");
  if (!root || !window.FOOZ_LIBRARY) return;

  const { ajaxUrl, action, nonce, currentId } = window.FOOZ_LIBRARY;

  const url = new URL(ajaxUrl, window.location.origin);
  url.searchParams.set("action", action);
  url.searchParams.set("nonce", nonce);
  url.searchParams.set("current_id", currentId);

  root.innerHTML = '<p>Loading books…</p>';

  fetch(url.toString(), { credentials: "same-origin" })
    .then((res) => res.json())
    .then((data) => {
      if (!data || !data.success) {
        root.innerHTML = "<p>Loading error...</p>";
        return;
      }
      const items = data.data.items || [];
      if (!items.length) {
        root.innerHTML = "<p>No books found!</p>";
        return;
      }

      root.innerHTML = `
        <ul class="space-y-5">
          ${items
            .map((item) => {
              const genres = (item.genres || [])
                .map((genre) => `<a href="${genre.url}">${escapeHtml(genre.name)}</a>`)
                .join(", ");

              return `
                <li class="border border-black/10 rounded-2xl p-5">
                  <h3 class="text-lg font-normal">
                    <a href="${item.url}">${escapeHtml(item.title)}</a>
                  </h3>
                  <div class="mt-2 text-base flex flex-row justify-between">
                    <span>${escapeHtml(item.date_h)}</span>
                    ${genres ? `  <span>${genres}</span>` : ""}
                  </div>
                  <p class="mt-2 text-base">${escapeHtml(item.excerpt)}</p>
                </li>
              `;
            })
            .join("")}
        </ul>
      `;
    })
    .catch(() => {
      root.innerHTML = "<p>Oops! An error occurred</p>";
    });

  function escapeHtml(str) {
    return String(str ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }
})();
