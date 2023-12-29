export default function setHeightOfRestOfPage(containerId) {
  const headerHeight = document.querySelector("#header").offsetHeight;
  const windowHeight = window.innerHeight;
  const containerHegiht = windowHeight - headerHeight;
  document
    .querySelector(containerId)
    .setAttribute("style", `height: ${containerHegiht}px`);
}
