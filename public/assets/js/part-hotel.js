(function () {
  const grid = document.getElementById("poiHotelGrid");
  const dotsWrap = document.getElementById("poiHotelDots");

  if (!grid || !dotsWrap) return;

  const cards = grid.querySelectorAll(".poi-hotel-card");
  if (!cards.length) return;

  cards.forEach((_, i) => {
    const dot = document.createElement("span");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dotsWrap.appendChild(dot);
  });

  const dots = dotsWrap.querySelectorAll(".dot");

  function updateDots() {
    const scrollLeft = grid.scrollLeft;
    const cardWidth = grid.offsetWidth + parseFloat(getComputedStyle(grid).gap || 16);
    const index = Math.round(scrollLeft / cardWidth);
    dots.forEach((d, i) => d.classList.toggle("active", i === index));
  }

  grid.addEventListener("scroll",
    updateDots,
    {
      passive: true
    });
}());