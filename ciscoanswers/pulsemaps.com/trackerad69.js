var img = document.createElement('img');
img.src = location.protocol + '//pulsemaps.com/t/e.gif?rand=' + Math.random() + '&id=360284943';
img.style.position = 'fixed';
img.style.bottom = '0px';
img.style.left = '0px';
img.width = '0';
img.height = '0';
document.body.appendChild(img);
