


var scr = document.getElementById('pulsemaps_360284943');


var a = document.createElement('a');
a.href = location.protocol + '//pulsemaps.com/maps/360284943/';
a.title = 'Click for more details!  Visitor map widget by PulseMaps.com.';
a.target = '_blank';
scr.parentNode.insertBefore(a, scr);


var span = document.createElement('span');
span.className = 'pulsemaps-widget';
span.style.display= 'inline-block';


a.appendChild(span);


var div = document.createElement('div');
div.id = 'pulsemaps_div_360284943';
div.style.width = '180px';
div.style.height = '126px';
div.style.padding = '0px';
div.style.border = '0px';
div.style.backgroundColor = '#0b0a32';
div.style.backgroundImage = 'url(' + location.protocol + '//pulsemaps.com/static/maps/satellite-map3-180.jpg)';
div.style.backgroundPosition = 'bottom';
div.style.backgroundRepeat = 'no-repeat';
div.style.backgroundSize = '100%';
div.style.position = 'relative';
span.appendChild(div);


var ovl = document.createElement('div');
ovl.style.width = '100%';
ovl.style.height = '100%';
ovl.style.padding = '0px';
ovl.style.border = '0px';
ovl.style.backgroundColor = 'transparent';
ovl.style.backgroundImage = 'url(' + location.protocol + '//pulsemaps.com/widget.png?id=360284943&c4=ff0000&c5=ffff00&c6=ffffff&width=180)';
ovl.style.backgroundPosition = 'bottom';
ovl.style.backgroundRepeat = 'no-repeat';
ovl.style.backgroundSize = '100%';
div.appendChild(ovl);

var meta = document.createElement('div');
meta.id = 'pulsemaps_meta_360284943';
meta.style.position = 'absolute';
meta.style.fontFamily = 'Helvetica';
meta.style.fontWeight = 'bold';
meta.style.fontSize = '10px';
meta.style.lineHeight = '12px';
meta.style.color = '#f2efe8';
meta.style.backgroundColor = '#0b0a32';
meta.style.left = 0;
meta.style.top = 0;
meta.style.paddingTop = '5px';
meta.style.width = '100%';
meta.style.textAlign = 'center';

var scr = document.createElement('script');
scr.src = location.protocol + '//pulsemaps.com/counts.js?id=360284943&meta=2';
document.body.appendChild(scr);

div.appendChild(meta);




var pulsemaps = pulsemaps || {};

pulsemaps.hit_flash = function(i) {
    var hit = document.getElementById('pulsemaps_hit_' + i);
	if (hit) {
		hit.style.backgroundPosition = '-9px 0px';
		hit.style.zIndex = 10;
	}
}


pulsemaps.hit_normal = function(i) {
    var hit = document.getElementById('pulsemaps_hit_' + i);
	if (hit) {
		hit.style.backgroundPosition = '0px 0px';
		hit.style.zIndex = 1;
	}
}

pulsemaps.flasher = null;

pulsemaps.stop_flashing = function() {
	if (pulsemaps.flasher) {
		clearTimeout(pulsemaps.flasher);
		pulsemaps.flasher = null;
	}
}

pulsemaps.num_recents = 0;

pulsemaps.hit_flasher = function() {
	if (pulsemaps.num_recents == 0) {
		return;
	}
	pulsemaps.stop_flashing();
    i = Math.floor(Math.random() * pulsemaps.num_recents);
    pulsemaps.hit_flash(i);
    setTimeout(function() { pulsemaps.hit_normal(i); }, 500);
    pulsemaps.flasher = setTimeout(pulsemaps.hit_flasher, Math.floor(Math.random() * 2000) + 2000);
}

pulsemaps.recent = function(recent) {
	if (recent.x == 0 && recent.y == 0) {
		return;
	}
	i = pulsemaps.num_recents++;
    var hit = document.createElement('div');
    hit.id = 'pulsemaps_hit_' + i;
    hit.style.backgroundImage = 'url(' + location.protocol + '//pulsemaps.com/static/dots/dots-ffffff.png)';
	hit.style.backgroundPosition = '0px 0px';
    hit.style.width = '9px';
    hit.style.height = '9px';
    hit.style.position = 'absolute';
    hit.style.left = Math.round(recent.x * 180 - 4) + 'px';
    hit.style.top = Math.round(recent.y * 180 + 1 + 25) + 'px';
    hit.style.zIndex = 1;
    div.appendChild(hit);
}


var scr = document.createElement('script');
scr.src = location.protocol + '//pulsemaps.com/t/recents.js?id=360284943';
document.body.appendChild(scr);

setTimeout(pulsemaps.hit_flasher, 2000);

