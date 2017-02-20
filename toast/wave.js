
var ocean = document.getElementById("ocean"),
    waveWidth = 1,
    waveCount = Math.floor(window.innerWidth/waveWidth),
    docFrag = document.createDocumentFragment();

for(var i = 0; i < waveCount; i++){
  var wave = document.createElement("div");
  wave.className += " wave";
  docFrag.appendChild(wave);
  wave.style.left = i * waveWidth + "px";
  // wave.style.right = (waveCount - i) * waveWidth + "px";
  // wave.style.center = i * waveWidth + "px";
  wave.style.webkitAnimationDelay = (i/1000) + "s";

  var wave2 = document.createElement("div");
  wave2.className += " wave";
  docFrag.appendChild(wave2);
  // wave2.style.left = i * waveWidth + "px";
  wave2.style.left = (i + 20) * waveWidth + "px";
  // wave.style.center = i * waveWidth + "px";
  wave2.style.webkitAnimationDelay = (i/100) + "s";
}

for(var i = waveCount; i == 0; i--){
  var wave = document.createElement("div");
  wave.className += " wave";
  docFrag.appendChild(wave);
  wave.style.left = i * waveWidth + "px";
  // wave.style.right = (waveCount - i) * waveWidth + "px";
  // wave.style.center = i * waveWidth + "px";
  wave.style.webkitAnimationDelay = (i/100) + "s";
}

ocean.appendChild(docFrag);

