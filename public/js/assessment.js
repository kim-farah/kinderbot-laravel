document.querySelectorAll('.rating-row').forEach(row=>{

const buttons=row.querySelectorAll('.rate-pill');
const hidden=row.querySelector('input');

buttons.forEach(btn=>{
btn.addEventListener('click',()=>{

buttons.forEach(b=>b.classList.remove('active'));
btn.classList.add('active');

hidden.value=btn.dataset.score;

});
});

});