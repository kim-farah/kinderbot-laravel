const cards=document.querySelectorAll('.lesson-card');

cards.forEach(card=>{

const trigger=card.querySelector('.accordion-trigger');

trigger.addEventListener('click',()=>{

card.classList.toggle('open');

});

});

/* IMAGE SWITCH */

const mainPrintable = document.getElementById('mainPrintable');
const swapBtn = document.getElementById('swapImageBtn');

if(swapBtn && mainPrintable){

let toggled=false;

swapBtn.addEventListener('click',()=>{

mainPrintable.src = toggled
? mainPrintable.dataset.original
: mainPrintable.dataset.alt;

toggled=!toggled;

});

}


/* DRAG CLONE (original stays) */

const tokens=document.querySelectorAll('.drag-token');
const dropZone=document.getElementById('dropZone');

let dragged=null;

tokens.forEach(item=>{

item.addEventListener('dragstart',()=>{

dragged=item;

});

});

if(dropZone){

dropZone.addEventListener('dragover',(e)=>{
e.preventDefault();
});

dropZone.addEventListener('drop',()=>{

dropZone.innerHTML='';

const clone=dragged.cloneNode(true);

clone.style.width='100%';
clone.style.height='100%';
clone.style.borderRadius='50%';
clone.draggable=false;

dropZone.appendChild(clone);

dropZone.style.borderColor='#FEC243';

});

}