/* Woo Clothes 3.8.11.39 - final homepage finder and mega-menu runtime. */
(function(W,D){
  'use strict';
  var H=D.documentElement;
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function desktop(){return W.matchMedia('(min-width: 992px)').matches;}

  function mark(){if(D.body)D.body.classList.add('wpbb-v139');}

  function removeDuplicateFinders(){
    if(!D.body||!(D.body.classList.contains('home')||D.body.classList.contains('front-page')||D.body.classList.contains('wpbb-v139-home')))return;
    var finders=qa('#wp-theme-main .wpbb-v97-hero-finder');
    if(finders.length<2)return;
    finders.slice(1).forEach(function(finder){finder.classList.add('wpbb-v139-duplicate-quickfinder');});
  }

  function menuTrigger(menu){
    var li=menu&&menu.parentElement;if(!li)return null;
    try{return li.querySelector(':scope > a, :scope > button, :scope > .wp-theme-nav-link')||li;}
    catch(e){return li.querySelector('a,button')||li;}
  }
  function positionMega(menu){
    if(!menu)return;
    if(!desktop()){menu.style.removeProperty('top');return;}
    var trigger=menuTrigger(menu);if(!trigger||!trigger.getBoundingClientRect)return;
    var r=trigger.getBoundingClientRect();
    var li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r;
    var bottom=Math.ceil(Math.max(r.bottom,lr.bottom)-6);
    if(bottom>0){menu.style.setProperty('top',bottom+'px','important');H.style.setProperty('--wpbb-v139-mega-top',bottom+'px');}
  }
  function positionMegas(){qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(positionMega);}
  function bindMegas(){
    qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(function(menu){
      if(menu.dataset.wpbbV139Bound)return;
      menu.dataset.wpbbV139Bound='1';
      var li=menu.parentElement;
      if(li){
        li.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});
        li.addEventListener('focusin',function(){positionMega(menu);});
      }
    });
    positionMegas();
  }

  function run(){mark();removeDuplicateFinders();bindMegas();}
  var timer=0;function schedule(){clearTimeout(timer);timer=W.setTimeout(run,30);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();W.setTimeout(run,250);W.setTimeout(run,900);});
  W.addEventListener('resize',function(){clearTimeout(timer);timer=W.setTimeout(positionMegas,80);},{passive:true});
  if(W.MutationObserver){
    var queued=false;
    new MutationObserver(function(ms){
      if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;
      if(queued)return;queued=true;
      W.setTimeout(function(){queued=false;run();},60);
    }).observe(D.documentElement,{childList:true,subtree:true});
  }
})(window,document);
