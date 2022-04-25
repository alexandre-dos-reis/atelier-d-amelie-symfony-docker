import MagicGrid from "magic-grid";
import BigPicture from "bigpicture";

export default function gallery() {
  document.addEventListener("turbo:load", () => {
    const container = document.querySelector(".galerie-container");

    if (container) {
      setTimeout(() => {
        let magicGrid = new MagicGrid({
          container, // Required. Can be a class, id, or an HTMLElement.
          static: true,
          useTransform: false,
          animate: false,
          // center: true,
          useMin: true,
          maxColumns: 4,
          gutter: 50,
        });
        magicGrid.listen();
      }, 200);

      container.addEventListener("click", (e) => {
        e.preventDefault();
        BigPicture({
          el: e.target,
          gallery: ".galerie-container",
          noLoader: true,
        });
      });
    }
  });
}

gallery();