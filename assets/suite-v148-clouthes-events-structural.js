/* WP BBTheme Child Woo Clothes 3.8.11.48 - Events-style structural homepage runtime. */
(function(W,D){
  'use strict';

  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function text(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function unique(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){return unique(arr||[]).filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}
  function home(){return !!(D.body&&D.body.classList.contains('wpbb-v148-home'));}

  function promoteImage(img,hero){
    if(!img)return;
    var current=String(img.getAttribute('src')||'').trim();
    if(!current||/^data:image\//i.test(current)||/placeholder|transparent|spacer|blank\.(?:gif|png|svg)/i.test(current)){
      ['data-src','data-lazy-src','data-original','data-full-url','data-orig-file'].some(function(name){
        var value=String(img.getAttribute(name)||'').trim();
        if(!value)return false;
        img.setAttribute('src',value);return true;
      });
    }
    if(!String(img.getAttribute('srcset')||'').trim()){
      ['data-srcset','data-lazy-srcset'].some(function(name){
        var value=String(img.getAttribute(name)||'').trim();
        if(!value)return false;
        img.setAttribute('srcset',value);return true;
      });
    }
    var picture=img.parentElement&&img.parentElement.tagName==='PICTURE'?img.parentElement:null;
    if(picture){qa('source',picture).forEach(function(source){
      if(!String(source.getAttribute('srcset')||'').trim()){
        var value=String(source.getAttribute('data-srcset')||source.getAttribute('data-lazy-srcset')||'').trim();
        if(value)source.setAttribute('srcset',value);
      }
    });}
    img.setAttribute('loading','eager');
    img.setAttribute('decoding','async');
    if(hero)img.setAttribute('fetchpriority','high');
    img.style.setProperty('opacity','1','important');
    img.style.setProperty('visibility','visible','important');
  }

  function bestHost(scope,cards){
    cards=topLevel(cards||[]);if(!scope||cards.length<2)return null;
    var candidates=unique(cards.reduce(function(out,card){
      var node=card;
      for(var depth=0;node&&node!==scope&&depth<7;depth++,node=node.parentElement){
        if(node.matches&&node.matches('.row,.wpbb-row,.wpbb-v62-card-grid,.wpbb-sector-grid,.wp-theme-case-grid,.wp-theme-sector-services,.wp-theme-sector-industries,.wp-theme-sector-proof,.wp-theme-sector-process-grid,.wp-block-post-template'))out.push(node);
      }
      return out;
    },[]));
    var best=null;
    candidates.forEach(function(host){
      var direct=kids(host).filter(function(child){return cards.some(function(card){return child===card||child.contains(card);});});
      if(direct.length<2)return;
      if(!best||direct.length>best.items.length)best={host:host,items:direct};
    });
    if(best)return best;
    var parent=cards[0].parentElement;
    if(parent&&cards.every(function(card){return card.parentElement===parent;}))return{host:parent,items:cards.slice()};
    return null;
  }

  function clearOwned(scope,host){
    if(!scope||!host)return;
    var node=host.parentElement;
    while(node&&node!==scope){
      node.classList.remove('wpbb-v148-grid','wpbb-v148-cols-2','wpbb-v148-cols-3','wpbb-v148-cols-4');
      node=node.parentElement;
    }
  }

  function markGrid(host,items,cols,extra){
    if(!host||!items||items.length<2)return;
    clearOwned(host.closest('.wp-theme-section-shell')||host.parentElement,host);
    host.classList.add('wpbb-v148-grid','wpbb-v148-cols-'+Math.max(2,Math.min(4,cols||items.length)));
    if(extra)host.classList.add(extra);
    items.forEach(function(item){item.classList.add('wpbb-v148-grid-cell');});
  }

  function markCards(sectionSelector,cardSelector,cols,extra){
    qa('#wp-theme-main '+sectionSelector).forEach(function(section){
      var cards=topLevel(qa(cardSelector,section));
      var pick=bestHost(section,cards);
      if(pick)markGrid(pick.host,pick.items,cols,extra);
    });
  }

  function repairKnownGrids(){
    markCards('.clothes-category-section','.clothes-category-tile',4,'wpbb-v148-category-grid');
    markCards('.wp-theme-services-section','.wp-theme-sector-card,.wpbb-icon-card',3,'wpbb-v148-services-grid');
    markCards('.wp-theme-industries-section','.wp-theme-sector-card,.wpbb-icon-card',4,'wpbb-v148-industries-grid');
    markCards('.wp-theme-home-stats','.wpbb-fun-fact,.wp-theme-sector-proof__item',4,'wpbb-v148-stats-grid');
    markCards('.wp-theme-cases-section','.wp-theme-case-card',3,'wpbb-v148-cases-grid');
    markCards('.wp-theme-process-section','.wp-theme-sector-card',3,'wpbb-v148-process-grid');

    qa('#wp-theme-main .wp-theme-blog-preview .wp-block-post-template').forEach(function(host){
      var items=kids(host).filter(function(item){return !!q('article,img,a,h2,h3',item);});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v148-blog-grid');
    });
  }

  function liveSwiper(root){
    if(!root)return null;
    var el=root.matches&&root.matches('.swiper')?root:q('.swiper',root);
    return el&&el.swiper?el.swiper:null;
  }

  function heroRoot(){return q('#wp-theme-main .wp-theme-sector-hero .wpbb-swiper--hero')||q('#wp-theme-main .wp-theme-sector-hero .wpbb-swiper');}
  function heroSwiperElement(root){return root&&(root.matches&&root.matches('.swiper')?root:q('.swiper',root));}
  function realSlides(sw){
    if(!sw)return[];
    var seen={};
    return qa('.swiper-wrapper > .swiper-slide',sw).filter(function(slide,i){
      var key=slide.getAttribute('data-swiper-slide-index');
      if(key===null||key==='')key='dom-'+i;
      if(seen[key])return false;
      seen[key]=1;return true;
    });
  }
  function activeHeroIndex(swEl,count){
    var api=swEl&&swEl.swiper;
    if(api&&typeof api.realIndex==='number')return Math.max(0,Math.min(count-1,api.realIndex));
    var slides=realSlides(swEl),active=q('.swiper-slide-active',swEl),index=slides.indexOf(active);
    return index>=0?Math.min(count-1,index):0;
  }
  function paintHeroPager(pager,swEl,count){
    var active=activeHeroIndex(swEl,count);
    qa('button',pager).forEach(function(button,index){
      var on=index===active;
      button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function ensureHeroPager(root,swEl){
    if(!root||!swEl)return;
    qa('.wpbb-v141-hero-pagination,.wpbb-v142-hero-pagination,.wpbb-v143-hero-pagination,.wpbb-v144-hero-pagination,.wpbb-v145-hero-pagination,.wpbb-v146-hero-pager,.wpbb-v147-hero-pager,.wpbb-v140-hero-pagination',root).forEach(function(node){node.remove();});
    var count=realSlides(swEl).length;
    if(count<2)return;
    var pager=q(':scope > .wpbb-v148-hero-pager',root);
    if(!pager){pager=D.createElement('div');pager.className='wpbb-v148-hero-pager';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');root.appendChild(pager);}
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var button=D.createElement('button');button.type='button';button.textContent=String(i+1).padStart(2,'0');button.dataset.wpbbV148Slide=String(i);button.setAttribute('aria-label','Show hero slide '+(i+1)+' of '+count);pager.appendChild(button);
      }
    }
    if(!pager.dataset.wpbbV148Bound){
      pager.dataset.wpbbV148Bound='1';
      pager.addEventListener('click',function(event){
        var button=event.target&&event.target.closest?event.target.closest('button[data-wpbb-v148-slide]'):null;if(!button)return;
        var index=parseInt(button.dataset.wpbbV148Slide,10)||0,api=swEl.swiper;
        if(api){try{if(typeof api.slideToLoop==='function')api.slideToLoop(index);else if(typeof api.slideTo==='function')api.slideTo(index);}catch(e){}}
        paintHeroPager(pager,swEl,count);
      });
    }
    var api=swEl.swiper;
    if(api&&pager._wpbbV148Api!==api){
      pager._wpbbV148Api=api;
      if(typeof api.on==='function'){
        var update=function(){paintHeroPager(pager,swEl,count);};
        try{api.on('slideChange',update);api.on('realIndexChange',update);api.on('transitionEnd',update);}catch(e){}
      }
    }
    paintHeroPager(pager,swEl,count);
  }

  function repairHero(){
    var root=heroRoot(),swEl=heroSwiperElement(root);if(!root||!swEl)return;
    qa('.wpbb-swiper-slide__media img',root).forEach(function(img){promoteImage(img,true);});
    qa('.swiper-button-prev,.swiper-button-next,.swiper-pagination,.swiper-scrollbar',root).forEach(function(node){node.setAttribute('aria-hidden','true');});
    ensureHeroPager(root,swEl);
  }

  /* Cache-safe partner fallback: replace a still-looping legacy swiper with six unique tiles. */
  function repairPartners(){
    var section=q('#wp-theme-main .wp-theme-partners-section');if(!section||q('.wpbb-v148-partners-grid',section))return;
    var swiper=q('.swiper',section);if(!swiper)return;
    var cards=[],seen={};
    qa('.swiper-wrapper > .swiper-slide',swiper).forEach(function(slide){
      var title=text(q('h1,h2,h3,h4,h5,h6,.wpbb-swiper-slide__title',slide));
      if(!title||seen[title.toLowerCase()])return;
      seen[title.toLowerCase()]=1;
      var img=q('img',slide);cards.push({title:title,src:img?String(img.getAttribute('src')||img.getAttribute('data-src')||'').trim():''});
    });
    if(cards.length<2)return;
    var grid=D.createElement('div');grid.className='wpbb-v148-partners-grid';grid.setAttribute('aria-label','Trusted partners');
    cards.slice(0,6).forEach(function(card){
      var tile=D.createElement('div');tile.className='wpbb-v148-partner';
      if(card.src){var img=D.createElement('img');img.src=card.src;img.alt='';img.loading='eager';img.decoding='async';tile.appendChild(img);}
      var span=D.createElement('span');span.textContent=card.title;tile.appendChild(span);grid.appendChild(tile);
    });
    var block=swiper.closest('.wpbb-swiper')||swiper;
    block.parentNode.insertBefore(grid,block);block.style.setProperty('display','none','important');
  }

  /* Cache-safe values fallback. The server renderer normally replaces this row. */
  function repairValues(){
    if(q('#wp-theme-main .wpbb-v148-values-grid'))return;
    var section=q('#wp-theme-main .clothes-values');if(!section)return;
    var headings=qa('h2,h3,h4',section).filter(function(el){return /^(Natural materials|Considered fit|Easy returns)$/i.test(text(el));});
    if(headings.length<3)return;
    var cards=topLevel(headings.map(function(h){return h.closest('.wp-theme-sector-card,.wpbb-icon-card,.wp-block-group')||h.parentElement;}));
    var pick=bestHost(section,cards);if(pick)markGrid(pick.host,pick.items,3,'wpbb-v148-values-fallback');
    qa('h2,h3,h4,p',section).forEach(function(el){el.style.setProperty('writing-mode','horizontal-tb','important');el.style.setProperty('word-break','normal','important');});
  }

  function promoteMedia(){qa('#wp-theme-main img').forEach(function(img){promoteImage(img,false);});}

  function removeLegacyArtifacts(){
    qa('#wp-theme-main .wpbb-v141-hero-pagination,#wp-theme-main .wpbb-v142-hero-pagination,#wp-theme-main .wpbb-v143-hero-pagination,#wp-theme-main .wpbb-v144-hero-pagination,#wp-theme-main .wpbb-v145-hero-pagination,#wp-theme-main .wpbb-v146-hero-pager,#wp-theme-main .wpbb-v147-hero-pager').forEach(function(node){node.remove();});
  }

  function run(){
    if(!home())return;
    removeLegacyArtifacts();repairHero();repairPartners();repairValues();repairKnownGrids();promoteMedia();
  }

  var timer=0;
  function schedule(delay){W.clearTimeout(timer);timer=W.setTimeout(run,delay||30);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(1);},{once:true});else schedule(1);
  W.addEventListener('load',function(){run();[160,450,1000,2200].forEach(function(ms){W.setTimeout(run,ms);});},{once:true});
  W.addEventListener('resize',function(){schedule(110);},{passive:true});
  if(W.MutationObserver){
    var root=q('#wp-theme-main');
    if(root){var queued=false,observer=new MutationObserver(function(records){if(!records.some(function(r){return r.addedNodes&&r.addedNodes.length;}))return;if(queued)return;queued=true;W.setTimeout(function(){queued=false;run();},55);});observer.observe(root,{childList:true,subtree:true});W.setTimeout(function(){observer.disconnect();},6500);}
  }
})(window,document);
