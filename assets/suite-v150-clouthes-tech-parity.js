/* WP BBTheme Child Woo Clothes 3.8.11.50 - Tech Shop parity runtime. */
(function(W,D){
  'use strict';
  var R=D.documentElement;
  function q(s,r){try{return(r||D).querySelector(s);}catch(e){return null;}}
  function qa(s,r){try{return Array.prototype.slice.call((r||D).querySelectorAll(s));}catch(e){return[];}}
  function home(){return D.body&&D.body.classList.contains('wpbb-v150-tech-parity');}
  function desktop(){return W.matchMedia('(min-width: 992px)').matches;}

  function measureHeaderRail(){
    var candidates=[q('.wp-theme-header-main > .container'),q('.wp-theme-header-main .container'),q('.wp-theme-site-header .container')].filter(Boolean),best=null;
    candidates.some(function(el){var r=el.getBoundingClientRect();if(r.width>420&&r.width<=W.innerWidth+2){best=r;return true;}return false;});
    if(!best)return;
    var left=Math.max(16,Math.round(best.left)),right=Math.max(16,Math.round(W.innerWidth-best.right));
    R.style.setProperty('--cl150-left',left+'px');R.style.setProperty('--cl150-right',right+'px');
  }

  function menuTrigger(menu){var li=menu&&menu.parentElement;if(!li)return null;try{return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link')||li;}catch(e){return li.querySelector('a,button')||li;}}
  function positionMega(menu){
    if(!menu)return;if(!desktop()){menu.style.removeProperty('top');return;}
    var t=menuTrigger(menu);if(!t||!t.getBoundingClientRect)return;
    var r=t.getBoundingClientRect(),li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r;
    var bottom=Math.ceil(Math.max(r.bottom,lr.bottom)-3);if(bottom>0)menu.style.setProperty('top',bottom+'px','important');
  }
  function positionMegas(){qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(positionMega);}

  function stripLegacyFinder(){
    qa('.wpbb-v97-hero-finder,.wpbb-v139-hero-finder,.wpbb-v140-hero-finder').forEach(function(el){if(!el.closest('.clothes149-search'))el.remove();});
  }

  function hero(){
    var root=q('.clothes149-hero');if(!root)return;
    var slides=qa('.clothes149-hero__slide',root),dots=qa('[data-clothes149-slide]',root);if(slides.length<2)return;
    var index=0,timer=0,delay=8500;
    function paint(next){
      index=(next+slides.length)%slides.length;
      slides.forEach(function(slide,i){var on=i===index;slide.classList.toggle('is-active',on);slide.setAttribute('aria-hidden',on?'false':'true');});
      dots.forEach(function(dot,i){var on=i===index;dot.classList.toggle('is-active',on);if(on)dot.setAttribute('aria-current','true');else dot.removeAttribute('aria-current');});
    }
    function start(){W.clearInterval(timer);timer=W.setInterval(function(){paint(index+1);},delay);}
    dots.forEach(function(dot,i){if(dot.dataset.cl150Bound)return;dot.dataset.cl150Bound='1';dot.addEventListener('click',function(){paint(i);start();});});
    root.addEventListener('mouseenter',function(){W.clearInterval(timer);},{passive:true});
    root.addEventListener('mouseleave',start,{passive:true});
    root.addEventListener('focusin',function(){W.clearInterval(timer);});
    root.addEventListener('focusout',start);
    paint(0);start();
  }

  function run(){if(!home())return;measureHeaderRail();stripLegacyFinder();positionMegas();hero();}
  var resizeTimer=0;
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',run,{once:true});else run();
  W.addEventListener('load',function(){run();W.setTimeout(run,300);W.setTimeout(run,1000);});
  W.addEventListener('resize',function(){W.clearTimeout(resizeTimer);resizeTimer=W.setTimeout(function(){measureHeaderRail();positionMegas();},90);},{passive:true});
  W.addEventListener('scroll',function(){W.requestAnimationFrame(positionMegas);},{passive:true});
})(window,document);
