/* WP BBTheme Child Woo Clothes 3.8.11.51 - deterministic Tech Shop parity runtime. */
(function(W,D){
  'use strict';
  function q(s,r){try{return(r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return[];}}
  function home(){return D.body&&D.body.classList.contains('wpbb-v151-tech-source');}

  function menu(){
    var button=q('.clothes151-menu-toggle'),nav=q('.clothes151-primary-nav');
    if(!button||!nav)return;
    function set(open){D.body.classList.toggle('is-clothes151-menu-open',!!open);button.setAttribute('aria-expanded',open?'true':'false');}
    if(!button.dataset.cl151Bound){
      button.dataset.cl151Bound='1';
      button.addEventListener('click',function(){set(!D.body.classList.contains('is-clothes151-menu-open'));});
      nav.addEventListener('click',function(e){if(e.target&&e.target.closest&&e.target.closest('a'))set(false);});
      D.addEventListener('keydown',function(e){if(e.key==='Escape')set(false);});
      W.addEventListener('resize',function(){if(W.matchMedia('(min-width:1040px)').matches)set(false);},{passive:true});
    }
  }

  function hero(){
    var root=q('.clothes149-hero');if(!root)return;
    var slides=qa('.clothes149-hero__slide',root),dots=qa('[data-clothes149-slide]',root);if(slides.length<2)return;
    var index=0,timer=0,delay=9000;
    function paint(next){
      index=(next+slides.length)%slides.length;
      slides.forEach(function(slide,i){var on=i===index;slide.classList.toggle('is-active',on);slide.setAttribute('aria-hidden',on?'false':'true');});
      dots.forEach(function(dot,i){var on=i===index;dot.classList.toggle('is-active',on);if(on)dot.setAttribute('aria-current','true');else dot.removeAttribute('aria-current');});
    }
    function stop(){if(timer){W.clearInterval(timer);timer=0;}}
    function start(){stop();timer=W.setInterval(function(){paint(index+1);},delay);}
    dots.forEach(function(dot,i){if(dot.dataset.cl151Bound)return;dot.dataset.cl151Bound='1';dot.addEventListener('click',function(){paint(i);start();});});
    if(!root.dataset.cl151Bound){
      root.dataset.cl151Bound='1';
      root.addEventListener('mouseenter',stop,{passive:true});root.addEventListener('mouseleave',start,{passive:true});
      root.addEventListener('focusin',stop);root.addEventListener('focusout',start);
      root.addEventListener('keydown',function(e){if(e.key==='ArrowLeft'){paint(index-1);start();}else if(e.key==='ArrowRight'){paint(index+1);start();}});
    }
    paint(0);start();
  }

  function imageFallbacks(){
    qa('img[data-clothes151-fallback]').forEach(function(img){
      if(img.dataset.cl151FallbackBound)return;img.dataset.cl151FallbackBound='1';
      var fallback=img.getAttribute('data-clothes151-fallback');
      img.addEventListener('error',function(){if(!fallback||img.src===fallback)return;img.src=fallback;},{once:true});
      if(img.complete&&img.naturalWidth===0&&fallback)img.src=fallback;
    });
  }

  function run(){if(!home())return;menu();hero();imageFallbacks();}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',run,{once:true});else run();
  W.addEventListener('load',function(){run();W.setTimeout(imageFallbacks,250);},{once:true});
})(window,document);
