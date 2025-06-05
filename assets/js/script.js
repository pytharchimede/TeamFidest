document.querySelectorAll("#banc li").forEach(li => {
  li.addEventListener("dragstart", e => {
    e.dataTransfer.setData("text", e.target.innerText);
  });
});
document.querySelectorAll(".zone").forEach(zone => {
  zone.addEventListener("dragover", e => e.preventDefault());
  zone.addEventListener("drop", e => {
    e.preventDefault();
    const data = e.dataTransfer.getData("text");
    zone.innerText = data;
  });
});
